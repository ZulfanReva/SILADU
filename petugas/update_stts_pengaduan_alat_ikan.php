<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pengaduan = $_POST['id_pengaduan'];
    $stts_pengaduan = $_POST['stts_pengaduan'];

    // $sql = "UPDATE pengaduan_alat_perikanan = '$stts_pengaduan' WHERE id_pengaduan = '$id_pengaduan'";
    // $result = $koneksi->query($sql);

    // Pastikan untuk menghindari SQL injection dengan menggunakan prepared statement
    $sql = "UPDATE hasil_pengaduan_alat_ikan  SET stts_pengaduan = ? WHERE id_pengaduan = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("si", $stts_pengaduan, $id_pengaduan); // "si" berarti string dan integer

    if ($stmt->execute()) {
        echo "<script>alert('Status berhasil diupdate!');</script>";
    } else {
        echo "<script>alert('Gagal mengupdate status!');</script>";
    }

    header("refresh:2;url=hasil_pengaduan_alat_ikan.php");
}
?>