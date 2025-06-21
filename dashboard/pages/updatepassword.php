<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login!']);
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'siladu2');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal.']);
    exit;
}

$id_user = $_SESSION['id_user'];
$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['new_password_confirmation'] ?? '';

if (empty($current) || empty($new) || empty($confirm)) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi.']);
    exit;
}

if ($new !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Konfirmasi kata sandi tidak cocok.']);
    exit;
}

if (strlen($new) < 8) {
    echo json_encode(['success' => false, 'message' => 'Kata sandi minimal 8 karakter.']);
    exit;
}

// Cek password lama
$stmt = $conn->prepare('SELECT password FROM user WHERE id_user = ?');
$stmt->bind_param('i', $id_user);
$stmt->execute();
$stmt->bind_result($hashedPassword);
$stmt->fetch();
$stmt->close();

if (!password_verify($current, $hashedPassword)) {
    echo json_encode(['success' => false, 'message' => 'Kata sandi lama salah.']);
    exit;
}

// Update password baru
$newHashed = password_hash($new, PASSWORD_BCRYPT);
$stmt = $conn->prepare('UPDATE user SET password = ? WHERE id_user = ?');
$stmt->bind_param('si', $newHashed, $id_user);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui password.']);
}
$stmt->close();
$conn->close();
