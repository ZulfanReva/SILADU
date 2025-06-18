<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
}

if (isset($_GET['id'])) {
    $id_pengajuan = $_GET['id'];
    $query = $koneksi->query("SELECT * FROM produksi_pangan WHERE id_pengajuan = '$id_pengajuan'");
    $data = $query->fetch_assoc();

    if (!$data) {
        die("Data tidak ditemukan.");
    }
} else {
    die("ID Pengajuan tidak diberikan.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Detail Pengajuan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Detail Pengajuan</h1>
        <table class="table table-bordered">
            <tr>
                <th>Nama Pemohon</th>
                <td><?= $data['nama_pemohon'] ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= $data['email_pemohon'] ?></td>
            </tr>
            <tr>
                <th>Telepon</th>
                <td><?= $data['telp_pemohon'] ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?= $data['alamat_pemohon'] ?></td>
            </tr>
            <tr>
                <th>Jenis Usaha</th>
                <td><?= $data['jenis_usaha'] ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= $data['status_pengajuan'] ?></td>
            </tr>
            <tr>
                <th>Tanggal Pengajuan</th>
                <td><?= $data['tgl_pengajuan'] ?></td>
            </tr>
            <tr>
                <th>Dokumen</th>
                <td><a href="uploads/<?= $data['file_dokumen'] ?>" target="_blank"><?= $data['file_dokumen'] ?></a></td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td><?= $data['keterangan'] ?></td>
            </tr>
        </table>
        <a href="update_produksi.php?id=<?= $id_pengajuan ?>" class="btn btn-warning">Edit</a>
        <a href="produksi_pangan.php" class="btn btn-secondary">Kembali</a>
    </div>
</body>

</html>
