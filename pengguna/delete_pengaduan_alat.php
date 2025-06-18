<?php
session_start();
include "../koneksi.php";

// Memeriksa apakah ada id pengaduan yang dikirim
if (isset($_GET['id'])) {
    $id_pengaduan = $_GET['id'];  

    // Query untuk menghapus data pengaduan
    $delete = mysqli_query($koneksi, "DELETE FROM pengaduan_alat WHERE id_pengaduan='$id_pengaduan'");

    if ($delete) {
        // Jika berhasil menghapus data, tampilkan pesan sukses dan redirect ke pengaduan_alat.php
        echo "<script>alert('Hapus Data Pengaduan Sukses!'); location='pengaduan_alat.php';</script>";
    } else {
        // Jika gagal menghapus data, tampilkan pesan gagal
        echo "<script>alert('Gagal Menghapus Data Pengaduan');</script>";
    }
}
?>