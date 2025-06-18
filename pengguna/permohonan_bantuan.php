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
$alamat = $_POST['alamat'] ?? '';
$kelurahan = $_POST['kelurahan'] ?? '';
$kecamatan = $_POST['kecamatan'] ?? '';
$no_telp = $_POST['no_telp'] ?? '';
$jenis_bantuan = $_POST['jenis_bantuan'] ?? '';
$deskripsi_bantuan = $_POST['deskripsi_bantuan'] ?? '';
$status_pemohon = 'Pending'; // Set default status pemohon
$tgl_pengajuan = date('Y-m-d'); // Set tanggal pengajuan ke hari ini
$tgl_persetujuan = ''; // Kosongkan tanggal persetujuan
$catatan_admin = ''; // Kosongkan catatan admin

if (isset($_POST['simpan'])) {
    if (!empty($alamat) && !empty($kelurahan) && !empty($kecamatan) && !empty($no_telp) && !empty($jenis_bantuan) && !empty($deskripsi_bantuan)) {
        if ($_GET['hal'] == "edit") {
            $query = "UPDATE permohonan_bantuan SET
                        alamat = '$alamat',
                        kelurahan = '$kelurahan',
                        kecamatan = '$kecamatan',
                        no_telp = '$no_telp',
                        jenis_bantuan = '$jenis_bantuan',
                        deskripsi_bantuan = '$deskripsi_bantuan',
                        status_pemohon = '$status_pemohon',
                        tgl_pengajuan = '$tgl_pengajuan',
                        tgl_persetujuan = '$tgl_persetujuan',
                        catatan_admin = '$catatan_admin'
                        WHERE id_bantuan = '$kode'";
            $edit = mysqli_query($koneksi, $query) or die("Error in query: $query");

            if ($edit) {
                echo "<script>
                    alert('Berhasil Memperbarui Permohonan!');
                    window.location.href = 'permohonan_bantuan.php';
                </script>";
            } else {
                echo "<script>alert('Edit Data Gagal!'); history.back();</script>";
            }
        } else {
            $query = "SELECT id_user FROM user WHERE username = '$username'";
            $result = $koneksi->query($query);
            $row = $result->fetch_assoc();
            $id_user = $row['id_user']; 

            $sql = "INSERT INTO permohonan_bantuan (id_user, alamat, kelurahan, kecamatan, no_telp, jenis_bantuan, deskripsi_bantuan, tgl_pengajuan, status_pemohon)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sssssssss", $id_user, $alamat, $kelurahan, $kecamatan, $no_telp, $jenis_bantuan, $deskripsi_bantuan, $tgl_pengajuan, $status_pemohon);

            $insert_hasil = mysqli_stmt_execute($stmt);

            if ($insert_hasil) {
                echo "<script>
                    alert('Berhasil Mengirim pengajuan!');
                    window.location.href = 'permohonan_bantuan.php?status=sukses';
                </script>";
            } else {
                echo "<script>alert('Gagal menyimpan pengajuan!'); history.back();</script>";
            }
        }
    } else {
        echo "<script>alert('Ada Input yang Kosong!');</script>";
        echo "<script>history.back();</script>";
    }
} else {
    echo "<script>location('permohonan_bantuan.php');</script>";
}

