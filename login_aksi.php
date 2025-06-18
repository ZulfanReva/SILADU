<?php
session_start();
include "koneksi.php";

// Ambil input dari form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$level    = $_POST['level'] ?? '';

// Validasi input
if (empty($username) || empty($password) || empty($level)) {
    header("location:index.php?modal=login&alert=gagal&error=kosong");
    exit();
}

// Ambil data user berdasarkan username
$stmt = $koneksi->prepare("SELECT id_user, username, password, nama, level FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Jika user tidak ditemukan
if ($result->num_rows === 0) {
    header("location:index.php?modal=login&alert=gagal&error=username");
    exit();
}

$user = $result->fetch_assoc();

// Verifikasi password (dengan hash)
if (!password_verify($password, $user['password'])) {
    header("location:index.php?modal=login&alert=gagal&error=password");
    exit();
}

// Verifikasi level cocok
if (strtolower($user['level']) !== strtolower($level)) {
    header("location:index.php?modal=login&alert=gagal&error=level");
    exit();
}

// Simpan data ke session
$_SESSION['id_user']  = $user['id_user'];
$_SESSION['username'] = $user['username'];
$_SESSION['nama']     = $user['nama'];
$_SESSION['level']    = $user['level'];


header("location:dashboard/pages/dashboard.php");

exit();

