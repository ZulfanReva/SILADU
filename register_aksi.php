<?php
session_start();
include "koneksi.php";

// Ambil data dari form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$nama     = $_POST['nama'] ?? '';
$email    = $_POST['email'] ?? '';
$alamat   = $_POST['alamat'] ?? '';
$tlp      = $_POST['tlp'] ?? '';
$level    = $_POST['level'] ?? '';
$nip      = $_POST['nip'] ?? '';
$kode     = $_POST['kode'] ?? ''; // ambil kode admin

// Validasi input wajib
if (!$username || !$password || !$nama || !$email || !$alamat || !$tlp || !$level) {
    header("location:index.php?modal=register&alert=gagal&error=kosong");
    exit();
}

// Validasi tambahan berdasarkan level
if ($level === 'petugas' && (strlen($nip) !== 18 || !ctype_digit($nip))) {
    header("location:index.php?modal=register&alert=gagal&error=nip");
    exit();
}

if ($level === 'admin' && !preg_match('/^[F-J0-9]{7}$/', $kode)) {
    header("location:index.php?modal=register&alert=gagal&error=kode");
    exit();
}

// Cek apakah username sudah digunakan
$stmt = $koneksi->prepare("SELECT id_user FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    header("location:index.php?modal=register&alert=gagal&error=username");
    exit();
}
$stmt->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Siapkan query sesuai level
if ($level === 'admin') {
    $nip = $kode; // simpan kode di kolom nip
}

// Simpan data ke DB
$stmt = $koneksi->prepare("INSERT INTO user (username, password, nama, email, alamat, tlp, level, nip) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $username, $hashed_password, $nama, $email, $alamat, $tlp, $level, $nip);

if ($stmt->execute()) {
    header("location:index.php?modal=login&alert=sukses&msg=register");
} else {
    header("location:index.php?modal=register&alert=gagal&error=db");
}
$stmt->close();
