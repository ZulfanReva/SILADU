<?php
// simpan_catatan.php

session_start();
include "../koneksi.php";

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
}

if (isset($_POST['update_catatan'])) {
    $id_pengajuan = $_POST['id_pengajuan'];
    $catatan_petugas = $_POST['catatan_petugas'];

    if (!empty($id_pengajuan) && !empty($catatan_petugas)) {
        $query = "UPDATE hasil_pengajuan_izin SET catatan_admin = ? WHERE id_pengajuan = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("ss", $catatan_petugas, $id_pengajuan);

        if ($stmt->execute()) {
            echo "<script>alert('Catatan berhasil diperbarui!'); location='hasil_pengajuan_izin.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui catatan!');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Catatan tidak boleh kosong!');</script>";
    }
} else {
    echo "<script>alert('Akses langsung tidak diperbolehkan!'); location='hasil_pengajuan_izin.php';</script>";
}

?>
