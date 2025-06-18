<?php
session_start();
include '../koneksi.php';
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

function getRekapData($koneksi, $table)
{
    $query = "SELECT jenis_alat, 
                SUM(CASE WHEN permintaan = 'Perbaikan'  THEN 1 ELSE 0 END) AS jumlah_perbaikan,
                SUM(CASE WHEN permintaan = 'Ganti Baru'  THEN 1 ELSE 0 END) AS jumlah_pengganti,
                SUM(CASE WHEN stts_pengaduan = 'Ditolak' THEN 1 ELSE 0 END) AS jumlah_ditolak,
                SUM(CASE WHEN stts_pengaduan = 'Disetujui' THEN 1 ELSE 0 END) AS jumlah_disetujui,
                COUNT(*) AS jumlah
              FROM $table
              GROUP BY jenis_alat";

    $result = mysqli_query($koneksi, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

$pertanian = getRekapData($koneksi, 'pengaduan_alat_pertanian');
$perikanan = getRekapData($koneksi, 'pengaduan_alat_perikanan');
$ketahanan_pangan = getRekapData($koneksi, 'pengaduan_alat_ketahananpangan');

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard - Rekap Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #68A7AD;
        }

        .navbar-brand {
            font-weight: bold;
            color: #fff;
        }

        .card-wrap {
            max-width: 900px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-top: 20px;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
        }

        th {
            background-color: rgb(0, 0, 0);
            color: #333;
            font-weight: bold;
        }

        td,
        th {
            padding: 12px;
            text-align: center;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../home.php">SILADUMA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="../home.php">Home</a>
                    </li>
                </ul>
                <span class="navbar-profile">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown">
                                <?php echo $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item btn-logout" href="../logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </span>
            </div>
        </div>
    </nav>

    <!-- Rekap Pengaduan -->
    <div class="card-wrap">
        <h3 class="text-center">Rekap Pengaduan</h3>
        <div class="box-header with-border text-center">
            <button type="button" class="btn btn-success cetak-btn" onclick="printReport()">
                Cetak Laporan
            </button>
        </div>
        <?php
        $filter_jenis = isset($_GET['jenis_alat']) ? $_GET['jenis_alat'] : '';
        $filter_perbaikan = isset($_GET['perbaikan']) ? $_GET['perbaikan'] : '';
        $filter_pengganti = isset($_GET['ganti_baru']) ? $_GET['ganti_baru'] : '';
        $filter_disetujui = isset($_GET['disetujui']) ? $_GET['disetujui'] : '';
        $filter_ditolak = isset($_GET['ditolak']) ? $_GET['ditolak'] : '';

        $filters = [];
        if ($filter_jenis) $filters[] = "jenis_alat LIKE '%$filter_jenis%'";
        if ($filter_perbaikan) $filters[] = "jumlah_perbaikan = $filter_perbaikan";
        if ($filter_pengganti) $filters[] = "jumlah_pengganti = $filter_pengganti";
        if ($filter_disetujui) $filters[] = "jumlah_disetujui = $filter_disetujui";
        if ($filter_ditolak) $filters[] = "jumlah_ditolak = $filter_ditolak";

        $where_clause = count($filters) > 0 ? 'WHERE ' . implode(' AND ', $filters) : '';
        $query = "SELECT 'Pertanian' AS kategori, jenis_alat, 
            SUM(jumlah_perbaikan) AS jumlah_perbaikan,
            SUM(jumlah_pengganti) AS jumlah_pengganti,
            SUM(jumlah_ditolak) AS jumlah_ditolak,
            SUM(jumlah_disetujui) AS jumlah_disetujui,
            SUM(jumlah) AS jumlah
        FROM (
            SELECT jenis_alat, 
                SUM(CASE WHEN permintaan = 'Perbaikan' THEN 1 ELSE 0 END) AS jumlah_perbaikan,
                SUM(CASE WHEN permintaan = 'Ganti Baru' THEN 1 ELSE 0 END) AS jumlah_pengganti,
                SUM(CASE WHEN stts_pengaduan = 'Ditolak' THEN 1 ELSE 0 END) AS jumlah_ditolak,
                SUM(CASE WHEN stts_pengaduan = 'Disetujui' THEN 1 ELSE 0 END) AS jumlah_disetujui,
                COUNT(*) AS jumlah
            FROM pengaduan_alat_pertanian 
            GROUP BY jenis_alat
        ) AS pertanian
        $where_clause
        GROUP BY jenis_alat
    
        UNION ALL
    
        SELECT 'Perikanan' AS kategori, jenis_alat, 
            SUM(jumlah_perbaikan) AS jumlah_perbaikan,
            SUM(jumlah_pengganti) AS jumlah_pengganti,
            SUM(jumlah_ditolak) AS jumlah_ditolak,
            SUM(jumlah_disetujui) AS jumlah_disetujui,
            SUM(jumlah) AS jumlah
        FROM (
            SELECT jenis_alat, 
                SUM(CASE WHEN permintaan = 'Perbaikan' THEN 1 ELSE 0 END) AS jumlah_perbaikan,
                SUM(CASE WHEN permintaan = 'Ganti Baru' THEN 1 ELSE 0 END) AS jumlah_pengganti,
                SUM(CASE WHEN stts_pengaduan = 'Ditolak' THEN 1 ELSE 0 END) AS jumlah_ditolak,
                SUM(CASE WHEN stts_pengaduan = 'Disetujui' THEN 1 ELSE 0 END) AS jumlah_disetujui,
                COUNT(*) AS jumlah
            FROM pengaduan_alat_perikanan 
            GROUP BY jenis_alat
        ) AS perikanan
        $where_clause
        GROUP BY jenis_alat
    
        UNION ALL
    
        SELECT 'Ketahanan Pangan' AS kategori, jenis_alat, 
            SUM(jumlah_perbaikan) AS jumlah_perbaikan,
            SUM(jumlah_pengganti) AS jumlah_pengganti,
            SUM(jumlah_ditolak) AS jumlah_ditolak,
            SUM(jumlah_disetujui) AS jumlah_disetujui,
            SUM(jumlah) AS jumlah
        FROM (
            SELECT jenis_alat, 
                SUM(CASE WHEN permintaan = 'Perbaikan' THEN 1 ELSE 0 END) AS jumlah_perbaikan,
                SUM(CASE WHEN permintaan = 'Ganti Baru' THEN 1 ELSE 0 END) AS jumlah_pengganti,
                SUM(CASE WHEN stts_pengaduan = 'Ditolak' THEN 1 ELSE 0 END) AS jumlah_ditolak,
                SUM(CASE WHEN stts_pengaduan = 'Disetujui' THEN 1 ELSE 0 END) AS jumlah_disetujui,
                COUNT(*) AS jumlah
            FROM pengaduan_alat_ketahananpangan 
            GROUP BY jenis_alat
        ) AS ketahanan_pangan
        $where_clause
        GROUP BY jenis_alat";

        $result = mysqli_query($koneksi, $query);

        $data_pertanian = [];
        $data_perikanan = [];
        $data_ketahanan_pangan = [];

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['kategori'] == 'Pertanian') {
                $data_pertanian[] = $row;
            } elseif ($row['kategori'] == 'Perikanan') {
                $data_perikanan[] = $row;
            } elseif ($row['kategori'] == 'Ketahanan Pangan') {
                $data_ketahanan_pangan[] = $row;
            }
        }
        ?>
        <!-- Form Pencarian -->
        <form method="GET">
            <input type="text" name="jenis_alat" placeholder="Jenis Alat" value="<?php echo htmlspecialchars($filter_jenis); ?>">
            <input type="number" name="perbaikan" placeholder="Perbaikan" value="<?php echo htmlspecialchars($filter_perbaikan); ?>">
            <input type="number" name="ganti_baru" placeholder="Ganti Baru" value="<?php echo htmlspecialchars($filter_pengganti); ?>">
            <input type="number" name="disetujui" placeholder="Disetujui" value="<?php echo htmlspecialchars($filter_disetujui); ?>">
            <input type="number" name="ditolak" placeholder="Ditolak" value="<?php echo htmlspecialchars($filter_ditolak); ?>">
            <button type="submit">Cari</button>
        </form>
        <!-- Tabel Alat Pertanian -->
        <h4 class="text-center">Rekap Pengaduan Alat Pertanian</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Perbaikan</th>
                    <th>Ganti Baru</th>
                    <th>Disetujui</th>
                    <th>Ditolak</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_pertanian as $data) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['jenis_alat']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_perbaikan']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_pengganti']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_disetujui']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_ditolak']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Tabel Alat Perikanan -->
        <h4 class="text-center">Rekap Pengaduan Alat Perikanan</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Perbaikan</th>
                    <th>Ganti Baru</th>
                    <th>Disetujui</th>
                    <th>Ditolak</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_perikanan as $data) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['jenis_alat']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_perbaikan']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_pengganti']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_disetujui']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_ditolak']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Tabel Alat Ketahanan Pangan -->
        <h4 class="text-center">Rekap Pengaduan Alat Ketahanan Pangan</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Perbaikan</th>
                    <th>Ganti Baru</th>
                    <th>Disetujui</th>
                    <th>Ditolak</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_ketahanan_pangan as $data) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['jenis_alat']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_perbaikan']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_pengganti']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_disetujui']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah_ditolak']); ?></td>
                        <td><?php echo htmlspecialchars($data['jumlah']); ?></td>
                    </tr>
                <?php } ?>
 
            </tbody>
        </table>

        <div class="container mt-5">
            <h3 class="text-center">Grafik Rekap Pengaduan</h3>
            <canvas id="rekapChart"></canvas>
        </div>
    </div>

    <script>
        const labels = ["Pertanian", "Perikanan", "Ketahanan Pangan"];
        const dataPerbaikan = [
            <?= array_sum(array_column($pertanian, 'jumlah_perbaikan')) ?>,
            <?= array_sum(array_column($perikanan, 'jumlah_perbaikan')) ?>,
            <?= array_sum(array_column($ketahanan_pangan, 'jumlah_perbaikan')) ?>
        ];
        const dataPengganti = [
            <?= array_sum(array_column($pertanian, 'jumlah_pengganti')) ?>,
            <?= array_sum(array_column($perikanan, 'jumlah_pengganti')) ?>,
            <?= array_sum(array_column($ketahanan_pangan, 'jumlah_pengganti')) ?>
        ];
        const dataDisetujui = [
            <?= array_sum(array_column($pertanian, 'jumlah_disetujui')) ?>,
            <?= array_sum(array_column($perikanan, 'jumlah_disetujui')) ?>,
            <?= array_sum(array_column($ketahanan_pangan, 'jumlah_disetujui')) ?>
        ];
        const dataDitolak = [
            <?= array_sum(array_column($pertanian, 'jumlah_ditolak')) ?>,
            <?= array_sum(array_column($perikanan, 'jumlah_ditolak')) ?>,
            <?= array_sum(array_column($ketahanan_pangan, 'jumlah_ditolak')) ?>
        ];
        const dataJumlah = [
            <?= array_sum(array_column($pertanian, 'jumlah')) ?>,
            <?= array_sum(array_column($perikanan, 'jumlah')) ?>,
            <?= array_sum(array_column($ketahanan_pangan, 'jumlah')) ?>
        ];

        const ctx = document.getElementById('rekapChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Perbaikan',
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        data: dataPerbaikan
                    },
                    {
                        label: 'Ganti Baru',
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        data: dataPengganti
                    },
                    {
                        label: 'Disetujui',
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        data: dataDisetujui
                    },
                    {
                        label: 'Ditolak',
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        data: dataDitolak
                    },
                    {
                        label: 'Jumlah',
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        data: dataJumlah
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        function printReport() {
            let tables = document.querySelectorAll("table");

            let mergedTable = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Rekap</th>
                            <th>Perbaikan</th>
                            <th>Ganti Baru</th>
                            <th>Disetujui</th>
                            <th>Ditolak</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            let categories = ["Alat Pertanian", "Alat Perikanan", "Alat Ketahanan Pangan"];

            tables.forEach((table, index) => {
                let rows = table.querySelectorAll("tbody tr");

                let totalPerbaikan = 0,
                    totalPengganti = 0,
                    totalDisetujui = 0,
                    totalDitolak = 0,
                    totalJumlah = 0;

                rows.forEach(row => {
                    let cells = row.querySelectorAll("td");
                    totalPerbaikan += parseInt(cells[1]?.textContent) || 0;
                    totalPengganti += parseInt(cells[2]?.textContent) || 0;
                    totalDisetujui += parseInt(cells[3]?.textContent) || 0;
                    totalDitolak += parseInt(cells[4]?.textContent) || 0;
                    totalJumlah += parseInt(cells[5]?.textContent) || 0;
                });

                mergedTable += `
                    <tr>
                        <td>${categories [index]}</td>
                        <td>${totalPerbaikan}</td>
                        <td>${totalPengganti}</td>
                        <td>${totalDisetujui}</td>
                        <td>${totalDitolak}</td>
                        <td>${totalJumlah}</td>
                    </tr>
                `;
            });

            mergedTable += `</tbody></table>`;

            var headerContent = `
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                    <img src="/SILADU-main/petugas/img/logo_dinas.jpg" alt="Logo Dinas" style="width: 80px; height: auto; margin-right: 20px;" />
                    <div>
                        <h3 style="text-transform: uppercase; margin: 0; text-align: center;">Pemerintah Kota Banjarmasin</h3> 
                        <h3 style="text-transform: uppercase; margin: 0;">Dinas Ketahanan Pangan, Pertanian dan Perikanan Kota Banjarmasin</h3>
                        <p style="font-size: 14px; margin: 0;">Alamat: Jl. Lkr. Dalam Utara, Komplek Screen House, Kel. Benua Anyar, Kota Banjarmasin</p>
                    </div>
                </div>
                <hr />
            `;

            var footerContent = `
                <div style="text-align: right; margin-top: 50px;">
                    <p>Banjarmasin, ${new Date().toLocaleDateString('id-ID')}</p>
                    <p style="margin-bottom: 80px;">Kepala Dinas</p>
                    <p style="margin-top: 5px; font-weight: bold; text-decoration: underline;">Nama Kepala Dinas</p>
                    <p style="margin-top: -5px;">NIP: xxxxxxxxx</p>
                </div>
            `;

            let printWindow = window.open('', '', 'height=800,width=1000');
            printWindow.document.write('<html><head><title>Cetak Laporan Pengaduan Alat</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; margin: 40px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; margin: 20px 0; }');
            printWindow.document.write('th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }');
            printWindow.document.write('th { background-color: #f2f2f2; }');
            printWindow.document.write('p, h3 { margin: 0; }');
            printWindow.document.write('hr { margin: 20px 0; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(headerContent); 
            printWindow.document.write(mergedTable);
            printWindow.document.write(footerContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            printWindow.print();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>