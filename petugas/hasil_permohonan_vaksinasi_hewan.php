<?php
session_start();
include "../koneksi.php";

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

// Ambil data dari form
$id_user = $_SESSION['id_user']; // Asumsi id_user diambil dari session
$jenis_ternak = $_POST['jenis_ternak'];
$jumlah_ternak = $_POST['jumlah_ternak'];
$jenis_vaksin = $_POST['jenis_vaksin'];
$alamat_pemohon = $_POST['alamat_pemohon'];

if (isset($_POST['simpan'])) {
    if (!empty($jenis_ternak) && !empty($jumlah_ternak) && !empty($jenis_vaksin) && !empty($alamat_pemohon)) {
        $sql = "INSERT INTO hasil_permohonan_vaksinasi_hewan (id_user, jenis_ternak, jumlah_ternak, jenis_vaksin, alamat_pemohon, status, tgl_permohonan) 
                VALUES ('$id_user', '$jenis_ternak', '$jumlah_ternak', '$jenis_vaksin', '$alamat_pemohon', 'diverifikasi', NOW())";

        $a = $koneksi->query($sql);
        if ($a === true) {
            echo "<script>alert('Berhasil Mengirim Permohonan Vaksinasi!');</script>";
            header("refresh:2;url=hasil_permohonan_vaksinasi_hewan.php");
        } else {
            echo "<script>alert('Gagal Mengirim Permohonan Vaksinasi!');</script>";
            header("refresh:2;url=hasil_permohonan_vaksinasi_hewan.php");
        }
    } else {
        echo "<script>alert('Ada Input yang Kosong!');</script>";
        echo "<script>history.back();</script>";
    }
} else {
    echo "<script>location('hasil_permohonan_vaksinasi_hewan.php');</script>";
}

