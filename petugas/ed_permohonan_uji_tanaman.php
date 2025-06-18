<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
}

$username = $_SESSION['username'];
$kode = $_GET['id'] ?? '';
$jenis_tanaman = '';
$alamat_pemohon = '';
$tgl_permohonan = date('Y-m-d');
$status = '';
$tgl_uji = '';
$hasil_uji = '';
$keterangan_petugas = '';

// Ambil data jika sedang mengedit
if (isset($_GET['hal']) && $_GET['hal'] == "edit") {
    $b = mysqli_query($koneksi, "SELECT * FROM permohonan_uji_tanaman WHERE id_permohonan='$kode'");
    $data = mysqli_fetch_array($b);
    if ($data) {
        $jenis_tanaman = $data['jenis_tanaman'];
        $alamat_pemohon = $data['alamat_pemohon'];
        $status = $data['status'];
        $tgl_uji = $data['tgl_uji'];
        $hasil_uji = $data['hasil_uji'];
        $keterangan_petugas = $data['keterangan_petugas'];
        $tgl_permohonan = $data['tgl_permohonan'];
    }
}

// Proses simpan data
if (isset($_POST['simpan'])) {
    $jenis_tanaman = $_POST['jenis_tanaman'];
    $alamat_pemohon = $_POST['alamat_pemohon'];
    $tgl_permohonan = $_POST['tgl_permohonan'] ?? date('Y-m-d');
    $status = $_POST['status'];
    $tgl_uji = $_POST['tgl_uji'];
    $hasil_uji = $_POST['hasil_uji'];
    $keterangan_petugas = $_POST['keterangan_petugas'];
    $kode = $_POST['id_permohonan'] ?? '';

    if (!empty($jenis_tanaman) && !empty($alamat_pemohon) && !empty($status) && !empty($tgl_uji)) {
        if (isset($_GET['hal']) && $_GET['hal'] == "edit") {
            // UPDATE
            $query = "UPDATE permohonan_uji_tanaman SET
                        jenis_tanaman = ?,
                        alamat_pemohon = ?,
                        tgl_permohonan = ?,
                        status = ?,
                        tgl_uji = ?,
                        hasil_uji = ?,
                        keterangan_petugas = ?
                      WHERE id_permohonan = ?";

            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "sssssssi", $jenis_tanaman, $alamat_pemohon, $tgl_permohonan, $status, $tgl_uji, $hasil_uji, $keterangan_petugas, $kode);
            $edit = mysqli_stmt_execute($stmt);

            if ($edit) {
                echo "<script>
                        alert('Berhasil Memperbarui permohonan!');
                        window.location.href = 'hasil_permohonan_uji_tanaman.php';
                      </script>";
            } else {
                echo "<script>alert('Edit Data Gagal!'); history.back();</script>";
            }
        } else {
            // INSERT
            $sql = "INSERT INTO permohonan_uji_tanaman (id_user, jenis_tanaman, alamat_pemohon, tgl_permohonan, status, tgl_uji, hasil_uji, keterangan_petugas)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "isssssss", $_SESSION['id_user'], $jenis_tanaman, $alamat_pemohon, $tgl_permohonan, $status, $tgl_uji, $hasil_uji, $keterangan_petugas);
            $insert_hasil = mysqli_stmt_execute($stmt);

            if ($insert_hasil) {
                echo "<script>
                        alert('Berhasil Mengirim permohonan!');
                        window.location.href = 'permohonan_uji_tanaman.php?status=sukses';
                      </script>";
            } else {
                echo "<script>alert('Gagal menyimpan permohonan!'); history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Ada input yang kosong!'); history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Permohonan Uji Tanaman</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        h1 { margin-top: 80px; text-align: center; }
        body { font-family: 'Poppins', sans-serif; }
        .administrasi { width: 95%; }
        .form-peng { background-color: #ECE5C7; }
    </style>
</head>
<body>
<h1>Permohonan Uji Tanaman</h1>
<div class="container-fluid administrasi">
    <div class="card-wrap mt-5">
        <div class="card administrasi-form">
            <div class="card-header form-peng">
                <h2 class="card-title text-center">Form Permohonan Uji Tanaman</h2>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <!-- ID Permohonan -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-start">ID Permohonan</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="id_permohonan" value="<?= $kode ?? ''; ?>">
                            <input type="text" class="form-control" value="<?= $kode ?? ''; ?>" placeholder="Otomatis oleh sistem" disabled>
                        </div>
                    </div>

                    <!-- Nama Pemohon -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-start">Nama Pemohon</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="<?= $username; ?>" disabled>
                        </div>
                    </div>

                    <!-- Jenis Tanaman -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-start">Jenis Tanaman</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jenis_tanaman" value="<?= $jenis_tanaman; ?>" required>
                        </div>
                    </div>

                    <!-- Alamat Pemohon -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-start">Alamat Pemohon</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="alamat_pemohon" required><?= $alamat_pemohon; ?></textarea>
                        </div>
                    </div>

                    <!-- Tanggal Permohonan -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-start">Tgl. Permohonan</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_permohonan" value="<?= $tgl_permohonan; ?>" disabled>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-start">Status</label>
                        <div class="col-sm-9">
                            <select name="status" class="form-control" required>
                                <option value="">Pilih Status</option>
                                <option value="Disetujui" <?= ($status == 'Disetujui') ? 'selected' : ''; ?>>Disetujui</option>
                                <option value="Ditolak" <?= ($status == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                <option value="Diproses" <?= ($status == 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                                <option value="Diajukan" <?= ($status == 'Diajukan') ? 'selected' : ''; ?>>Diajukan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tanggal Uji -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-start">Tgl. Uji</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_uji" value="<?= $tgl_uji; ?>" required>
                        </div>
                    </div>

                    <!-- Hasil Uji -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-start">Hasil Uji</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="hasil_uji" value="<?= $hasil_uji; ?>">
                        </div>
                    </div>

                    <!-- Keterangan Petugas -->
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label text-start">Keterangan Petugas</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="keterangan_petugas"><?= $keterangan_petugas; ?></textarea>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="text-end">
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
