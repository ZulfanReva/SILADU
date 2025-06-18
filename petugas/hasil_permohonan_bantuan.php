<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$id_bantuan = $_POST['id_bantuan'];
$nama_pemohon = $_POST['nama_pemohon'];
$alamat = $_POST['alamat'] ?? '';
$kelurahan = $_POST['kelurahan'] ?? '';
$kecamatan = $_POST['kecamatan'] ?? '';
$no_telp = $_POST['no_telp'] ?? '';
$jenis_bantuan = $_POST['jenis_bantuan'] ?? '';
$deskripsi_bantuan = $_POST['deskripsi_bantuan'] ?? '';
$status_pemohon = 'Pending'; // Set default status pemohon
$tgl_pengajuan = date('Y-m-d'); // Set tanggal pengajuan ke hari ini
$tgl_persetujuan = ''; // Kosongkan tanggal persetujuan
$catatan_admin = ''; // Kosongkan catatan admin

if (isset($_POST['simpan'])) {
    if (!empty($id_pengajuan) && !empty($nama_pemohon) && !empty($jenis_izin) && !empty($dokumen) && !empty($kontak) && !empty($catatan)) {
        $sql = "INSERT INTO permohonan_bantuan ( nama_pemohon, alamat, kelurahan, kecamatan, no_telp, jenis_bantuan, deskripsi_bantuan, tgl_persetujuan, tgl_pengajuan, status_pemohon, catatan_admin) 
                VALUES ( '$nama_pemohon', '$alamat', '$kelurahan', $kecamatan, '$np_telp', '$jenis_bantuan', '$deskripsi_bantuan', '$tgl_persetujuan', '$tgl_pengajuan', '$status_pemohon', '$catatan_admin')";
        $a = $koneksi->query($sql);
        if ($a === true) {
            move_uploaded_file($_FILES['dokumen']['tmp_name'], $linkberkas);
            echo "<script>alert('Pengajuan Izin Berhasil Disimpan!');</script>";
            header("refresh:2;url=hasil_permohonan_bantuan.php");
        } else {
            echo "<script>alert('Gagal Menyimpan Pengajuan Izin!');</script>";
            header("refresh:2;url=hasil_permohonan_bantuan.php");
        }
    } else {
        echo "<script>alert('Ada Input yang Kosong!');</script>";
        echo "<script>history.back();</script>";
    }
}

if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM permohonan_bantuan WHERE id_bantuan='$_GET[id]'");
        if ($hapus) {
            echo "<script>alert('Hapus Data Sukses!'); location='hasil_permohonan_bantuan.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
        }
    } elseif ($_GET['hal'] == "setuju") {
        $update = mysqli_query($koneksi, "UPDATE permohonan_bantuan SET status_pemohon='Disetujui' WHERE id_bantuan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Pengajuan Disetujui!'); location='hasil_permohonan_bantuan.php';</script>";
        } else {
            echo "<script>alert('Gagal Menyetujui!');</script>";
        }
    } elseif ($_GET['hal'] == "tolak") {
        $update = mysqli_query($koneksi, "UPDATE permohonan_bantuan SET status_pemohon='ditolak' WHERE id_bantuan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Pengajuan Ditolak!'); location='hasil_permohonan_bantuan.php';</script>";
        } else {
            echo "<script>alert('Gagal Menolak!');</script>";
        }
    }
}