if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM hasil_permohonan_vaksinasi_hewan WHERE id_permohonan='$_GET[id]'");
        if ($hapus) {
            echo "<script>alert('Hapus Data Sukses!'); location='hasil_permohonan_vaksinasi_hewan.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
        }
    } elseif ($_GET['hal'] == "verifikasi") {
        $update = mysqli_query($koneksi, "UPDATE hasil_permohonan_vaksinasi_hewan SET status='diverifikasi' WHERE id_permohonan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Permohonan Diverifikasi!'); location='hasil_permohonan_vaksinasi_hewan.php';</script>";
        } else {
            echo "<script>alert('Gagal Verifikasi!');</script>";
        }
    } elseif ($_GET['hal'] == "jadwalkan") {
        $update = mysqli_query($koneksi, "UPDATE hasil_permohonan_vaksinasi_hewan SET status='dijadwalkan' WHERE id_permohonan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Permohonan Dijadwalkan!'); location='hasil_permohonan_vaksinasi_hewan.php';</script>";
        } else {
            echo "<script>alert('Gagal Menjadwalkan!');</script>";
        }
    } elseif ($_GET['hal'] == "selesai") {
        $update = mysqli_query($koneksi, "UPDATE hasil_permohonan_vaksinasi_hewan SET status='selesai', tgl_selesai=NOW() WHERE id_permohonan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Permohonan Selesai!'); location='hasil_permohonan_vaksinasi_hewan.php';</script>";
        } else {
            echo "<script>alert('Gagal Menyelesaikan!');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Permohonan Vaksinasi Hewan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <style>
        h1 {
            margin-top: 80px;
            text-align: center;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .hasil {
            width: 95%;
        }

        .form-hasil {
            width: 85%;
            text-align: left;
        }

        .text-start {
            font-weight: bold;
        }

        .aturan {
            width: 90%;
        }

        button {
            background-color: #2d2d44;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #fa736b;
        }

        .navbar-brand {
            font-weight: bold;
            color: #cdc2ae;
        }

        .administrasi {
            width: 95%;
        }

        .form-peng {
            background-color: #ECE5C7;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-lg fixed-top" style="background-color: #68A7AD;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../home.php">S I L A D U M A</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../home.php">Home</a>
                    </li>

                </ul>
                <span class="navbar-profile">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <?php $username = $_SESSION['username'];
                                echo "$username"; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </span>
            </div>
        </div>
    </nav>

    <h1>Laporan Data Permohonan Vaksinasi Hewan</h1>

    <div class="container-fluid hasil">
        <div class="card-wrap mt-4">
            <div class="card-body">

                <div class="box box-primary">
                    <div class="box-header with- text-center">
                        <button type="button" class="btn btn-success" onclick="printReport()">
                            Cetak Laporan
                        </button>
                    </div>
                </div>
                <!-- Form Pencarian -->
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari berdasarkan nama pemohon atau jenis ternak..."
                            value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>
                <table class="table table-bordered table-striped">
                    <tr class="text-center">
                        <th>No.</th>
                        <th>Nama Pemohon</th>
                        <th>Jenis Ternak</th>
                        <th>Jumlah Ternak</th>
                        <th>Jenis Vaksin</th>
                        <th>Alamat Pemohon</th>
                        <th>Status</th>
                        <th>Tanggal Permohonan</th>
                        <th>Tanggal Selesai</th>
                        <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'petugas') { ?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>

                    <?php
                    $no = 1;
                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                    if ($_SESSION['level'] == "petugas" || $_SESSION['level'] == "admin") {
                        $query = "SELECT hasil_permohonan_vaksinasi_hewan.*, user.nama 
                                  FROM hasil_permohonan_vaksinasi_hewan
                                  JOIN user ON hasil_permohonan_vaksinasi_hewan.id_user = user.id_user";
                    } elseif ($_SESSION['level'] == "warga") {
                        $query = "SELECT hasil_permohonan_vaksinasi_hewan.*, user.nama 
                                  FROM hasil_permohonan_vaksinasi_hewan
                                  JOIN user ON hasil_permohonan_vaksinasi_hewan.id_user = user.id_user
                                  WHERE user.nama = '$_SESSION[nama]'";
                    }

                    // Jika ada pencarian
                    if (!empty($search)) {
                        // Jika query sudah memiliki WHERE, tambahkan AND
                        if (strpos($query, "WHERE") !== false) {
                            $query .= " AND (user.nama LIKE '%$search%' OR hasil_permohonan_vaksinasi_hewan.jenis_ternak LIKE '%$search%')";
                        } else {
                            $query .= " WHERE (user.nama LIKE '%$search%' OR hasil_permohonan_vaksinasi_hewan.jenis_ternak LIKE '%$search%')";
                        }
                    }

                    $result = mysqli_query($koneksi, $query);
if (!$result) {
    echo "Error: " . mysqli_error($koneksi) . "<br>"; // Tambahkan baris ini
}

                    while ($row = mysqli_fetch_array($result)) :
                        ?>

                        <tr>
                            <td>
                                <?= $no++ ?>
                            </td>
                            <td>
                                <?php echo $row['nama'] ?>
                            </td>
                            <td>
                                <?php echo $row['jenis_ternak'] ?>
                            </td>
                            <td>
                                <?php echo $row['jumlah_ternak'] ?>
                            </td>
                            <td>
                                <?php echo $row['jenis_vaksin'] ?>
                            </td>
                            <td>
                                <?php echo $row['alamat_pemohon'] ?>
                            </td>
                            <td>
                                <?php echo $row['status'] ?>
                            </td>
                            <td>
                                <?php echo $row['tgl_permohonan'] ?>
                            </td>
                            <td>
                                <?php echo $row['tgl_selesai'] ?>
                            </td>

                            <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'petugas') { ?>
                                <td class="text-center">
                                    <a href="ed_permohonan_vaksinasi.php?hal=edit&id=<?php echo $row['id_permohonan'] ?>"
                                        class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>

                                    <a href="hasil_permohonan_vaksinasi_hewan.php?hal=hapus&id=<?= $row['id_permohonan'] ?>"
                                        onclick="return confirm('Apakah yakin ingin menghapus data ini?')"
                                        class="btn btn-danger btn-sm" title="Hapus Pengajuan">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </a>

                                    <a href="hasil_permohonan_vaksinasi_hewan.php?hal=verifikasi&id=<?= $row['id_permohonan'] ?>"
                                        class="btn btn-info btn-sm" title="Verifikasi Pengajuan">
                                        <i class="fa-solid fa-check"></i> Verifikasi
                                    </a>

                                    <a href="hasil_permohonan_vaksinasi_hewan.php?hal=jadwalkan&id=<?= $row['id_permohonan'] ?>"
                                        class="btn btn-primary btn-sm" title="Jadwalkan Pengajuan">
                                        <i class="fa-solid fa-calendar-alt"></i> Jadwalkan
                                    </a>

                                    <a href="hasil_permohonan_vaksinasi_hewan.php?hal=selesai&id=<?= $row['id_permohonan'] ?>"
                                        class="btn btn-success btn-sm" title="Selesaikan Pengajuan">
                                        <i class="fa-solid fa-check-double"></i> Selesai
                                    </a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php endwhile ?>
                </table>
            </div>
        </div>
    </div>

    <script>
        function printReport() {
            var printButton = document.querySelector(".btn-success");
            printButton.style.display = "none";

            // Sembunyikan kolom aksi (kolom ke-12 dalam tabel)
            var elementsToHide = document.querySelectorAll('th:nth-child(11), td:nth-child(11)');
            elementsToHide.forEach(el => {
                el.style.display = 'none';
            });

            var tableContent = document.querySelector("table").outerHTML;

            var headerContent = `
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
            <img src="/SILADU-main/petugas/img/logo_dinas.jpg" alt="Logo Dinas"
                style="width: 80px; height: auto; margin-right: 20px;" />
            <div>
                <h3 style="text-transform: uppercase; margin: 0; text-align: center;">Pemerintah Kota Banjarmasin</h3>
                <h3 style="text-transform: uppercase; margin: 0;">Dinas Ketahanan Pangan, Pertanian dan Perikanan Kota
                    Banjarmasin</h3>
                <p style="font-size: 14px; margin: 0;">Alamat: Jl. Lkr. Dalam Utara, Komplek Screen House, Kel. Benua Anyar,
                    Kota Banjarmasin</p>

            </div>
        </div>
        <hr />
    `;

            // Tambahkan Tanda Tangan Kepala Dinas
            var footerContent = `
        <div style="text-align: right; margin-top: 50px;">
            <p>Banjarmasin,
                ${new Date().toLocaleDateString('id-ID')}
            </p>
            <p style="margin-bottom: 80px;">Kepala Dinas</p>
            <p style="margin-top: 5px; font-weight: bold; text-decoration: underline;">Nama Kepala Dinas</p>
            <p style="margin-top: -5px;">NIP: xxxxxxxxxx</p>
        </div>
    `;

            var printWindow = window.open('', '', 'height=800,width=1000');
            printWindow.document.write('<html><head><title>Cetak Hasil Laporan Permohonan Vaksinasi Hewan</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; margin: 40px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; margin: 20px 0; }');
            printWindow.document.write('th, td { padding: 10px; border : 1px solid #ddd; text-align: left; }');
            printWindow.document.write('th { background-color: #f2f2f2; text-align: center; }');
            printWindow.document.write('p, h3 { margin: 0; }');
            printWindow.document.write('hr { margin: 20px 0; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');

            printWindow.document.write(headerContent);
            printWindow.document.write(tableContent);
            printWindow.document.write(footerContent); // Tambahkan tanda tangan Kepala Dinas
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Cetak
            printWindow.print();

            // Refresh halaman setelah cetak
            setTimeout(function () {
                location.reload();
            }, 1000);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>
