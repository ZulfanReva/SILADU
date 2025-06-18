<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$kode = $_GET['id'] ?? '';
$nama = $_SESSION['nama'];
$jenis_izin = $_POST['jenis_izin'] ?? '';
$file_name = $_FILES['dokumen']['name'] ?? '';
$kontak = $_POST['kontak'] ?? '';
$tgl_pengajuan = date('Y-m-d'); // Set tanggal pengajuan ke hari ini
$status_pemohon = $_POST['status_pemohon'] ?? ''; // Set default status pemohon
$catatan_admin = $_POST['catatan_admin'] ?? '';

if (isset($_POST['simpan'])) {
    if (!empty($jenis_izin) && !empty($kontak)) {
        $ekstensi_boleh = array('pdf');
        $file_uploaded = !empty($_FILES['dokumen']['name']);
        $file_name = "";
        $direktori = "uploads/pengajuan_izin/";
    
        if ($file_uploaded) {
            $x = explode('.', $_FILES['dokumen']['name']);
            $ekstensi = strtolower(end($x));
            $file_name = time() . '_' . basename($_FILES['dokumen']['name']); // Nama unik
        }
    
        // Buat folder jika belum ada
        if (!file_exists($direktori)) {
            mkdir($direktori, 0777, true);
        }
    
        if (!$file_uploaded || in_array($ekstensi, $ekstensi_boleh)) {
            if ($_GET['hal'] == "edit") {
                // Ambil nama file lama sebelum diperbarui
                $query_old = "SELECT dokumen FROM pengajuan_izin WHERE id_pengajuan = '$kode'";
                $result_old = mysqli_query($koneksi, $query_old);
                $data_old = mysqli_fetch_assoc($result_old);
                $old_file = $data_old['dokumen'];
    
                // Gunakan file lama jika tidak ada file baru yang diunggah
                if (!$file_uploaded) {
                    $file_name = $old_file;
                }
    
                // Update data dengan file baru atau lama
                $query = "UPDATE pengajuan_izin SET
                            jenis_izin = '$jenis_izin',
                            dokumen = '$file_name',
                            kontak = '$kontak',
                            tgl_pengajuan = '$tgl_pengajuan',
                            status_pemohon = '$status_pemohon', 
                            catatan_admin = '$catatan_admin'
                          WHERE id_pengajuan = '$kode'";
                $edit = mysqli_query($koneksi, $query);
    
                if ($edit) {
                    if ($file_uploaded) {
                        // Hapus file lama jika ada file baru
                        if (!empty($old_file) && file_exists($direktori . $old_file)) {
                            unlink($direktori . $old_file);
                        }
                        // Pindahkan file baru ke folder
                        move_uploaded_file($_FILES['dokumen']['tmp_name'], $direktori . $file_name);
                    }
                    echo "<script>
                        alert('Berhasil Memperbarui Pengajuan!');
                        window.location.href = 'hasil_pengajuan_izin.php';
                    </script>";
                } else {
                    echo "<script>alert('Edit Data Gagal!'); history.back();</script>";
                }
            } else {
                $sql = "INSERT INTO pengajuan_izin (jenis_izin, dokumen, kontak, tgl_pengajuan, status_pemohon, catatan_admin)
                        VALUES (?, ?, ?, ?, ?, ?)";
    
                $stmt = mysqli_prepare($koneksi, $sql);
                mysqli_stmt_bind_param($stmt, "ssssss", $jenis_izin, $file_name, $kontak, $tgl_pengajuan, $status_pemohon, $catatan_admin);
                $insert_hasil = mysqli_stmt_execute($stmt);
    
                if ($insert_hasil) {
                    if ($file_uploaded) {
                        // Pindahkan file setelah data tersimpan
                        move_uploaded_file($_FILES['dokumen']['tmp_name'], $direktori . $file_name);
                    }
                    echo "<script>
                        alert('Berhasil Mengirim pengajuan!');
                        window.location.href = 'pengajuan_izin.php?status=sukses';
                    </script>";
                } else {
                    echo "<script>alert('Gagal menyimpan pengajuan!'); history.back();</script>";
                }
            }
        } else {
            echo "<script>alert('Ekstensi dokumen hanya diperbolehkan pdf!');</script>";
            echo "<script>history.back();</script>";
        }
    } else {
        echo "<script>alert('Ada Input yang Kosong!');</script>";
        echo "<script>history.back();</script>";
    }
} else {
    echo "<script>location('pengajuan_izin.php');</script>";
}


