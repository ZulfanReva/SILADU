<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$id = $_GET['id'];
$query = $koneksi->query("SELECT * FROM pengaduan_alat WHERE id_pengaduan='$id'");
$data = $query->fetch_assoc();

if (isset($_POST['update'])) {
    $jenis_alat = $_POST['jenis_alat'];
    $waktu_kerusakan = $_POST['waktu_kerusakan'];
    $penyebab_kerusakan = $_POST['penyebab_kerusakan'];
    $permintaan = $_POST['permintaan'];

    // Handle file upload
    if ($_FILES['foto_kerusakan']['name']) {
        $file_name = $_FILES['foto_kerusakan']['name'];
        $file_tmp = $_FILES['foto_kerusakan']['tmp_name'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        if (!in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
            echo "<script>alert('Foto kerusakan harus berupa gambar (JPG, JPEG, PNG)');</script>";
            header("refresh:2;url=pengaduan_update.php?id=$id");
            exit;
        }

        $file_path = "uploads/" . $file_name;
        move_uploaded_file($file_tmp, $file_path);
        $sql = "UPDATE pengaduan_alat SET jenis_alat=?, waktu_kerusakan=?, penyebab_kerusakan=?, permintaan=?, foto_kerusakan=? WHERE id_pengaduan=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sssssi", $jenis_alat, $waktu_kerusakan, $penyebab_kerusakan, $permintaan, $file_name, $id);
    } else {
        $sql = "UPDATE pengaduan_alat SET jenis_alat=?, waktu_kerusakan=?, penyebab_kerusakan=?, permintaan=? WHERE id_pengaduan=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssssi", $jenis_alat, $waktu_kerusakan, $penyebab_kerusakan, $permintaan, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diupdate!');</script>";
        header("refresh:2;url=pengaduan_alat.php");
    } else {
        echo "<script>alert('Gagal mengupdate data!');</script>";
        header("refresh:2;url=pengaduan_update.php?id=$id");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Pengaduan Alat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1 {
            margin-top: 80px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Update Pengaduan Alat</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="jenis_alat" class="form-label">Jenis Alat</label>
                <input type="text" class="form-control" name="jenis_alat" id="jenis_alat" value="<?= $data['jenis_alat'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="waktu_kerusakan" class="form-label">Waktu Kerusakan</label>
                <input type="date" class="form-control" name="waktu_kerusakan" id="waktu_kerusakan" value="<?= $data['waktu_kerusakan'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="penyebab_kerusakan" class="form-label">Penyebab Kerusakan</label>
                <textarea class="form-control" name="penyebab_kerusakan" id="penyebab_kerusakan" required><?= $data['penyebab_kerusakan'] ?></textarea>
            </div>
            <div class="mb-3">
                <label for="permintaan" class="form-label">Permintaan</label>
                <textarea class="form-control" name="permintaan" id="permintaan" required><?= $data['permintaan'] ?></textarea>
            </div>
            <div class="mb-3">
                <label for="foto_kerusakan" class="form-label">Unggah Foto Kerusakan</label>
                <input type="file" class="form-control" name="foto_kerusakan" id="foto_kerusakan">
                <p><img src="uploads/<?= $data['foto_kerusakan'] ?>" width="150"></p>
            </div>
            <button type="submit" name="update" class="btn btn-success">Update</button>
            <a href="pengaduan_alat.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
