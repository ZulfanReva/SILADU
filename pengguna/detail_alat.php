<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$id = $_GET['id'];
$query = $koneksi->query("SELECT * FROM pengaduan_alat WHERE id_pengaduan='$id'");
$data = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Detail Alat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1 {
            margin-top: 80px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Detail Pengaduan Alat</h1>
        <table class="table table-bordered mt-5">
            <tr>
                <th>Jenis Alat</th>
                <td><?= $data['jenis_alat'] ?></td>
            </tr>
            <tr>
                <th>Waktu Kerusakan</th>
                <td><?= $data['waktu_kerusakan'] ?></td>
            </tr>
            <tr>
                <th>Penyebab Kerusakan</th>
                <td><?= $data['penyebab_kerusakan'] ?></td>
            </tr>
            <tr>
                <th>Permintaan</th>
                <td><?= $data['permintaan'] ?></td>
            </tr>
            <tr>
                <th>Foto Kerusakan</th>
                <td><img src="uploads/<?= $data['foto_kerusakan'] ?>" width="300"></td>
            </tr>
        </table>
        <a href="update_alat.php?id=<?= $id_pengajuan ?>" class="btn btn-warning">Edit</a>
        <a href="pengaduan_alat.php" class="btn btn-primary">Kembali</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