if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "edit") {
        $b = mysqli_query($koneksi, "SELECT * FROM pengajuan_izin WHERE id_pengajuan='$_GET[id]'");
        $data = mysqli_fetch_array($b);
        if ($data) {
            $jenis_izin = $data['jenis_izin'];
            $dokumen = $data['dokumen'];
            $kontak = $data['kontak'];
            $tgl_pengajuan = $data['tgl_pengajuan'];
            $status_pemohon = $data['status_pemohon'];
            $catatan_admin = $data['catatan_admin'];
        }
    } elseif ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM pengajuan_izin WHERE id_pengajuan='$_GET[id]'");
        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Sukses!');
                    location='pengajuan_izin.php';
                  </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pengajuan Izin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <style>
        h1 {
            margin-top: 80px;
            text-align: center;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .layanan:hover>.dropdown-menu {
            display: block;
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
    <!-- navbar -->
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
                                <?php echo $username; ?>
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

    <h1>Pengajuan Izin</h1>
    <div class="container-fluid administrasi">
        <div class="card-wrap mt-5">
            <div class="card administrasi-form">
                <div class="card-header form-peng">
                    <h2 class="card-title text-center">Form Pengajuan Izin</h2>
                </div>
                <div class="card-body">
                   
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label for="disabledTextInput" class="col-sm-3 col-form-label text-start">ID Pengajuan</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="id" value="<?= $data['id_pengajuan'] ?? ''; ?>" placeholder="Otomatis Oleh Sistem" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Nama Pemohon</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="username" value="<?= $nama; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Izin</label>
                            <div class="col-sm-9">
                                <select name="jenis_izin" class="form-control" id="jenis_izin">
                                    <option value="">Pilih Jenis Izin</option>
                                    <option value="Izin Usaha Budidaya Tanaman Pangan dan Hortikultura" <?php echo ($jenis_izin == 'Izin Usaha Budidaya Tanaman Pangan dan Hortikultura') ? 'selected' : ''; ?>>Izin Usaha Budidaya Tanaman Pangan dan Hortikultura</option>
                                    <option value="Izin Usaha Perkebunan" <?php echo ($jenis_izin == 'Izin Usaha Perkebunan') ? 'selected' : ''; ?>>Izin Usaha Perkebunan</option>
                                    <option value="Izin Usaha Peternakan dan Kesehatan Hewan" <?php echo ($jenis_izin == 'Izin Usaha Peternakan dan Kesehatan Hewan') ? 'selected' : ''; ?>>Izin Usaha Peternakan dan Kesehatan Hewan</option>
                                    <option value="Izin Usaha Perikanan" <?php echo ($jenis_izin == 'Izin Usaha Perikanan') ? 'selected' : ''; ?>>Izin Usaha Perikanan</option>
                                    <option value="Izin Distribusi dan Keamanan Pangan" <?php echo ($jenis_izin == 'Izin Distribusi dan Keamanan Pangan') ? 'selected' : ''; ?>>Izin Distribusi dan Keamanan Pangan</option>
                                    <option value="Izin Penggunaan Sarana dan Prasarana Pertanian dan Perikanan" <?php echo ($jenis_izin == 'Izin Penggunaan Sarana dan Prasarana Pertanian dan Perikanan') ? 'selected' : ''; ?>>Izin Penggunaan Sarana dan Prasarana Pertanian dan Perikanan</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Dokumen</label>
                            <div class="col-sm-9">
                                <?php if (!empty($dokumen)) : ?>
                                    <div class="mb-2">
                                        <span class="text-primary">File saat ini: <?= basename($dokumen); ?></span>
                                    </div>
                                <?php endif; ?>
                                <input class="form-control" type="file" name="dokumen" value="<?= $dokumen; ?>" accept="application/pdf">
                                <input type="hidden" name="dokumen_lama" value="<?= $dokumen; ?>">
                                <small class="text-danger">Format hanya PDF</small>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Kontak</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kontak" value="<?= $kontak; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Tgl. Pengajuan</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="tgl_pengajuan" value="<?= $tgl_pengajuan; ?>" disabled>
                            </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Status Pemohon</label>
                            <div class="col-sm-9">
                                <select name="status_pemohon" class="form-control">
                                    <option value="">Pilih Status</option>
                                    <option value="Disetujui" <?= (isset($status_pemohon) && $status_pemohon == 'Disetujui') ? 'selected' : ''; ?>>Disetujui</option>
                                    <option value="Ditolak" <?= (isset($status_pemohon) && $status_pemohon == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                    <option value="Diproses" <?= (isset($status_pemohon) && $status_pemohon == 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                                    <option value="Diajukan" <?= (isset($status_pemohon) && $status_pemohon == 'Diajukan') ? 'selected' : ''; ?>>Diajukan</option>
                                </select>

                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Catatan Admin</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="catatan_admin" placeholder="Tulis catatan admin"><?= $catatan_admin; ?></textarea>
                            </div>
                        </div>
                        <div class="button-align text-end">
                            <button type="submit" name="simpan" class="btn btn-success">SIMPAN</button>
                            <button type="reset" name="reset" class="btn btn-danger">RESET</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>