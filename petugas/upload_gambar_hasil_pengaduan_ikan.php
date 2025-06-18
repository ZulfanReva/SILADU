<?php
include '../koneksi.php';

if (isset($_POST['upload'])) {
    $id_pengaduan = $_POST['id_pengaduan'];
    $target_dir = "../petugas/uploads/hasil_pengaduan_alat_ikan/"; // Perbaikan path folder

    // Pastikan folder upload tersedia
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Ambil informasi file yang diupload
    $file_name = $_FILES['gambar']['name'];
    $file_tmp = $_FILES['gambar']['tmp_name'];
    $file_size = $_FILES['gambar']['size'];

    // Cek apakah file ada sebelum diproses
    if ($file_name != "" && $file_tmp != "") {
        $x = explode('.', $file_name);
        $ekstensi = strtolower(end($x));

        // Buat nama file unik
        $new_file_name = time() . '_' . $file_name;
        $target_file = $target_dir . $new_file_name;

        // Validasi tipe file
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (in_array($ekstensi, $allowed_types)) {
            // Cek apakah file berhasil dipindahkan
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Simpan path gambar ke database
                $query = "UPDATE hasil_pengaduan_alat_ikan SET path_gambar='$new_file_name' WHERE id_pengaduan='$id_pengaduan'";
                mysqli_query($koneksi, $query);

                echo "<script>alert('Gambar berhasil diupload!'); window.location.href='hasil_pengaduan_alat_ikan.php';</script>";
            } else {
                echo "<script>alert('Gagal mengupload gambar!'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Format gambar tidak didukung! Hanya JPG, JPEG, PNG, dan GIF.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Tidak ada file yang diupload!'); window.history.back();</script>";
    }
}
?>
