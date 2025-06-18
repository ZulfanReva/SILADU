<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$kode = $_GET['id'] ?? '';
$nama = $_SESSION['nama'];
$jenis_tanaman = $_POST['jenis_tanaman'] ?? '';
$alamat_pemohon = $_POST['alamat_pemohon'] ?? '';
$status = $_POST['status'] ?? '';

if (isset($_POST['simpan'])) {
    if (!empty($jenis_tanaman) && !empty($alamat_pemohon)) {
        $query = "SELECT id_user FROM user WHERE username = '$username'";
        $result = $koneksi->query($query);
        $row = $result->fetch_assoc();
        $id_user = $row['id_user'];

        if (isset($_GET['hal']) && $_GET['hal'] == "edit") {
            $query = "UPDATE permohonan_uji_tanaman SET
                    jenis_tanaman = '$jenis_tanaman',
                    alamat_pemohon = '$alamat_pemohon',
                    tgl_permohonan = NOW(),
                    status = '$status'
                    WHERE id_permohonan = '$kode'";

            $edit = mysqli_query($koneksi, $query);

            if ($edit) {
                echo "<script>
                    alert('Berhasil Memperbarui permohonan!');
                    window.location.href = 'permohonan_uji_tanaman.php';
                </script>";
            } else {
                echo "<script>alert('Edit Data Gagal!'); history.back();</script>";
            }
        } else {
            $sql = "INSERT INTO permohonan_uji_tanaman (id_user, jenis_tanaman, alamat_pemohon, tgl_permohonan, status)
                    VALUES (?, ?, ?, NOW(), 'Diajukan')";
            
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $id_user, $jenis_tanaman, $alamat_pemohon);
            $insert_hasil = mysqli_stmt_execute($stmt);
            
            if ($insert_hasil) {
                echo "<script>
                    alert('Berhasil Mengirim permohonan!');
                    window.location.href = 'permohonan_uji_tanaman.php?status=sukses';
                </script>";
            } else {
                echo "<script>alert('Gagal menyimpan permohonan!'); history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Ada Input yang Kosong!'); history.back();</script>";
    }
}

if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "edit") {
        $b = mysqli_query($koneksi, "SELECT * FROM permohonan_uji_tanaman WHERE id_permohonan='$_GET[id]'");
        $data = mysqli_fetch_array($b);
        if ($data) {
            $jenis_tanaman = $data['jenis_tanaman'];
            $alamat_pemohon = $data['alamat_pemohon'];
            $status = $data['status'];
        }
    } elseif ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM permohonan_uji_tanaman WHERE id_permohonan='$_GET[id]'");
        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Sukses!');
                    location='permohonan_uji_tanaman.php';
                  </script>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Permohonan Uji Tanaman</title>
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

    <h1>Permohonan Uji Tanaman</h1>
    <div class="container-fluid administrasi">
        <div class="card-wrap mt-5">
            <div class="card administrasi-form">
                <div class="card-header form-peng">
                    <h2 class="card-title text-center">Form Permohonan Uji Tanaman</h2>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3 row">
                            <label for="disabledTextInput" class="col-sm-3 col-form-label text-start">ID Permohonan</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="id" value="<?= $data['id_permohonan'] ?? ''; ?>" placeholder="Otomatis Oleh Sistem" disabled>
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
                            <label class="col-sm-3 col-form-label text-start">Alamat Pemohon</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="alamat_pemohon" placeholder="Tulis alamat pemohon"><?= $alamat_pemohon; ?></textarea>
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
            <h3 class="card-title text-center">Daftar Permohonan Uji Tanaman</h3>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr class="text-center">
                        <th>No.</th>
                        <th scope="col">ID Permohonan</th>
                        <th scope="col">Nama Pemohon</th>
                        <th scope="col">Jenis Tanaman</th>
                        <th scope="col">Alamat Pemohon</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal Permohonan</th>
                        <th scope="col">Tanggal Uji</th>
                        <th scope="col">Hasil Uji</th>
                        <th scope="col">Keterangan Petugas</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    <?php
                    $no = 1;
                    $query = "SELECT permohonan_uji_tanaman.*, user.nama
                              FROM permohonan_uji_tanaman
                              JOIN user ON permohonan_uji_tanaman.id_user = user.id_user";
                    $result = mysqli_query($koneksi, $query);

                    while ($row = mysqli_fetch_array($result)) :
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?php echo $row['id_permohonan'] ?></td>
                            <td><?php echo $row['nama'] ?></td>
                            <td><?php echo $row['jenis_tanaman'] ?></td>
                            <td><?php echo $row['alamat_pemohon'] ?></td>
                            <td><?php echo $row['status'] ?></td>
                            <td><?php echo $row['tgl_permohonan'] ?></td>
                            <td><?php echo $row['tgl_uji'] ? $row['tgl_uji'] : '<span class="text-muted">Belum dijadwalkan</span>'; ?></td>
                            <td><?php echo $row['hasil_uji'] ? $row['hasil_uji'] : '<span class="text-muted">Belum ada hasil</span>'; ?></td>
                            <td><?php echo $row['keterangan_petugas'] ? $row['keterangan_petugas'] : '<span class="text-muted">Belum ada keterangan</span>'; ?></td>
                            <td class="text-center">
                                <a href="permohonan_uji_tanaman.php?hal=edit&id=<?php echo $row['id_permohonan'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="permohonan_uji_tanaman.php?hal=hapus&id=<?php echo $row['id_permohonan'] ?>" onclick="return confirm('Apakah yakin ingin menghapus data ini?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
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
