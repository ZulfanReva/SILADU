<?php
session_start();
include "../koneksi.php";
// if (isset($_SESSION["level"]) && $_SESSION["level"] == "masyarakat") {
//     header("Location:../forbidden.php"); // Redirect ke halaman error atau halaman lain yang sesuai
//     exit();
// }
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$pengId = $_POST['pengId'];
$nama_pemohon = $_POST['nama_pemohon'];
$jenis_alat = $_POST['jenis_alat'];
$waktu_kerusakan = $_POST['waktu_kerusakan'];
$penyebab_kerusakan = $_POST['penyebab_kerusakan'];
$permintaan = $_POST['permintaan'];
$petugas = $_SESSION['nama'];
$file_name = $_FILES['file']['name'];
$file_tmp = $_FILES['file']['tmp_name'];
$direktori = "hasil_pengaduan/";
$linkberkas = $direktori . $file_name;

if (isset($_POST['simpan'])) {
    if (!empty($pengId) && !empty($nama_pemohon) && !empty($jenis_alat) && !empty($waktu_kerusakan) && !empty($penyebab_kerusakan) && !empty($permintaan) && !empty($file_name)) {
        $sql = "INSERT INTO pengaduan_alat_ketahananpangan ( nama_pemohon, jenis_alat, waktu_kerusakan, penyebab_kerusakan, permintaan, path_gambar, stts_pengaduan, tgl_pengaduan, tgl_selesai, keterangan_petugas) VALUES ('$pengId', '$nama_pemohon', '$jenis_alat', '$waktu_kerusakan', '$penyebab_kerusakan', '$permintaan', '$file_name', 'Pending', NOW(), NULL, '$petugas')";
        $a = $koneksi->query($sql);
        if ($a === true) {
            move_uploaded_file($file_tmp, $linkberkas);
            echo "<script>alert('Berhasil Mengirim Hasil Pengaduan!');</script>";
            header("refresh:2;url=hasil_pengaduan_alat_pangan.php");
        } else {
            echo "<script>alert('Gagal Mengirim Hasil Pengaduan!');</script>";
            header("refresh:2;url=hasil_pengaduan_alat_pangan.php");
        }
    } else {
        echo "<script>alert('Ada Input yang Kosong!');</script>";
        echo "<script>history.back();</script>";
    }
} else {
    echo "<script>location('hasil_pengaduan_alat_pangan.php');</script>";
}

