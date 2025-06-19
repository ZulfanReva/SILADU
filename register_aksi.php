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
$nip      = $_POST['nip'] ?? '';   // hanya untuk petugas & kepala dinas
$kode     = $_POST['kode'] ?? '';  // hanya untuk admin

// Validasi input wajib
if (!$username || !$password || !$nama || !$email || !$alamat || !$tlp || !$level) {
    header("location:index.php?modal=register&alert=gagal&error=kosong");
    exit();
}

// Validasi tambahan berdasarkan level
if (in_array($level, ['petugas', 'kepala_dinas']) && (strlen($nip) !== 18 || !ctype_digit($nip))) {
    header("location:index.php?modal=register&alert=gagal&error=nip");
    exit();
}

if ($level === 'admin') {
    if (!preg_match('/^[F-J0-9]{7}$/', $kode)) {
        header("location:index.php?modal=register&alert=gagal&error=kode");
        exit();
    }
    $nip = $kode; // admin simpan kode di kolom nip
}

if ($level === 'warga') {
    $nip = ''; // pastikan nip kosong untuk warga
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

// Simpan data ke DB
$stmt = $koneksi->prepare("INSERT INTO user (username, password, nama, email, alamat, tlp, level, nip) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $username, $hashed_password, $nama, $email, $alamat, $tlp, $level, $nip);

if ($stmt->execute()) {
    header("location:index.php?modal=login&alert=sukses&msg=register");
} else {
    header("location:index.php?modal=register&alert=gagal&error=db");
}
$stmt->close();
$koneksi->close();
exit();
?>
<?php
// Catatan: Pastikan koneksi.php sudah benar dan sesuai dengan konfigurasi database Anda.