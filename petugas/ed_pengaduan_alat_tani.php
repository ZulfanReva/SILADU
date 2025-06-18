<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$kode = $_GET['id'];
$nama = $_SESSION['nama'];
$jenis_alat = $_POST['jenis_alat'] ?? '';
$waktu_kerusakan = $_POST['waktu_kerusakan'] ?? '';
$penyebab_kerusakan = $_POST['penyebab_kerusakan'] ?? '';
$permintaan = $_POST['permintaan'] ?? '';
$tgl_selesai = $_POST['tgl_selesai'] ?? null; 
$file_name = $_FILES['path_gambar']['name'] ?? '';
$catatan_admin = $_POST['catatan_admin'] ?? '';
$file_name_petugas = $_FILES['path_gambar']['name'] ?? '';

if (isset($_POST['simpan'])) {
    if (!empty($jenis_alat) && !empty($waktu_kerusakan) && !empty($penyebab_kerusakan) && !empty($permintaan) && !empty($tgl_selesai)) {
        $ekstensi_boleh = array('jpg', 'jpeg', 'png', 'gif'); // Ekstensi yang diizinkan
        $direktori = "../petugas/uploads/pengaduan_alat_pertanian/";

        if (!file_exists($direktori)) {
            mkdir($direktori, 0777, true);
        }

        $gambar_baru = !empty($_POST['gambar_lama']) ? $_POST['gambar_lama'] : "default.png";
        $gambar_petugas_baru = !empty($_POST['gambar_petugas_lama']) ? $_POST['gambar_petugas_lama'] : "default.png";

        // Jika ada file baru yang diunggah
        if (!empty($_FILES['path_gambar']['name'])) {
            $x = explode('.', $_FILES['path_gambar']['name']);
            $ekstensi = strtolower(end($x));

            if (!in_array($ekstensi, $ekstensi_boleh)) {
                echo "<script>alert('Format file tidak didukung! Gunakan jpg, jpeg, png, atau gif.'); history.back();</script>";
                exit;
            }

            $file_name = time() . '_' . basename($_FILES['path_gambar']['name']); // Pastikan hanya nama file
            $gambar_baru = $file_name; // Simpan hanya nama file di database

            if (!move_uploaded_file($_FILES['path_gambar']['tmp_name'], $direktori . $file_name)) {
                echo "<script>alert('Gagal mengunggah gambar.'); history.back();</script>";
                exit;
            }
        }

        if (!empty($_FILES['keterangan_petugas']['name'])) {
            $y = explode('.', $_FILES['keterangan_petugas']['name']);
            $ekstensi_petugas = strtolower(end($y));

            if (!in_array($ekstensi_petugas, $ekstensi_boleh)) {
                echo "<script>alert('Format file keterangan petugas tidak didukung! Gunakan jpg, jpeg, png, atau gif.'); history.back();</script>";
                exit;
            }

            $file_name_petugas = time() . '_petugas_' . basename($_FILES['keterangan_petugas']['name']);
            $gambar_petugas_baru = $file_name_petugas;

            if (!move_uploaded_file($_FILES['keterangan_petugas']['tmp_name'], $direktori . $file_name_petugas)) {
                echo "<script>alert('Gagal mengunggah gambar keterangan petugas.'); history.back();</script>";
                exit;
            }
        }

        if ($_GET['hal'] == "edit") {
            $query = "UPDATE pengaduan_alat_pertanian SET
                    jenis_alat = '$jenis_alat',
                    waktu_kerusakan = '$waktu_kerusakan',
                    penyebab_kerusakan = '$penyebab_kerusakan',
                    permintaan = '$permintaan',
                    path_gambar = '$gambar_baru',
                    tgl_selesai = '$tgl_selesai',
                    catatan_admin = '$catatan_admin',
                    keterangan_petugas = '$gambar_petugas_baru',  
                    tgl_pengaduan = NOW()
                    WHERE id_pengaduan = '$kode'";

            $edit = mysqli_query($koneksi, $query);

            if ($edit) {
                echo "<script>
                    alert('Berhasil Memperbarui pengaduan!');
                    window.location.href = 'hasil_pengaduan_alat_tani.php';
                </script>";
            } else {
                echo "<script>alert('Edit Data Gagal!'); history.back();</script>";
            }
        } else {
            $sql = "INSERT INTO pengaduan_alat_pertanian ( jenis_alat, waktu_kerusakan, penyebab_kerusakan, permintaan, path_gambar, tgl_selesai, catatan_admin, keterangan_petugas, tgl_pengaduan)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssss", $jenis_alat, $waktu_kerusakan, $penyebab_kerusakan, $permintaan, $tgl_selesai, $catatan_admin, $gambar_baru, $gambar_petugas_baru);
            $insert_hasil = mysqli_stmt_execute($stmt);

            if ($insert_hasil) {
                echo "<script>
                    alert('Berhasil Mengirim pengaduan!');
                    window.location.href = 'pengaduan_alat_pertanian.php?status=sukses';
                </script>";
            } else {
                echo "<script>alert('Gagal menyimpan pengaduan!'); history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Ada Input yang Kosong!'); history.back();</script>";
    }
}

if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "edit") {
        $b = mysqli_query($koneksi, "SELECT * FROM pengaduan_alat_pertanian WHERE id_pengaduan='$_GET[id]'");
        $data = mysqli_fetch_array($b);
        if ($data) {
            $jenis_alat = $data['jenis_alat'];
            $waktu_kerusakan = $data['waktu_kerusakan'];
            $penyebab_kerusakan = $data['penyebab_kerusakan'];
            $permintaan = $data['permintaan'];
            $path_gambar = $data['path_gambar'];
            $tgl_selesai = $data['tgl_selesai'];
            $catatan_admin = $data['catatan_admin'];
            $keterangan_petugas = $data['keterangan_petugas'];
        }
    } elseif ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM pengaduan_alat_pertanian WHERE id_pengaduan='$_GET[id]'");
        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Sukses!');
                    location='pengaduan_alat_pertanian.php';
                  </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pengaduan Alat Pertanian</title>
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

    <h1>Pengaduan Alat Pertanian</h1>
    <div class="container-fluid administrasi">
        <div class="card-wrap mt-5">
            <div class="card administrasi-form">
                <div class="card-header form-peng">
                    <h2 class="card-title text-center">Form  Pengaduan Alat Pertanian</h2>
                </div>
                <div class="card-body">
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label for="disabledTextInput" class="col-sm-3 col-form-label text-start">ID Pengaduan</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="id" value="<?= $data['id_pengaduan'] ?? ''; ?>" placeholder="Otomatis Oleh Sistem" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Nama Pemohon</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="username" value="<?= $data['nama_pemohon'] ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Alat</label>
                            <div class="col-sm-9">
                                <select name="jenis_alat" class="form-control" id="jenis_alat">
                                    <option value="">Pilih Jenis Alat</option>
                                    <optgroup label="1. Alat Pengolahan Tanah">
                                        <option value="Cangkul" <?= ($jenis_alat == "Cangkul") ? 'selected' : '' ?>>Cangkul → Alat manual paling umum untuk menggemburkan tanah.</option>
                                        <option value="Bajak Singkal" <?= ($jenis_alat == "Bajak Singkal") ? 'selected' : '' ?>>Bajak Singkal → Digunakan bersama sapi atau traktor untuk membalik tanah.</option>
                                        <option value="Rotavator" <?= ($jenis_alat == "Rotavator") ? 'selected' : '' ?>>Rotavator → Mesin untuk menggemburkan tanah setelah dibajak.</option>
                                    </optgroup>
                                    <optgroup label="2. Alat Penanaman">
                                        <option value="Tugal" <?= ($jenis_alat == "Tugal") ? 'selected' : '' ?>>Tugal → Alat manual untuk membuat lubang tanam (misalnya untuk jagung dan kedelai).</option>
                                        <option value="Mesin Penanam Padi" <?= ($jenis_alat == "Mesin Penanam Padi") ? 'selected' : '' ?>>Mesin Penanam Padi (Rice Transplanter) → Untuk menanam padi secara cepat dan merata.</option>
                                        <option value="Alat Semai Bibit" <?= ($jenis_alat == "Alat Semai Bibit") ? 'selected' : '' ?>>Alat Semai Bibit → Untuk menanam bibit dalam tray sebelum dipindahkan ke lahan.</option>
                                    </optgroup>
                                    <optgroup label="3. Alat Pemeliharaan Tanaman">
                                        <option value="Sprayer" <?= ($jenis_alat == "Sprayer") ? 'selected' : '' ?>>Sprayer (Semprotan) → Untuk menyemprot pestisida atau pupuk cair.</option>
                                        <option value="Hand Sprayer" <?= ($jenis_alat == "Hand Sprayer") ? 'selected' : '' ?>>Hand Sprayer → Semprotan manual untuk pertanian skala kecil.</option>
                                        <option value="Power Sprayer" <?= ($jenis_alat == "Power Sprayer") ? 'selected' : '' ?>>Power Sprayer → Mesin semprot bertenaga untuk skala lebih besar.</option>
                                        <option value="Pompa Air" <?= ($jenis_alat == "Pompa Air") ? 'selected' : '' ?>>Pompa Air → Untuk mengalirkan air ke sawah atau ladang.</option>
                                        <option value="Mulsa Plastik" <?= ($jenis_alat == "Mulsa Plastik") ? 'selected' : '' ?>>Mulsa Plastik → Digunakan untuk menutup tanah agar mengurangi penguapan air dan pertumbuhan gulma.</option>
                                    </optgroup>
                                    <optgroup label="4. Alat Panen dan Pascapanen">
                                        <option value="Sabit dan Arit" <?= ($jenis_alat == "Sabit dan Arit") ? 'selected' : '' ?>>Sabit dan Arit → Alat manual untuk memanen padi dan rumput.</option>
                                        <option value="Combine Harvester" <?= ($jenis_alat == "Combine Harvester") ? 'selected' : '' ?>>Combine Harvester → Mesin panen padi yang dapat sekaligus merontokkan gabah.</option>
                                        <option value="Alat Perontok Padi" <?= ($jenis_alat == "Alat Perontok Padi") ? 'selected' : '' ?>>Alat Perontok Padi (Thresher) → Untuk memisahkan padi dari jeraminya.</option>
                                        <option value="Alat Pengering Gabah" <?= ($jenis_alat == "Alat Pengering Gabah") ? 'selected' : '' ?>>Alat Pengering Gabah → Untuk mengeringkan gabah sebelum digiling.</option>
                                    </optgroup>
                                </select>

                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Waktu Kerusakan</label>
                            <div class="col-sm-9">
                            <input type="date" class="form-control" name="waktu_kerusakan" 
                            value="<?= !empty($waktu_kerusakan) ? date('Y-m-d', strtotime($waktu_kerusakan)) : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Penyebab Kerusakan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="penyebab_kerusakan" placeholder="Tulis penyebab kerusakan"><?= $penyebab_kerusakan; ?></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Permintaan</label>
                            <div class="col-sm-9">
                                <select name="permintaan" class="form-control">
                                    <option value="Perbaikan" <?= (isset($permintaan) && $permintaan == 'Perbaikan') ? 'selected' : ''; ?>>Perbaikan</option>
                                    <option value="Ganti Baru" <?= (isset($permintaan) && $permintaan == 'Ganti Baru') ? 'selected' : ''; ?>>Ganti Baru</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="formFileMultiple" class="col-sm-3 col-form-label text-start">Path Gambar</label>
                            <div class="col-sm-9">
                                <!-- Menampilkan nama file jika sudah ada -->
                                <?php if (!empty($path_gambar)) : ?>
                                    <div class="mb-2">
                                        <span class="text-primary">File saat ini: <?= basename($path_gambar); ?></span>
                                    </div>
                                <?php endif; ?>

                                <!-- Input file untuk upload gambar baru -->
                                <input class="form-control" type="file" name="path_gambar" id="formFileMultiple" accept="image/*">

                                <!-- Input hidden untuk menyimpan nama file lama -->
                                <input type="hidden" name="gambar_lama" value="<?= $path_gambar; ?>">

                                <label class="text-danger" style="font-size:smaller">Pastikan sesuai dengan format yang diminta</label>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Tgl. selesai</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="tgl_selesai" value="<?= $tgl_selesai; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="formFileMultiple" class="col-sm-3 col-form-label text-start">Keterangan Petugas</label>
                            <div class="col-sm-9">
                                <!-- Menampilkan nama file jika sudah ada -->
                                <?php if (!empty($keterangan_petugas)) : ?>
                                    <div class="mb-2">
                                        <span class="text-primary">File saat ini: <?= basename($keterangan_petugas); ?></span>
                                    </div>
                                <?php endif; ?>

                                <!-- Input file untuk upload gambar baru -->
                                <input class="form-control" type="file" name="keterangan_petugas" id="formFileMultiple" accept="image/*">

                                <!-- Input hidden untuk menyimpan nama file lama -->
                                <input type="hidden" name="gambar_petugas_lama" value="<?= $keterangan_petugas; ?>">

                                <label class="text-danger" style="font-size:smaller">Pastikan sesuai dengan format yang diminta</label>
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