// Proses penyimpanan catatan jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_catatan'])) {
    $id_bantuan = $_POST['id_bantuan'];
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);

    // Update catatan di database
    $query = "UPDATE permohonan_bantuan SET catatan_admin='$catatan' WHERE id_bantuan='$id_bantuan'";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
            alert('Catatan berhasil disimpan!');
            window.location.href='hasil_permohonan_bantuan.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menyimpan catatan!');
            window.history.back();
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tgl_persetujuan'])) {
    $id_bantuan = $_POST['id_bantuan'];
    $tgl_persetujuan = $_POST['tgl_persetujuan'];


    // Update di tabel pengaduan_alat_perikanan
    $sql2 = "UPDATE permohonan_bantuan SET tgl_persetujuan=? WHERE id_bantuan=?";
    $stmt2 = $koneksi->prepare($sql2);
    $stmt2->bind_param("si", $tgl_persetujuan, $id_bantuan);

    // Eksekusi kedua query
    if ($stmt2->execute()) {
        echo "<script>alert('Tanggal selesai diperbarui di kedua tabel!'); window.location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Gagal memperbarui tanggal selesai!');</script>";
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pengajuan Izin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <style>
        .text-center {
            text-align: center;
        }

        .cetak-btn {
            margin-top: 20px;
            margin-bottom: 10px;
        }

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

        .navbar-brand {
            font-weight: bold;
            color: #cdc2ae;
        }

        @media print {

            .btn-success,
            .btn-danger,
            .btn-warning,
            th:nth-child(10),
            td:nth-child(10) {
                display: none;
            }

            body {
                font-size: 12px;
                color: black;
            }

            table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-lg fixed-top" style="background-color: #68A7AD;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../home.php">S I L A D U M A</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
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
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo "$username"; ?>
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

    <h1>Laporan Permohonan Bantuan</h1>
    <div class="box box-primary">
        <div class="box-header with-border text-center">
            <button type="button" class="btn btn-success cetak-btn" onclick="printReport()">
                Cetak Laporan
            </button>
        </div>
        <div class="container-fluid hasil">
            <div class="card-wrap mt-4">
                <div class="card-body">
                    <!-- Form Pencarian -->
                    <form method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama pemohon atau jenis bantuan..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped">
                        <tr class="text-center">
                            <th>No.</th>
                            <th scope="col">Nama Pemohon</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Kelurahan</th>
                            <th scope="col">Kecamatan</th>
                            <th scope="col">No. Telp</th>
                            <th scope="col">Jenis Bantuan</th>
                            <th scope="col">Deskripsi Bantuan</th>
                            <th scope="col">Status Pemohon</th>
                            <th scope="col">Tgl. Pengajuan</th>
                            <th scope="col">Tgl. Persetujuan</th>
                            <th scope="col">Catatan Admin</th>
                            <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'petugas') { ?>
                                <th>Aksi</th>
                            <?php } ?>
                        </tr>

                        <?php
                        $no = 1;
                        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                        if ($_SESSION['level'] == "petugas" || $_SESSION['level'] == "admin") {
                            $query = "SELECT permohonan_bantuan.*, user.nama 
                                      FROM permohonan_bantuan
                                      JOIN user ON permohonan_bantuan.id_user = user.id_user";
                        } elseif ($_SESSION['level'] == "warga") {
                            $query = "SELECT permohonan_bantuan.*, user.nama 
                                      FROM permohonan_bantuan
                                      JOIN user ON permohonan_bantuan.id_user = user.id_user
                                      WHERE user.nama = '$_SESSION[nama]'";
                        }
    
                        // Jika ada pencarian
                        if (!empty($search)) {
                            // Jika query sudah memiliki WHERE, tambahkan AND
                            if (strpos($query, "WHERE") !== false) {
                                $query .= " AND (user.nama LIKE '%$search%' OR permohonan_bantuan.jenis_bantuan LIKE '%$search%')";
                            } else {
                                $query .= " WHERE (user.nama LIKE '%$search%' OR permohonan_bantuan.jenis_bantuan LIKE '%$search%')";
                            }
                        }

                        $result = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($result)) :
                            $dokumenPath = "../pengguna/uploads/permohonan_bantuan/" . $row['dokumen'];
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?php echo $row['nama'] ?></td>
                                <td><?php echo $row['alamat'] ?></td>
                                <td><?php echo $row['kelurahan'] ?></td>
                                <td><?php echo $row['kecamatan'] ?></td>
                                <td><?php echo $row['no_telp'] ?></td>
                                <td><?php echo $row['jenis_bantuan'] ?></td>
                                <td><?php echo $row['deskripsi_bantuan'] ?></td>
                                <td><?= !empty($row['status_pemohon']) ? $row['status_pemohon'] : 'Pending'; ?></td>
                                <td><?php echo $row['tgl_pengajuan'] ?></td>
                                <td><?php echo $row['tgl_persetujuan'] ?></td>
                                <td><?php echo $row['catatan_admin'] ?></td>

                                <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'petugas') { ?>
                                    <td class="text-center">
                                        <a href="ed_permohonan_bantuan.php?hal=edit&id=<?php echo $row['id_bantuan'] ?>" class="btn btn-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>Edit</a>
                                            
                                        <a href="hasil_permohonan_bantuan.php?hal=hapus&id=<?= $row['id_bantuan'] ?>" onclick="return confirm('Apakah yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm" title="Hapus Pengajuan">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </a>

                                        <a href="hasil_permohonan_bantuan.php?hal=setuju&id=<?= $row['id_bantuan'] ?>" class="btn btn-success btn-sm" title="Setujui Pengajuan">
                                            <i class="fa-solid fa-check"></i> Setujui
                                        </a>

                                        <a href="hasil_permohonan_bantuan.php?hal=tolak&id=<?= $row['id_bantuan'] ?>" class="btn btn-warning btn-sm" title="Tolak Pengajuan">
                                            <i class="fa-solid fa-times"></i> Tolak
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
                // Sembunyikan elemen yang tidak perlu ditampilkan saat mencetak
                const elementsToHide = document.querySelectorAll('th:nth-child(12), td:nth-child(12), button, textarea');
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

                var tanggalInputs = document.querySelectorAll('input[name="tgl_persetujuan"]');
                tanggalInputs.forEach(function(input) {
                    var span = document.createElement("span");
                    span.textContent = input.value || 'Belum Disetujui'; // Ambil nilai dari input
                    span.classList.add("tgl-persetujuan-text"); // Tambahkan class agar bisa diubah kembali nanti
                    input.parentElement.replaceChild(span, input); // Ganti input dengan teks
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
                printWindow.document.write('<html><head><title>Cetak Laporan Permohonan Bantuan</title>');
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



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>