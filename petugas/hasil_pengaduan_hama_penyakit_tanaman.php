<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$kode = $_GET['id'] ?? null; // Get the ID from the URL
$id_user = $_SESSION['id_user']; // Assuming user ID is stored in session
$jenis_tanaman = $_POST['jenis_tanaman'] ?? '';
$jenis_hama_penyakit = $_POST['jenis_hama_penyakit'] ?? '';
$alamat_pengadu = $_POST['alamat_pengadu'] ?? '';
$tgl_pengaduan = date('Y-m-d'); // Tanggal pengaduan otomatis
$status = $_POST['status'] ?? 'menunggu'; // Default status
$tgl_selesai = $_POST['tgl_selesai'] ?? null; // Optional field

if (isset($_POST['simpan'])) {
    if (!empty($jenis_tanaman) && !empty($jenis_hama_penyakit) && !empty($alamat_pengadu)) {
        if ($_GET['hal'] == "edit") {
            $query = "UPDATE pengaduan_hama_penyakit_tanaman SET
                    id_user = ?,
                    jenis_tanaman = ?,
                    jenis_hama_penyakit = ?,
                    alamat_pengadu = ?,
                    tgl_pengaduan = ?,
                    status = ?,
                    tgl_selesai = ?
                    WHERE id_pengaduan = ?";

            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "issssssi", $id_user, $jenis_tanaman, $jenis_hama_penyakit, $alamat_pengadu, $tgl_pengaduan, $status, $tgl_selesai, $kode);
            $edit = mysqli_stmt_execute($stmt);

            if ($edit) {
                echo "<script>
                    alert('Berhasil Memperbarui pengaduan!');
                    window.location.href = 'hasil_pengaduan_hama_penyakit_tanaman.php';
                </script>";
            } else {
                echo "<script>alert('Edit Data Gagal!'); history.back();</script>";
            }
        } else {
            $sql = "INSERT INTO pengaduan_hama_penyakit_tanaman (id_user, jenis_tanaman, jenis_hama_penyakit, alamat_pengadu, tgl_pengaduan, status, tgl_selesai)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "issssss", $id_user, $jenis_tanaman, $jenis_hama_penyakit, $alamat_pengadu, $tgl_pengaduan, $status, $tgl_selesai);
            $insert_hasil = mysqli_stmt_execute($stmt);

            if ($insert_hasil) {
                echo "<script>
                    alert('Berhasil Mengirim pengaduan!');
                    window.location.href = 'hasil_pengaduan_hama_penyakit_tanaman.php?status=sukses';
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
        $b = mysqli_query($koneksi, "SELECT * FROM hasil_pengaduan_hama_penyakit_tanaman WHERE id_pengaduan='$_GET[id]'");
        $data = mysqli_fetch_array($b);
        if ($data) {
            $jenis_tanaman = $data['jenis_tanaman'];
            $jenis_hama_penyakit = $data['jenis_hama_penyakit'];
            $alamat_pengadu = $data['alamat_pengadu'];
            $tgl_pengaduan = $data['tgl_pengaduan'];
            $status = $data['status'];
            $tgl_selesai = $data['tgl_selesai'];
        }
    } elseif ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM hasil_pengaduan_hama_penyakit_tanaman WHERE id_pengaduan='$_GET[id]'");
        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Sukses!');
                    location='hasil_pengaduan_hama_penyakit_tanaman.php';
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pengaduan Hama Penyakit Tanaman</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        h1 {
            margin-top: 80px;
            text-align: center;
        }

        body {
            font-family: 'Poppins', sans-serif;
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
                                <?php echo htmlspecialchars($username); ?>
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
    
    <h1>Form Pengaduan Hama Penyakit Tanaman</h1>
    <div class="container-fluid administrasi">
        <div class="card-wrap mt-5">
            <div class="card administrasi-form">
                <div class="card-header form-peng">
                    <h2 class="card-title text-center">Form Pengaduan</h2>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <!-- ID Pengaduan -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">ID Pengaduan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="id_pengaduan" value="<?= $data['id_pengaduan'] ?? ''; ?>" placeholder="Otomatis oleh sistem" disabled>
                            </div>
                        </div>

                        <!-- ID User -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">ID User</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="id_user" value="<?= $id_user; ?>" disabled>
                            </div>
                        </div>

                        <!-- Jenis Tanaman -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Tanaman</label>
                            <div class="col-sm-9">
                                <select name="jenis_tanaman" class="form-control" required>
                                    <option value="">Pilih Jenis Tanaman</option>
                                    <option value="padi" <?= (isset($jenis_tanaman) && $jenis_tanaman == 'padi') ? 'selected' : ''; ?>>Padi</option>
                                    <option value="cabai" <?= (isset($jenis_tanaman) && $jenis_tanaman == 'cabai') ? 'selected' : ''; ?>>Cabai</option>
                                    <option value="tomat" <?= (isset($jenis_tanaman) && $jenis_tanaman == 'tomat') ? 'selected' : ''; ?>>Tomat</option>
                                    <option value="terong" <?= (isset($jenis_tanaman) && $jenis_tanaman == 'terong') ? 'selected' : ''; ?>>Terong</option>
                                    <option value="bayam" <?= (isset($jenis_tanaman) && $jenis_tanaman == 'bayam') ? 'selected' : ''; ?>>Bayam</option>
                                    <option value="kangkung" <?= (isset($jenis_tanaman) && $jenis_tanaman == 'kangkung') ? 'selected' : ''; ?>>Kangkung</option>
                                </select>
                            </div>
                        </div>

                        <!-- Jenis Hama/Penyakit -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Hama/Penyakit</label>
                            <div class="col-sm-9">
                                <select name="jenis_hama_penyakit" class="form-control" required>
                                    <option value="">Pilih Jenis Hama/Penyakit</option>
                                    <option value="Hama wereng hijau, penyakit tungro" <?= (isset($jenis_hama_penyakit) && $jenis_hama_penyakit == 'Hama wereng hijau, penyakit tungro') ? 'selected' : ''; ?>>Hama wereng hijau, penyakit tungro</option>
                                    <option value="Hama kutu daun, penyakit antraknosa" <?= (isset($jenis_hama_penyakit) && $jenis_hama_penyakit == 'Hama kutu daun, penyakit antraknosa') ? 'selected' : ''; ?>>Hama kutu daun, penyakit antraknosa</option>
                                    <option value="Hama lalat buah, penyakit layu bakteri" <?= (isset($jenis_hama_penyakit) && $jenis_hama_penyakit == 'Hama lalat buah, penyakit layu bakteri') ? 'selected' : ''; ?>>Hama lalat buah, penyakit layu bakteri</option>
                                    <option value="Hama trips, penyakit kutu kebul" <?= (isset($jenis_hama_penyakit) && $jenis_hama_penyakit == 'Hama trips, penyakit kutu kebul') ? 'selected' : ''; ?>>Hama trips, penyakit kutu kebul</option>
                                    <option value="Hama ulat grayak, penyakit bercak daun" <?= (isset($jenis_hama_penyakit) && $jenis_hama_penyakit == 'Hama ulat grayak, penyakit bercak daun') ? 'selected' : ''; ?>>Hama ulat grayak, penyakit bercak daun</option>
                                    <option value="Hama belalang, penyakit layu fusarium" <?= (isset($jenis_hama_penyakit) && $jenis_hama_penyakit == 'Hama belalang, penyakit layu fusarium') ? 'selected' : ''; ?>>Hama belalang, penyakit layu fusarium</option>
                                </select>
                            </div>
                        </div>

                        <!-- Alamat Pengadu -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Alamat Pengadu</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="alamat_pengadu" placeholder="Tulis alamat" required><?= $alamat_pengadu ?? ''; ?></textarea>
                            </div>
                        </div>

                        <!-- Tanggal Pengaduan -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Tanggal Pengaduan</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="tgl_pengaduan" value="<?= $tgl_pengaduan ?? ''; ?>" disabled>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Status</label>
                            <div class="col-sm-9">
                                <select name="status" class="form-control" required>
                                    <option value="menunggu" <?= (isset($status) && $status == 'menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                                    <option value="diproses" <?= (isset($status) && $status == 'diproses') ? 'selected' : ''; ?>>Diproses</option>
                                    <option value="selesai" <?= (isset($status) && $status == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Tanggal Selesai</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="tgl_selesai" value="<?= $tgl_selesai ?? ''; ?>">
                            </div>
                        </div>

                        <!-- Keterangan Petugas -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Keterangan Petugas</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="keterangan_petugas" placeholder="Tulis keterangan petugas"><?= $keterangan_petugas ?? ''; ?></textarea>
                            </div>
                        </div>

                        <!-- Tombol Simpan dan Reset -->
                        <div class="button-align text-end">
                            <button type="submit" name="simpan" class="btn btn-success">SIMPAN</button>
                            <button type="reset" name="reset" class="btn btn-danger">RESET</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
