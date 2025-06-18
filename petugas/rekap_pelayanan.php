<?php
session_start();
include '../koneksi.php';
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}
$rekapData = [];
$query = "SELECT jenis_izin, 
                COUNT(*) AS jumlah, 
                SUM(CASE WHEN status_pemohon = 'Disetujui' THEN 1 ELSE 0 END) AS disetujui, 
                SUM(CASE WHEN status_pemohon = 'Ditolak' THEN 1 ELSE 0 END) AS ditolak
         FROM pengajuan_izin";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    $query .= " WHERE jenis_izin LIKE '%" . $koneksi->real_escape_string($search) . "%'";
}

$query .= " GROUP BY jenis_izin";
$result = mysqli_query($koneksi, $query);

while ($data = mysqli_fetch_assoc($result)) {
    $rekapData[] = $data;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard - Rekap Perizinan</title>
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
            /* Ubah menjadi warna gelap */
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


    <div class="card-wrap">
        <h3 class="text-center">Rekap Pengajuan Izin</h3>
        <div class="box-header with-border text-center">
            <button type="button" class="btn btn-success cetak-btn" onclick="printReport()">
                Cetak Laporan
            </button>
        </div>
        <!-- Form Pencarian -->
        <form method="GET">
        <input type="text" name="search" placeholder="Jenis Izin" value="<?php echo htmlspecialchars($search); ?>" class="form-control d-inline-block" style="width: auto; display: inline-block; margin-right: 10px;">
            <button type="submit">Cari</button>
        </form>
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>Jenis Izin</th>
                    <th>Jumlah</th>
                    <th>Disetujui</th>
                    <th>Ditolak</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($rekapData as $data): ?>
                    <tr>
                        <td><?= ($data['jenis_izin']); ?></td>
                        <td><?= $data['jumlah']; ?></td>
                        <td><?= $data['disetujui']; ?></td>
                        <td><?= $data['ditolak']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="container mt-5">
            <h3 class="text-center">Grafik Rekap Pengajuan Izin</h3>
            <canvas id="rekapChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('rekapChart').getContext('2d');
        const rekapChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($rekapData, 'jenis_izin')); ?>,
                datasets: [{
                        label: 'Disetujui',
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        data: <?= json_encode(array_column($rekapData, 'disetujui')); ?>
                    },
                    {
                        label: 'Ditolak',
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        data: <?= json_encode(array_column($rekapData, 'ditolak')); ?>
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
            // Sembunyikan elemen yang tidak perlu ditampilkan saat mencetak
            const elementsToHide = document.querySelectorAll('th:nth-child(9), td:nth-child(9), button, textarea');
            elementsToHide.forEach(el => {
                el.style.display = 'none';
            });

            // Mengubah semua textarea menjadi teks biasa sebelum dicetak
            document.querySelectorAll('textarea').forEach(textarea => {
                const text = textarea.value;
                const span = document.createElement('span');
                span.textContent = text;
                span.style.display = 'block';
                textarea.parentNode.replaceChild(span, textarea);
            });

            // Ambil isi tabel setelah perubahan
            var tableContent = document.querySelector("table").outerHTML;

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

            // Tambahkan Tanda Tangan Kepala Dinas
            var footerContent = `
        <div style="text-align: right; margin-top: 50px;">
            <p>Banjarmasin, ${new Date().toLocaleDateString('id-ID')}</p>
            <p style="margin-bottom: 80px;">Kepala Dinas</p>
            <p style="margin-top: 5px; font-weight: bold; text-decoration: underline;">Nama Kepala Dinas</p>
            <p style="margin-top: -5px;">NIP: xxxxxxxxxx</p>
        </div>
    `;

            // Buat jendela cetak
            var printWindow = window.open('', '', 'height=800,width=1000');
            printWindow.document.write('<html><head><title>Cetak Laporan Rekap Pengajuan Izin</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; margin: 40px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; margin: 20px 0; }');
            printWindow.document.write('th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }');
            printWindow.document.write('th { background-color: #f2f2f2; }');
            printWindow.document.write('p, h3 { margin: 0; }');
            printWindow.document.write('hr { margin: 20px 0; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(headerContent); // Tambahkan header laporan
            printWindow.document.write(tableContent); // Tambahkan isi tabel
            printWindow.document.write(footerContent); // Tambahkan tanda tangan Kepala Dinas
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Cetak laporan
            printWindow.print();

            // Kembalikan tampilan elemen yang disembunyikan setelah cetak
            elementsToHide.forEach(el => {
                el.style.display = '';
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>