if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "edit") {
        $b = mysqli_query($koneksi, "SELECT * FROM permohonan_bantuan WHERE id_bantuan='$_GET[id]'");
        $data = mysqli_fetch_array($b);
        if ($data) {
            $alamat = $data['alamat'];
            $kelurahan = $data['kelurahan'] ?? '';
            $kecamatan = $data['kecamatan'] ?? '';
            $no_telp = $data['no_telp'];
            $jenis_bantuan = $data['jenis_bantuan'];
            $deskripsi_bantuan = $data['deskripsi_bantuan'];
            $status_pemohon = $data['status_pemohon'];
            $tgl_pengajuan = $data['tgl_pengajuan'];
            $tgl_persetujuan = $data['tgl_persetujuan'];
            $catatan_admin = $data['catatan_admin'];
        }
    } elseif ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM permohonan_bantuan WHERE id_bantuan='$_GET[id]'");
        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Sukses!');
                    location='permohonan_bantuan.php';
                  </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Permohonan Bantuan</title>
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
                                <?php echo $username; ?>
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

    <h1>Permohonan Bantuan</h1>
    <div class="container-fluid administrasi">
        <div class="card-wrap mt-5">
            <div class="card administrasi-form">
                <div class="card-header form-peng">
                    <h2 class="card-title text-center">Form Pengajuan Permohonan Bantuan</h2>
                </div>
                <div class="card-body">
                    
                    <form action="" method="POST">
                        <div class="mb-3 row">
                            <label for="disabledTextInput" class="col-sm-3 col-form-label text-start">ID Pemohon</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="id" value="<?= $data['id_bantuan'] ?? ''; ?>" placeholder="Otomatis Oleh Sistem" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Nama Pemohon</label>
                            <?php
                            $a = mysqli_query($koneksi, "select * from user where username='$_SESSION[username]'");
                            $tampil = mysqli_fetch_array($a);
                            ?>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="username" value="<?= $nama; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Alamat</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="alamat" placeholder="Tulis alamat"><?= $alamat; ?></textarea>
                            </div>
                        </div>
                         <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Kelurahan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="kelurahan" placeholder="Tulis kelurahan"><?= $kelurahan; ?></textarea>
                            </div>
                        </div>
                         <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Kecamatan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="kecamatan" placeholder="Tulis kecamatan"><?= $kecamatan; ?></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">No. Telp</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="no_telp" value="<?= $no_telp; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Bantuan</label>
                            <div class="col-sm-9">
                                <select name="jenis_bantuan" class="form-control">
                                    <option value="">Pilih Jenis Bantuan</option>
                                    <option value="Pangan" <?= (isset($jenis_bantuan) && $jenis_bantuan == 'Pangan') ? 'selected' : ''; ?>>Pangan</option>
                                    <option value="Pertanian" <?= (isset($jenis_bantuan) && $jenis_bantuan == 'Pertanian') ? 'selected' : ''; ?>>Pertanian</option>
                                    <option value="Perikanan" <?= (isset($jenis_bantuan) && $jenis_bantuan == 'Perikanan') ? 'selected' : ''; ?>>Perikanan</option>
                                    <option value="UMKM" <?= (isset($jenis_bantuan) && $jenis_bantuan == 'UMKM') ? 'selected' : ''; ?>>UMKM</option>
                                    <option value="Peternakan" <?= (isset($jenis_bantuan) && $jenis_bantuan == 'Peternakan') ? 'selected' : ''; ?>>Peternakan</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Deskripsi Bantuan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="deskripsi_bantuan" placeholder="Tulis deskripsi bantuan"><?= $deskripsi_bantuan; ?></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Status Pemohon</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="status_pemohon" value="<?= $status_pemohon; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Tgl. Pengajuan</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="tgl_pengajuan" value="<?= $tgl_pengajuan; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Tgl. Persetujuan</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="tgl_persetujuan" value="<?= $tgl_persetujuan; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Catatan Admin</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="catatan_admin" placeholder="Tulis catatan admin" disabled><?= $catatan_admin; ?></textarea>
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
            <h3 class="card-title text-center">Daftar Permohonan Bantuan</h3>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr class="text-center">
                        <th>No.</th>
                        <th scope="col">ID Pemohon</th>
                        <th scope="col">Nama Pemohon</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Kelurahan</th>
                        <th scope="col">Kecamatan</th>
                        <th scope="col">No. Telp</th>
                        <th scope="col">Jenis Bantuan</th>
                        <th scope="col">Deskripsi Bantuan</th>
                        <th scope="col">Status Pemohon</th>
                        <th scope="col">Tgl. Pengajuan</th>
                        <th scope="col">Tgl. Persetujuan</th>
                        <th scope="col">Catatan Admin</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    <?php
                    $no = 1;
                    if ($_SESSION['level'] == "petugas" || $_SESSION['level'] == "admin") {
                        $query = "SELECT * FROM permohonan_bantuan";
                    } elseif ($_SESSION['level'] == "warga") {
                        $query = "SELECT * FROM permohonan_bantuan WHERE id_user='$nama'";
                    }

                    $query = "SELECT permohonan_bantuan.*, user.nama
                    FROM permohonan_bantuan
                    JOIN user ON permohonan_bantuan.id_user = user.id_user";


                    $result = mysqli_query($koneksi, $query);
                    while ($row = mysqli_fetch_array($result)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?php echo $row['id_bantuan'] ?></td>
                            <td><?php echo $row['nama'] ?></td>
                            <td><?php echo $row['alamat'] ?></td>
                            <td><?php echo $row['kelurahan'] ?></td>
                            <td><?php echo $row['kecamatan'] ?></td>
                            <td><?php echo $row['no_telp'] ?></td>
                            <td><?php echo $row['jenis_bantuan'] ?></td>
                            <td><?php echo $row['deskripsi_bantuan'] ?></td>
                            <td><?= !empty($row['status_pemohon']) ? $row['status_pemohon'] : 'Pending'; ?></td> 
                            <td><?php echo $row['tgl_pengajuan'] ?></td>
                            <td><?php echo $row['tgl_persetujuan'] ?></td>
                            <td><?php echo $row['catatan_admin'] ?></td>
                            <td class="text-center">
                                <a href="permohonan_bantuan.php?hal=edit&id=<?php echo $row['id_bantuan'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="permohonan_bantuan.php?hal=hapus&id=<?php echo $row['id_bantuan'] ?>" onclick="return confirm('Apakah yakin ingin menghapus data ini?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
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