if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM pengaduan_alat_ketahananpangan WHERE id_pengaduan='$_GET[id]'");
        if ($hapus) {
            echo "<script>alert('Hapus Data Sukses!'); location='hasil_pengaduan_alat_pangan.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
    $id_pengaduan = $_POST['id_pengaduan'];

    $target_dir = "../petugas/uploads/hasil_pengaduan_alat_pangan/";

    // Pastikan folder ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = $_FILES['gambar']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_exts = array("jpg", "jpeg", "png", "gif");

    if (in_array($file_ext, $allowed_exts)) {
        // Buat nama file unik agar tidak bentrok
        $new_file_name = time() . '_' . $file_name;
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // Mulai transaksi database
            mysqli_begin_transaction($koneksi);

            try {
                // Update tabel hasil_pengaduan_alat_ikan


                // Update tabel pengaduan_alat_perikanan agar warga juga bisa melihat gambar
                $query2 = "UPDATE pengaduan_alat_ketahananpangan 
                           SET keterangan_petugas = ? 
                           WHERE id_pengaduan = ?";
                $stmt2 = $koneksi->prepare($query2);
                $stmt2->bind_param("si", $new_file_name, $id_pengaduan);
                $stmt2->execute();

                // Commit transaksi jika kedua update berhasil
                mysqli_commit($koneksi);

                echo "<script>alert('Gambar berhasil diupload dan disimpan!'); window.location.href='hasil_pengaduan_alat_pangan.php';</script>";
            } catch (Exception $e) {
                // Jika ada kesalahan, rollback transaksi
                mysqli_rollback($koneksi);
                echo "<script>alert('Gagal menyimpan ke database!');</script>";
            }
        } else {
            echo "<script>alert('Gagal mengupload gambar!');</script>";
        }
    } else {
        echo "<script>alert('Format gambar tidak didukung!');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_pengaduan = $_POST['id_pengaduan'];
    $stts_pengaduan = $_POST['stts_pengaduan'] ?? null;
    $tgl_selesai = $_POST['tgl_selesai'] ?? null;

    // Ambil status dan tanggal selesai jika tidak ada input dari pengguna
    $query = "SELECT stts_pengaduan, tgl_selesai FROM pengaduan_alat_ketahananpangan WHERE id_pengaduan = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_pengaduan);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "<script>alert('Data tidak ditemukan!'); window.history.back();</script>";
        exit;
    }

    $stts_pengaduan = $stts_pengaduan ?? $row['stts_pengaduan'];
    $tgl_selesai = $tgl_selesai ?? $row['tgl_selesai'];

    $stmt->close();

    // Update data
    $sql = "UPDATE pengaduan_alat_ketahananpangan SET stts_pengaduan = ?, tgl_selesai = ? WHERE id_pengaduan = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssi", $stts_pengaduan, $tgl_selesai, $id_pengaduan);

    if ($stmt->execute()) {
        $stmt->close();
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='hasil_pengaduan_alat_pangan.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }

    $stmt->close();
    $koneksi->close();
}
if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM pengaduan_alat_ketahananpangan WHERE id_pengaduan='$_GET[id]'");
        if ($hapus) {
            echo "<script>alert('Hapus Data Sukses!'); location='hasil_pengaduan_alat_pangan.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
        }
    } elseif ($_GET['hal'] == "setuju") {
        $update = mysqli_query($koneksi, "UPDATE pengaduan_alat_ketahananpangan SET stts_pengaduan='Disetujui' WHERE id_pengaduan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Pengajuan Disetujui!'); location='hasil_pengaduan_alat_pangan.php';</script>";
        } else {
            echo "<script>alert('Gagal Menyetujui!');</script>";
        }
    } elseif ($_GET['hal'] == "tolak") {
        $update = mysqli_query($koneksi, "UPDATE pengaduan_alat_ketahananpangan SET stts_pengaduan='ditolak' WHERE id_pengaduan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Pengajuan Ditolak!'); location='hasil_pengaduan_alat_pangan.php';</script>";
        } else {
            echo "<script>alert('Gagal Menolak!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASIL LAYANAN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

    <h1>Laporan Data Pengaduan Ketahanan Pangan</h1>

    <div class="container-fluid hasil">
        <div class="card-wrap mt-4">
            <div class="card-body">
            <div class="box box-primary">
                    <div class="box-header with- text-center" >
                        <button type="button" class="btn btn-success" onclick="printReport()">
                            Cetak Laporan
                        </button>
                    </div>
                </div>
                <!-- Form Pencarian -->
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama pemohon atau jenis alat..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>
                <table class="table table-bordered table-striped">
                    <tr class="text-center">
                        <th>No.</th>
                        <th>Nama Pemohon</th>
                        <th>Jenis Alat</th>
                        <th>Waktu Kerusakan</th>
                        <th>Penyebab Kerusakan</th>
                        <th>Permintaan</th>
                        <th>File</th>
                        <th>Status Pengaduan</th>
                        <th>Tanggal Pengaduan</th>
                        <th>Tanggal Selesai</th>
                        <th>Keterangan Petugas</th>
                        <th>Catatan Admin</th>
                        <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'petugas') { ?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>

                    <?php
                    $no = 1;
                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                    if ($_SESSION['level'] == "petugas" || $_SESSION['level'] == "admin") {
                        $query = "SELECT pengaduan_alat_ketahananpangan.*, user.nama 
                                  FROM pengaduan_alat_ketahananpangan
                                  JOIN user ON pengaduan_alat_ketahananpangan.id_user = user.id_user";
                    } elseif ($_SESSION['level'] == "warga") {
                        $query = "SELECT pengaduan_alat_ketahananpangan.*, user.nama 
                                  FROM pengaduan_alat_ketahananpangan
                                  JOIN user ON pengaduan_alat_ketahananpangan.id_user = user.id_user
                                  WHERE user.nama = '$_SESSION[nama]'";
                    }

                    // Jika ada pencarian
                    if (!empty($search)) {
                        // Jika query sudah memiliki WHERE, tambahkan AND
                        if (strpos($query, "WHERE") !== false) {
                            $query .= " AND (user.nama LIKE '%$search%' OR pengaduan_alat_ketahananpangan.jenis_alat LIKE '%$search%')";
                        } else {
                            $query .= " WHERE (user.nama LIKE '%$search%' OR pengaduan_alat_ketahananpangan.jenis_alat LIKE '%$search%')";
                        }
                    }

                    $result = mysqli_query($koneksi, $query);

                    while ($row = mysqli_fetch_array($result)) :
                        $penggunaImagePath = "../petugas/uploads/pengaduan_alat_ketahananpangan/" . $row['path_gambar'];

                        $petugasImagePath = "../petugas/uploads/pengaduan_alat_ketahananpangan/" . $row['keterangan_petugas'];
                    ?>

                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?php echo $row['nama'] ?></td>
                            <td><?php echo $row['jenis_alat'] ?></td>
                            <td><?php echo $row['waktu_kerusakan'] ?></td>
                            <td><?php echo $row['penyebab_kerusakan'] ?></td>
                            <td><?php echo $row['permintaan'] ?></td>
                            <td>
                                <?php if (!empty($row['path_gambar']) && file_exists($penggunaImagePath)) : ?>
                                    <a href="<?php echo $penggunaImagePath; ?>" target="_blank">
                                        <img src="<?php echo $penggunaImagePath; ?>" alt="Gambar Pengaduan" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc; border-radius: 5px;">
                                    </a>
                                    <br>
                                    <small class="download-link"><a href="<?php echo $penggunaImagePath; ?>" download>Download</a></small>
                                <?php else : ?>
                                    <span class="text-muted">Belum ada gambar</span>
                                <?php endif; ?>
                            </td>

                            <td><?php echo $row['stts_pengaduan'] ?></td>
                            <td><?php echo $row['tgl_pengaduan'] ?></td>
                            <td><?php echo $row['tgl_selesai'] ?></td>

                            <td>
                                <?php if (!empty($row['keterangan_petugas']) && file_exists($petugasImagePath)) : ?>
                                    <a href="<?php echo $petugasImagePath; ?>" target="_blank">
                                        <img src="<?php echo $petugasImagePath; ?>" alt="Gambar Pengaduan" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc; border-radius: 5px;">
                                    </a>
                                    <br>
                                    <small class="download-link"><a href="<?php echo $petugasImagePath; ?>" download>Download</a></small>
                                <?php else : ?>
                                    <span class="text-muted">Belum ada gambar</span>
                                <?php endif; ?>

                            </td>
                            <td><?php echo $row['catatan_admin'] ?></td>

                            <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'petugas') { ?>
                                <td class="text-center">
                                    <a href="ed_pengaduan_alat_pangan.php?hal=edit&id=<?php echo $row['id_pengaduan'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>

                                    <a href="hasil_pengaduan_alat_pangan.php?hal=hapus&id=<?= $row['id_pengaduan'] ?>" onclick="return confirm('Apakah yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm" title="Hapus Pengajuan">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </a>

                                    <a href="hasil_pengaduan_alat_pangan.php?hal=setuju&id=<?= $row['id_pengaduan'] ?>" class="btn btn-success btn-sm" title="Setujui Pengajuan">
                                        <i class="fa-solid fa-check"></i> Setujui
                                    </a>

                                    <a href="hasil_pengaduan_alat_pangan.php?hal=tolak&id=<?= $row['id_pengaduan'] ?>" class="btn btn-warning btn-sm" title="Tolak Pengajuan">
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
    var printButton = document.querySelector(".btn-success");
    printButton.style.display = "none";

    // Sembunyikan kolom aksi (kolom ke-12 dalam tabel)
    var elementsToHide = document.querySelectorAll('th:nth-child(13), td:nth-child(13)');
    elementsToHide.forEach(el => {
        el.style.display = 'none';
    });

    // Ubah "Tanggal Selesai" menjadi teks biasa
    var tanggalInputs = document.querySelectorAll('input[name="tgl_selesai"]');
    tanggalInputs.forEach(function(input) {
        var span = document.createElement("span");
        span.textContent = input.value || 'Belum selesai'; // Ambil nilai dari input
        span.classList.add("tgl-selesai-text"); // Tambahkan class agar bisa diubah kembali nanti
        input.parentElement.replaceChild(span, input); // Ganti input dengan teks
    });

        // Sembunyikan link "Download"
    var downloadLinks = document.querySelectorAll('.download-link');
    downloadLinks.forEach(el => {
        el.style.display = 'none';
    });

    // Tampilkan hanya gambar di "Keterangan Petugas"
    var keteranganPetugasFields = document.querySelectorAll('td[name="keterangan_petugas"]');
    keteranganPetugasFields.forEach(function(td) {
        var img = td.querySelector("img");
        td.innerHTML = ''; // Kosongkan isi
        if (img) {
            td.appendChild(img); // Tambahkan gambar kembali
        }
    });

    // Ubah dropdown "Status Pengaduan" menjadi teks biasa
    var statusDropdowns = document.querySelectorAll('td select[name="stts_pengaduan"]');
    statusDropdowns.forEach(function(select) {
        var span = document.createElement("span");
        span.textContent = select.options[select.selectedIndex].text; // Ambil teks dari option yang dipilih
        select.parentElement.replaceChild(span, select);
    });

    // Ambil konten tabel setelah perubahan
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

    var printWindow = window.open('', '', 'height=800,width=1000');
    printWindow.document.write('<html><head><title>Cetak Hasil Laporan Pengaduan Alat Pangan</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 40px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin: 20px 0; }');
    printWindow.document.write('th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }');
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
    setTimeout(function() {
        location.reload();
    }, 1000);
}
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>