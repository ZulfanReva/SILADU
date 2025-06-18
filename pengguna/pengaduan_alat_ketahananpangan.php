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
        $direktori = "../petugas/uploads/pengaduan_alat_ketahananpangan/";

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
            $query = "UPDATE pengaduan_alat_ketahananpangan SET
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
                    window.location.href = 'pengaduan_alat_ketahananpangan.php';
                </script>";
            } else {
                echo "<script>alert('Edit Data Gagal!'); history.back();</script>";
            }
        } else {
            $query = "SELECT id_user FROM user WHERE username = '$username'";
            $result = $koneksi->query($query);
            $row = $result->fetch_assoc();
            $id_user = $row['id_user'];
            $sql = "INSERT INTO pengaduan_alat_ketahananpangan (id_user, jenis_alat, waktu_kerusakan, penyebab_kerusakan, permintaan, path_gambar, tgl_pengaduan)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $id_user, $jenis_alat, $waktu_kerusakan, $penyebab_kerusakan, $permintaan, $gambar_baru);
            $insert_hasil = mysqli_stmt_execute($stmt);
            
            if ($insert_hasil) {
                echo "<script>
                    alert('Berhasil Mengirim pengaduan!');
                    window.location.href = 'pengaduan_alat_ketahananpangan.php?status=sukses';
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
    if ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM pengaduan_alat_ketahananpangan WHERE id_pengaduan='$_GET[id]'");
        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Sukses!');
                    location='pengaduan_alat_ketahananpangan.php';
                  </script>";
        }
    } else if ($_GET['hal'] == "edit") {
        $b = mysqli_query($koneksi, "SELECT * FROM pengaduan_alat_ketahananpangan WHERE id_pengaduan='$_GET[id]'");
        $data = mysqli_fetch_array($b);
        if ($data) {
            $jenis_alat = $data['jenis_alat'];
            $waktu_kerusakan = $data['waktu_kerusakan'];
            $penyebab_kerusakan = $data['penyebab_kerusakan'];
            $permintaan = $data['permintaan'];
            $path_gambar = $data['path_gambar'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pengaduan Alat Ketahanan Pangan</title>
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

    <h1>Pengaduan Alat Ketahanan Pangan</h1>
    <div class="container-fluid administrasi">
        <div class="card-wrap mt-5">
            <div class="card administrasi-form">
                <div class="card-header form-peng">
                    <h2 class="card-title text-center">Form Pengaduan Alat Ketahanan Pangan</h2>
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
                                <input type="text" class="form-control" name="username" value="<?= $nama; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Alat</label>
                            <div class="col-sm-9">
                                <select name="jenis_alat" class="form-control" id="jenis_alat">
                                    <option value="">Pilih Jenis Alat</option>
                                    <optgroup label="1. Alat Penyimpanan dan Pengawetan Pangan">
                                        <option value="Cold Storage" <?= ($jenis_alat == "Cold Storage") ? 'selected' : '' ?>>Cold Storage (Ruang Pendingin) → Untuk menyimpan bahan pangan agar tetap segar.</option>
                                        <option value="Vacuum Sealer" <?= ($jenis_alat == "Vacuum Sealer") ? 'selected' : '' ?>>Vacuum Sealer → Untuk mengemas makanan agar tahan lama.</option>
                                        <option value="Silo" <?= ($jenis_alat == "Silo") ? 'selected' : '' ?>>Silo → Digunakan untuk menyimpan gabah atau bahan pangan dalam jumlah besar.</option>
                                        <option value="Gudang Penyimpanan Pangan" <?= ($jenis_alat == "Gudang Penyimpanan Pangan") ? 'selected' : '' ?>>Gudang Penyimpanan Pangan → Untuk menjaga stok pangan dalam jangka waktu tertentu.</option>
                                    </optgroup>
                                    <optgroup label="2. Alat Pengolahan Pangan">
                                        <option value="Mesin Pengering Pangan" <?= ($jenis_alat == "Mesin Pengering Pangan") ? 'selected' : '' ?>>Mesin Pengering Pangan → Untuk mengurangi kadar air pada bahan pangan seperti beras, jagung, dan ikan.</option>
                                        <option value="Alat Penggiling Padi" <?= ($jenis_alat == "Alat Penggiling Padi") ? 'selected' : '' ?>>Alat Penggiling Padi (Rice Mill) → Untuk menggiling gabah menjadi beras siap konsumsi.</option>
                                        <option value="Alat Penepung" <?= ($jenis_alat == "Alat Penepung") ? 'selected' : '' ?>>Alat Penepung (Disk Mill) → Untuk menggiling bahan pangan seperti jagung dan kedelai menjadi tepung.</option>
                                        <option value="Alat Pengasapan Ikan" <?= ($jenis_alat == "Alat Pengasapan Ikan") ? 'selected' : '' ?>>Alat Pengasapan Ikan → Untuk mengawetkan ikan dengan proses pengasapan.</option>
                                        <option value="Alat Pengolahan Produk Olahan" <?= ($jenis_alat == "Alat Pengolahan Produk Olahan") ? 'selected' : '' ?>>Alat Pengolahan Produk Olahan → Seperti mesin pembuat kerupuk, mesin pembuat nugget, dan lainnya.</option>
                                    </optgroup>
                                    <optgroup label="3. Alat Pengujian Mutu Pangan">
                                        <option value="Moisture Meter" <?= ($jenis_alat == "Moisture Meter") ? 'selected' : '' ?>>Moisture Meter → Untuk mengukur kadar air dalam beras, jagung, atau bahan pangan lainnya.</option>
                                        <option value="pH Meter" <?= ($jenis_alat == "pH Meter") ? 'selected' : '' ?>>pH Meter → Untuk mengukur tingkat keasaman bahan pangan.</option>
                                        <option value="Alat Uji Formalin dan Boraks" <?= ($jenis_alat == "Alat Uji Formalin dan Boraks") ? 'selected' : '' ?>>Alat Uji Formalin dan Boraks → Untuk mendeteksi bahan kimia berbahaya dalam makanan.</option>
                                        <option value="Spektrofotometer" <?= ($jenis_alat == "Spektrofotometer") ? 'selected' : '' ?>>Spektrofotometer → Untuk menguji kandungan gizi dan zat kimia dalam bahan pangan.</option>
                                    </optgroup>
                                    <optgroup label="4. Alat Distribusi dan Logistik Pangan">
                                        <option value="Truk Berpendingin" <?= ($jenis_alat == "Truk Berpendingin") ? 'selected' : '' ?>>Truk Berpendingin (Refrigerated Truck) → Untuk mendistribusikan bahan pangan yang mudah rusak seperti daging dan susu.</option>
                                        <option value="Kontainer Berpendingin" <?= ($jenis_alat == "Kontainer Berpendingin") ? 'selected' : '' ?>>Kontainer Berpendingin → Untuk menyimpan dan mengangkut stok pangan dalam jumlah besar.</option>
                                        <option value="Timbangan Digital" <?= ($jenis_alat == "Timbangan Digital") ? 'selected' : '' ?>>Timbangan Digital → Untuk menimbang hasil pertanian dan pangan sebelum distribusi.</option>
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
            <h3 class="card-title text-center">Daftar Pengaduan Alat Ketahanan Pangan</h3>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr class="text-center">
                        <th>No.</th>
                        <th scope="col">ID Pengaduan</th>
                        <th scope="col">Nama Pemohon</th>
                        <th scope="col">Jenis Alat</th>
                        <th scope="col">Waktu Kerusakan</th>
                        <th scope="col">Penyebab Kerusakan</th>
                        <th scope="col">Permintaan</th>
                        <th scope="col">Path Gambar</th>
                        <th scope="col">Status Pengaduan</th>
                        <th scope="col">Tanggal Pengaduan</th>
                        <th scope="col">Tanggal Selesai</th>
                        <th scope="col">Keterangan Petugas</th>
                        <th scope="col">Catatan Admin</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    <?php
                    $no = 1;
                    if ($_SESSION['level'] == "petugas" || $_SESSION['level'] == "admin") {
                        $query = "SELECT * FROM pengaduan_alat_ketahananpangan";
                    } elseif ($_SESSION['level'] == "warga") {
                        $query = "SELECT * FROM pengaduan_alat_ketahananpangan WHERE nama_pemohon='$nama'";
                    }
                    $query = "SELECT pengaduan_alat_ketahananpangan.*, user.nama
                    FROM pengaduan_alat_ketahananpangan
                    JOIN user ON pengaduan_alat_ketahananpangan.id_user = user.id_user";
                    $result = mysqli_query($koneksi, $query);

                    while ($row = mysqli_fetch_array($result)) :
                        $imagePath = "../petugas/uploads/pengaduan_alat_ketahananpangan/" . $row['path_gambar'];
                        $petugasImagePath = "../petugas/uploads/Pengaduan_alat_ketahananpangan/" . $row['keterangan_petugas'];
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $no++ ?></td>
                            <td><?php echo $row['id_pengaduan'] ?></td>
                            <td><?php echo $row['nama'] ?></td>
                            <td><?php echo $row['jenis_alat'] ?></td>
                            <td><?php echo $row['waktu_kerusakan'] ?></td>
                            <td><?php echo $row['penyebab_kerusakan'] ?></td>
                            <td><?php echo $row['permintaan'] ?></td>
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
                                <a href="pengaduan_alat_ketahananpangan.php?hal=edit&id=<?php echo $row['id_pengaduan'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="pengaduan_alat_ketahananpangan.php?hal=hapus&id=<?php echo $row['id_pengaduan'] ?>" onclick="return confirm('Apakah yakin ingin menghapus data ini?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>