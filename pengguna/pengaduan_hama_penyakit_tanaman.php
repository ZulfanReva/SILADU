<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$kode = $_GET['id'];
$nama = $_SESSION['nama'];
$jenis_tanaman = $_POST['jenis_tanaman'] ?? '';
$jenis_hama_penyakit = $_POST['jenis_hama_penyakit'] ?? '';
$alamat_pengadu = $_POST['alamat_pengadu'] ?? '';

if (isset($_POST['simpan'])) {
    if (!empty($jenis_tanaman) && !empty($jenis_hama_penyakit) && !empty($alamat_pengadu)) {
        $query = "SELECT id_user FROM user WHERE username = '$username'";
        $result = $koneksi->query($query);
        $row = $result->fetch_assoc();
        $id_user = $row['id_user'];

        if (isset($_GET['hal']) && $_GET['hal'] == "edit") {
            $query = "UPDATE pengaduan_hama_penyakit_tanaman SET
                    jenis_tanaman = '$jenis_tanaman',
                    jenis_hama_penyakit = '$jenis_hama_penyakit',
                    alamat_pengadu = '$alamat_pengadu',
                    tgl_pengaduan = NOW()
                    WHERE id_pengaduan = '$kode'";

            $edit = mysqli_query($koneksi, $query);

            if ($edit) {
                echo "<script>
                    alert('Berhasil Memperbarui pengaduan!');
                    window.location.href = 'pengaduan_hama_penyakit_tanaman.php';
                </script>";
            } else {
                echo "<script>alert('Edit Data Gagal!'); history.back();</script>";
            }
        } else {
            $sql = "INSERT INTO pengaduan_hama_penyakit_tanaman (id_user, jenis_tanaman, jenis_hama_penyakit, alamat_pengadu, tgl_pengaduan, status)
                    VALUES (?, ?, ?, ?, NOW(), 'menunggu')";
            
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $id_user, $jenis_tanaman, $jenis_hama_penyakit, $alamat_pengadu);
            $insert_hasil = mysqli_stmt_execute($stmt);
            
            if ($insert_hasil) {
                echo "<script>
                    alert('Berhasil Mengirim pengaduan!');
                    window.location.href = 'pengaduan_hama_penyakit_tanaman.php?status=sukses';
                </script>";
            } else {
                echo "<script>alert('Gagal menyimpan pengaduan!'); history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Ada Input yang Kosong!'); history.back();</script>";
    }
}

if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "edit") {
        $b = mysqli_query($koneksi, "SELECT * FROM pengaduan_hama_penyakit_tanaman WHERE id_pengaduan='$_GET[id]'");
        $data = mysqli_fetch_array($b);
        if ($data) {
            $jenis_tanaman = $data['jenis_tanaman'];
            $jenis_hama_penyakit = $data['jenis_hama_penyakit'];
            $alamat_pengadu = $data['alamat_pengadu'];
        }
    } elseif ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM pengaduan_hama_penyakit_tanaman WHERE id_pengaduan='$_GET[id]'");
        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Sukses!');
                    location='pengaduan_hama_penyakit_tanaman.php';
                  </script>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pengaduan Hama dan Penyakit Tanaman</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <style>
        h1 {
            margin-top: 80px;
            text-align: center;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .layanan:hover>.dropdown-menu {
            display: block;
        }

        button {
            background-color: #2d2d44;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #fa736b;
        }

        .navbar-brand {
            font-weight: bold;
            color: #cdc2ae;
        }

        .administrasi {
            width: 95%;
        }

        .form-peng {
            background-color: #ECE5C7;
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-lg fixed-top" style="background-color: #68A7AD;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../home.php">S I L A D U M A</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../home.php">Home</a>
                    </li>
                </ul>
                <span class="navbar-profile">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php $username = $_SESSION['username'];
                                echo "$username"; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </span>
            </div>
        </div>
    </nav>

    <h1>Pengaduan Hama dan Penyakit Tanaman</h1>
    <div class="container-fluid administrasi">
        <div class="card-wrap mt-5">
            <div class="card administrasi-form">
                <div class="card-header form-peng">
                    <h2 class="card-title text-center">Form Pengaduan Hama dan Penyakit Tanaman</h2>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3 row">
                            <label for="disabledTextInput" class="col-sm-3 col-form-label text-start">ID Pengaduan</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="id" value="<?= $data['id_pengaduan'] ?? ''; ?>" placeholder="Otomatis Oleh Sistem" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Nama Pemohon</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="username" value="<?= $nama; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Tanaman</label>
                            <div class="col-sm-9">
                                <select name="jenis_tanaman" class="form-control" id="jenis_tanaman">
                                    <option value="">Pilih Jenis Tanaman</option>
                                    <option value="padi" <?= ($jenis_tanaman == "padi") ? 'selected' : '' ?>>Padi</option>
                                    <option value="cabai" <?= ($jenis_tanaman == "cabai") ? 'selected' : '' ?>>Cabai</option>
                                    <option value="tomat" <?= ($jenis_tanaman == "tomat") ? 'selected' : '' ?>>Tomat</option>
                                    <option value="terong" <?= ($jenis_tanaman == "terong") ? 'selected' : '' ?>>Terong</option>
                                    <option value="bayam" <?= ($jenis_tanaman == "bayam") ? 'selected' : '' ?>>Bayam</option>
                                    <option value="kangkung" <?= ($jenis_tanaman == "kangkung") ? 'selected' : '' ?>>Kangkung</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Hama/Penyakit</label>
                            <div class="col-sm-9">
                                <select name="jenis_hama_penyakit" class="form-control" id="jenis_hama_penyakit">
                                    <option value="">Pilih Jenis Hama/Penyakit</option>
                                    <option value="hama wereng hijau" <?= ($jenis_hama_penyakit == "hama wereng hijau") ? 'selected' : '' ?>>Hama Wereng Hijau</option>
                                    <option value="penyakit tungro" <?= ($jenis_hama_penyakit == "penyakit tungro") ? 'selected' : '' ?>>Penyakit Tungro</option>
                                    <option value="hama kutu daun" <?= ($jenis_hama_penyakit == "hama kutu daun") ? 'selected' : '' ?>>Hama Kutu Daun</option>
                                    <option value="penyakit Antraknosa" <?= ($jenis_hama_penyakit == "penyakit Antraknosa") ? 'selected' : '' ?>>Penyakit Antraknosa</option>
                                    <option value="Hama Lalat Buah" <?= ($jenis_hama_penyakit == "Hama Lalat Buah") ? 'selected' : '' ?>>Hama Lalat Buah</option>
                                    <option value="Penyakit Layu Bakteri" <?= ($jenis_hama_penyakit == "Penyakit Layu Bakteri") ? 'selected' : '' ?>>Penyakit Layu Bakteri</option>
                                    <option value="Hama Trips" <?= ($jenis_hama_penyakit == "Hama Trips") ? 'selected' : '' ?>>Hama Trips</option>
                                    <option value="Penyakit Kutu Kebul" <?= ($jenis_hama_penyakit == "Penyakit Kutu Kebul") ? 'selected' : '' ?>>Penyakit Kutu Kebul</option>
                                    <option value="Hama Ulat Grayak" <?= ($jenis_hama_penyakit == "Hama Ulat Grayak") ? 'selected' : '' ?>>Hama Ulat Grayak</option>
                                    <option value="Penyakit Bercak Daun" <?= ($jenis_hama_penyakit == "Penyakit Bercak Daun") ? 'selected' : '' ?>>Penyakit Bercak Daun</option>
                                    <option value="Hama Belalang" <?= ($jenis_hama_penyakit == "Hama Belalang") ? 'selected' : '' ?>>Hama Belalang</option>
                                    <option value="Penyakit Layu Fusarium" <?= ($jenis_hama_penyakit == "Penyakit Layu Fusarium") ? 'selected' : '' ?>>Penyakit Layu Fusarium</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Alamat Pengadu</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="alamat_pengadu" placeholder="Tulis alamat pengadu"><?= $alamat_pengadu; ?></textarea>
                            </div>
                        </div>
                        <div class="button-align text-end">
                            <button type="submit" name="simpan" class="btn btn-success">SIMPAN</button>
                            <button type="reset" name="reset" class="btn btn-danger">RESET</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-wrap mt-4">
            <h3 class="card-title text-center">Daftar Pengaduan Hama dan Penyakit Tanaman</h3>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr class="text-center">
                        <th>No.</th>
                        <th scope="col">ID Pengaduan</th>
                        <th scope="col">Nama Pemohon</th>
                        <th scope="col">Jenis Tanaman</th>
                        <th scope="col">Jenis Hama/Penyakit</th>
                        <th scope="col">Alamat Pengadu</th>
                        <th scope="col">Status Pengaduan</th>
                        <th scope="col">Tanggal Pengaduan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    <?php
                    $no = 1;
                    $query = "SELECT pengaduan_hama_penyakit_tanaman.*, user.nama
                              FROM pengaduan_hama_penyakit_tanaman
                              JOIN user ON pengaduan_hama_penyakit_tanaman.id_user = user.id_user";
                    $result = mysqli_query($koneksi, $query);

                    while ($row = mysqli_fetch_array($result)) :
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?php echo $row['id_pengaduan'] ?></td>
                            <td><?php echo $row['nama'] ?></td>
                            <td><?php echo $row['jenis_tanaman'] ?></td>
                            <td><?php echo $row['jenis_hama_penyakit'] ?></td>
                            <td><?php echo $row['alamat_pengadu'] ?></td>
                            <td><?php echo $row['status'] ?></td>
                            <td><?php echo $row['tgl_pengaduan'] ?></td>
                            <td class="text-center">
                                <a href="pengaduan_hama_penyakit_tanaman.php?hal=edit&id=<?php echo $row['id_pengaduan'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="pengaduan_hama_penyakit_tanaman.php?hal=hapus&id=<?php echo $row['id_pengaduan'] ?>" onclick="return confirm('Apakah yakin ingin menghapus data ini?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
