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
$file_name = $_FILES['path_gambar']['name'] ?? '';

if (isset($_POST['simpan'])) {
    if (!empty($jenis_alat) && !empty($waktu_kerusakan) && !empty($penyebab_kerusakan) && !empty($permintaan)) {
        $ekstensi_boleh = array('jpg', 'jpeg', 'png', 'gif'); // Ekstensi yang diizinkan
        $direktori = "../petugas/uploads/pengaduan_alat_perikanan/";

        if (!file_exists($direktori)) {
            mkdir($direktori, 0777, true);
        }

        $gambar_baru = !empty($_POST['gambar_lama']) ? $_POST['gambar_lama'] : "default.png"; // Default jika kosong

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

        if ($_GET['hal'] == "edit") {
            $query = "UPDATE pengaduan_alat_perikanan SET
                    jenis_alat = '$jenis_alat',
                    waktu_kerusakan = '$waktu_kerusakan',
                    penyebab_kerusakan = '$penyebab_kerusakan',
                    permintaan = '$permintaan',
                    path_gambar = '$gambar_baru', 
                    tgl_pengaduan = NOW()
                    WHERE id_pengaduan = '$kode'";

            $edit = mysqli_query($koneksi, $query);

            if ($edit) {
                echo "<script>
                    alert('Berhasil Memperbarui pengaduan!');
                    window.location.href = 'pengaduan_alat_perikanan.php';
                </script>";
            } else {
                echo "<script>alert('Edit Data Gagal!'); history.back();</script>";
            }
        } else {
            $query = "SELECT id_user FROM user WHERE username = '$username'";
            $result = $koneksi->query($query);
            $row = $result->fetch_assoc();
            $id_user = $row['id_user'];
            $sql = "INSERT INTO pengaduan_alat_perikanan (id_user, jenis_alat, waktu_kerusakan, penyebab_kerusakan, permintaan, path_gambar, tgl_pengaduan)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $id_user, $jenis_alat, $waktu_kerusakan, $penyebab_kerusakan, $permintaan, $gambar_baru);
            $insert_hasil = mysqli_stmt_execute($stmt);
            
            if ($insert_hasil) {
                echo "<script>
                    alert('Berhasil Mengirim pengaduan!');
                    window.location.href = 'pengaduan_alat_perikanan.php?status=sukses';
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
        $b = mysqli_query($koneksi, "SELECT * FROM pengaduan_alat_perikanan WHERE id_pengaduan='$_GET[id]'");
        $data = mysqli_fetch_array($b);
        if ($data) {
            $jenis_alat = $data['jenis_alat'];
            $waktu_kerusakan = $data['waktu_kerusakan'];
            $penyebab_kerusakan = $data['penyebab_kerusakan'];
            $permintaan = $data['permintaan'];
            $path_gambar = $data['path_gambar'];
        }
    } elseif ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM pengaduan_alat_perikanan WHERE id_pengaduan='$_GET[id]'");
        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Sukses!');
                    location='pengaduan_alat_perikanan.php';
                  </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pengaduan Alat Perikanan</title>
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

    <h1> Pengaduan Alat Perikanan</h1>
    <div class="container-fluid administrasi">
        <div class="card-wrap mt-5">
            <div class="card administrasi-form">
                <div class="card-header form-peng">
                    <h2 class="card-title text-center">Form  Pengaduan Alat Perikanan</h2>
                </div>
                <div class="card-body">
                   
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label for="disabledTextInput" class="col-sm-3 col-form-label text-start">ID Pengaduan</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="id" value="<?= $data['id_pengaduan'] ?>" placeholder="Otomatis Oleh Sistem" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Nama Pemohon</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="username" value="<?= $nama ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Alat</label>
                            <div class="col-sm-9">
                                <select name="jenis_alat" class="form-control" id="jenis_alat">
                                    <option value="">Pilih Jenis Alat</option>
                                    <optgroup label="1. Alat Penangkapan Ikan (Paling Sering Digunakan)">
                                        <option value="Jaring Gill Net" <?= ($jenis_alat == 'Jaring Gill Net') ? 'selected' : ''; ?>>Jaring Gill Net → Jaring insang yang banyak digunakan nelayan tradisional.</option>
                                        <option value="Jaring Pukat (Trawl)" <?= ($jenis_alat == 'Jaring Pukat (Trawl)') ? 'selected' : ''; ?>>Jaring Pukat (Trawl) → Digunakan untuk menangkap ikan di dasar laut.</option>
                                        <option value="Pancing Rawai (Longline)" <?= ($jenis_alat == 'Pancing Rawai (Longline)') ? 'selected' : ''; ?>>Pancing Rawai (Longline) → Pancing dengan banyak mata kail untuk menangkap ikan besar.</option>
                                        <option value="Bubu (Fish Trap)" <?= ($jenis_alat == 'Bubu (Fish Trap)') ? 'selected' : ''; ?>>Bubu (Fish Trap) → Alat perangkap ikan dan kepiting yang sering digunakan di sungai dan laut.</option>
                                    </optgroup>

                                    <optgroup label="2. Alat Budidaya Perikanan (Paling Umum)">
                                        <option value="Keramba Jaring Apung (KJA)" <?= ($jenis_alat == 'Keramba Jaring Apung (KJA)') ? 'selected' : ''; ?>>Keramba Jaring Apung (KJA) → Banyak digunakan untuk budidaya ikan nila, lele, dan patin.</option>
                                        <option value="Kolam Terpal" <?= ($jenis_alat == 'Kolam Terpal') ? 'selected' : ''; ?>>Kolam Terpal → Alternatif murah untuk budidaya ikan air tawar.</option>
                                        <option value="Kincir Air" <?= ($jenis_alat == 'Kincir Air') ? 'selected' : ''; ?>>Kincir Air → Digunakan di tambak untuk meningkatkan kadar oksigen dalam air.</option>
                                        <option value="Aerator Blower" <?= ($jenis_alat == 'Aerator Blower') ? 'selected' : ''; ?>>Aerator Blower → Menjaga suplai oksigen di kolam budidaya intensif.</option>
                                        <option value="pH Meter dan DO Meter" <?= ($jenis_alat == 'pH Meter dan DO Meter') ? 'selected' : ''; ?>>pH Meter dan DO Meter → Untuk mengukur tingkat keasaman air dan kadar oksigen terlarut.</option>
                                    </optgroup>

                                    <optgroup label="3. Alat Pengolahan Hasil Perikanan (Paling Banyak Dipakai)">
                                        <option value="Oven Pengering Ikan" <?= ($jenis_alat == 'Oven Pengering Ikan') ? 'selected' : ''; ?>>Oven Pengering Ikan → Untuk membuat ikan asin atau ikan kering.</option>
                                        <option value="Alat Pengasapan Ikan" <?= ($jenis_alat == 'Alat Pengasapan Ikan') ? 'selected' : ''; ?>>Alat Pengasapan Ikan → Banyak digunakan untuk produksi ikan asap.</option>
                                        <option value="Mesin Vacuum Sealer" <?= ($jenis_alat == 'Mesin Vacuum Sealer') ? 'selected' : ''; ?>>Mesin Vacuum Sealer → Untuk mengemas ikan agar lebih tahan lama.</option>
                                        <option value="Cold Storage (Pendingin Ikan)" <?= ($jenis_alat == 'Cold Storage (Pendingin Ikan)') ? 'selected' : ''; ?>>Cold Storage (Pendingin Ikan) → Untuk menyimpan ikan segar sebelum dipasarkan.</option>
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
                                <textarea class="form-control" name="penyebab_kerusakan" placeholder="Tulis penyebab kerusakan"><?= $penyebab_kerusakan ?></textarea>
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


                        <div class="button-align text-end">
                            <button type="submit" name="simpan" class="btn btn-success">SIMPAN</button>
                            <button type="reset" name="reset" class="btn btn-danger">RESET</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-wrap mt-4">
            <h3 class="card-title text-center">Daftar Pengaduan Alat Perikanan</h3>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>No.</th>
                            <th>ID Pengaduan</th>
                            <th>Nama Pemohon</th>
                            <th>Jenis Alat</th>
                            <th>Waktu Kerusakan</th>
                            <th>Penyebab Kerusakan</th>
                            <th>Permintaan</th>
                            <th>Gambar</th>
                            <th>Status Pengaduan</th>
                            <th>Tanggal Pengaduan</th>
                            <th>Tanggal Selesai</th>
                            <th>Keterangan Petugas</th>
                            <th>Catatan Admin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($_SESSION['level'] == "petugas" || $_SESSION['level'] == "admin") {
                            $query = "SELECT * FROM pengaduan_alat_perikanan";
                        } elseif ($_SESSION['level'] == "warga") {
                            $query = "SELECT * FROM pengaduan_alat_perikanan";
                        }

                        $query = "SELECT pengaduan_alat_perikanan.*, user.nama
                        FROM pengaduan_alat_perikanan
                        JOIN user ON pengaduan_alat_perikanan.id_user = user.id_user";
                        $result = mysqli_query($koneksi, $query);

                        while ($row = mysqli_fetch_array($result)) :
                            $imagePath = "../petugas/uploads/pengaduan_alat_perikanan/" . $row['path_gambar'];

                            $petugasImagePath = "../petugas/uploads/pengaduan_alat_perikanan/" . $row['keterangan_petugas'];
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo $row['id_pengaduan'] ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['jenis_alat']; ?></td>
                                <td><?php echo $row['waktu_kerusakan']; ?></td>
                                <td><?php echo $row['penyebab_kerusakan']; ?></td>
                                <td><?php echo $row['permintaan']; ?></td>
                                <td class="text-center">
                                    <?php if (!empty($row['path_gambar']) && file_exists($imagePath)) : ?>
                                        <a href="<?php echo $imagePath; ?>" target="_blank">
                                            <img src="<?php echo $imagePath; ?>" alt="Gambar Pengaduan" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc; border-radius: 5px;">
                                        </a>
                                        <br>
                                        <small><a href="<?php echo $imagePath; ?>" download>Download</a></small>
                                    <?php else : ?>
                                        <span class="text-muted">Tidak ada gambar</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $row['stts_pengaduan'] ?></td>

                                <td><?php echo $row['tgl_pengaduan'] ?></td>
                                <td><?php echo $row['tgl_selesai'] ? $row['tgl_selesai'] : '<span class="text-muted">Belum selesai</span>'; ?></td>
                                <td>
                                    <?php if (!empty($row['keterangan_petugas']) && file_exists($petugasImagePath)) : ?>
                                        <a href="<?php echo $petugasImagePath; ?>" target="_blank">
                                            <img src="<?php echo $petugasImagePath; ?>" alt="Gambar Pengaduan" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc; border-radius: 5px;">
                                        </a>
                                        <br>
                                        <small><a href="<?php echo $petugasImagePath; ?>" download>Download</a></small>
                                    <?php else : ?>
                                        <span class="text-muted">Belum ada gambar</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $row['catatan_admin']?></td>
                                <td class="text-center">
                                    <a href="pengaduan_alat_perikanan.php?hal=edit&id=<?php echo $row['id_pengaduan']; ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="pengaduan_alat_perikanan.php?hal=hapus&id=<?php echo $row['id_pengaduan']; ?>" onclick="return confirm('Apakah yakin ingin menghapus data ini?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>