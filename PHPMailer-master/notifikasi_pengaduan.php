<?php
// File: notifikasi_pengaduan.php
// Pastikan PHPMailer sudah kamu download dan taruh di folder PHPMailer/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "siladu2");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$tabels = [
    'pengaduan_alat_pertanian' => ['jenis_alat', 'penyebab_kerusakan', 'tgl_pengaduan', 'tgl_selesai', 'stts_pengaduan'],
    'pengaduan_alat_perikanan' => ['jenis_alat', 'penyebab_kerusakan', 'tgl_pengaduan', 'tgl_selesai', 'stts_pengaduan'],
    'pengaduan_alat_ketahananpangan' => ['jenis_alat', 'penyebab_kerusakan', 'tgl_pengaduan', 'tgl_selesai', 'stts_pengaduan'],
    'pengajuan_izin' => ['jenis_izin', 'dokumen', 'tgl_pengajuan', 'status_pemohon'],
    'permohonan_bantuan' => ['jenis_bantuan', 'deskripsi_bantuan', 'tgl_pengajuan', 'status_pemohon'],
    'survey_kepuasan' => ['skor_penilaian', 'komentar', 'tgl_pengisian']
];

$tanggal_hari_ini = date('Y-m-d');
$isi_email = '';

foreach ($tabels as $tabel => $kolom) {
    if ($tabel === 'survey_kepuasan') continue; // tidak perlu notifikasi kadaluarsa

    $tgl_field = in_array('tgl_pengajuan', $kolom) ? 'tgl_pengajuan' : 'tgl_pengaduan';

    $query = "SELECT * FROM $tabel WHERE $tgl_field <= '$tanggal_hari_ini'";
    $result = $koneksi->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $isi_email .= "<p><strong>Tabel:</strong> $tabel</p>";
            foreach ($kolom as $field) {
                $label = ucwords(str_replace('_', ' ', $field));
                $isi_email .= "<p><strong>$label:</strong> {$row[$field]}</p>";
            }
            $isi_email .= "<hr>";
        }
    }
}

if (!empty($isi_email)) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hildanurfadilah.2110010167@gmail.com'; // Ganti dengan email Gmail kamu
        $mail->Password   = 'uzonvmdujqbvawvo';    // Ganti dengan sandi aplikasi Gmail
        $mail->SMTPSecure = 'tls ';
        $mail->Port       = 587;

        $mail->setFrom('hildanurfadilah.2110010167@gmail.com', 'SILADU Notifikasi');
        $mail->addAddress('aplikasisiladu2myfile@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'Notifikasi Otomatis - SILADU';
        $mail->Body    = "<h3>Data yang sudah melewati tanggal relevan per $tanggal_hari_ini:</h3>$isi_email";

        $mail->send();
        echo "Email berhasil dikirim.";
    } catch (Exception $e) {
        echo "Email gagal dikirim. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Tidak ada data yang perlu dikirim.";
}

$koneksi->close();
