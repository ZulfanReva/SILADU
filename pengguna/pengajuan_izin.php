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
$jenis_izin = $_POST['jenis_izin'] ?? '';
$file_name = $_FILES['dokumen']['name'] ?? '';
$kontak = $_POST['kontak'] ?? '';
$tgl_pengajuan = date('Y-m-d'); // Set tanggal pengajuan ke hari ini
$status_pemohon = 'Pending'; // Set default status pemohon
$catatan_admin = ''; // Kosongkan catatan admin


if (isset($_POST['simpan'])) {
    if (!empty($jenis_izin)  && !empty($kontak)) {
        $ekstensi_boleh = array('pdf');
        $x = explode('.', $_FILES['dokumen']['name']);
        $ekstensi = strtolower(end($x));
        $file_name = time() . '_' . $_FILES['dokumen']['name'];
        $direktori = "../petugas/uploads/pengajuan_izin/";

        // Buat folder jika belum ada
        if (!file_exists($direktori)) {
            mkdir($direktori, 0777, true);
        }

        if (in_array($ekstensi, $ekstensi_boleh)) {
            if ($_GET['hal'] == "edit") {
                // Ambil nama file lama sebelum diperbarui
                $query_old = "SELECT dokumen FROM pengajuan_izin WHERE id_pengajuan = '$kode'";
                $result_old = mysqli_query($koneksi, $query_old);
                $data_old = mysqli_fetch_assoc($result_old);
                $old_file = $data_old['dokumen'];

                // Update data dengan file baru
                $query = "UPDATE pengajuan_izin SET
                            jenis_izin = '$jenis_izin',
                            dokumen = '$file_name',
                            kontak = '$kontak',
                            tgl_pengajuan = '$tgl_pengajuan'
                          WHERE id_pengajuan = '$kode'";
                $edit = mysqli_query($koneksi, $query);

                if ($edit) {
                    // Hapus file lama jika ada
                    if (!empty($old_file) && file_exists($direktori . $old_file)) {
                        unlink($direktori . $old_file);
                    }

                    // Pindahkan file baru ke folder
                    move_uploaded_file($_FILES['dokumen']['tmp_name'], $direktori . $file_name);
                    echo "<script>
                    alert('Berhasil Memperbarui Permohonan!');
                    window.location.href = 'pengajuan_izin.php';
                </script>";
                } else {
                    echo "<script>alert('Edit Data Gagal!'); history.back();</script>";

                }
            } else {
                $query = "SELECT id_user FROM user WHERE username = '$username'";
                $result = $koneksi->query($query);
                $row = $result->fetch_assoc();
                $id_user = $row['id_user']; 
                $sql = "INSERT INTO pengajuan_izin (id_user, jenis_izin, dokumen, kontak, tgl_pengajuan, status_pemohon, catatan_admin)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($koneksi, $sql);
                mysqli_stmt_bind_param($stmt, "sssssss", $id_user, $jenis_izin, $file_name, $kontak, $tgl_pengajuan, $status_pemohon, $catatan_admin);

                $insert_hasil = mysqli_stmt_execute($stmt);

                if ($insert_hasil) {
                    // Pindahkan file setelah data tersimpan
                    move_uploaded_file($_FILES['dokumen']['tmp_name'], $direktori . $file_name);

                    echo "<script>
                        alert('Berhasil Mengirim pengajuan!');
                        window.location.href = 'pengajuan_izin.php?status=sukses';
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
        echo "<script>location('pengajuan_izin.php');</script>";
    }
}


if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "edit") {
        $b = mysqli_query($koneksi, "SELECT * FROM pengajuan_izin WHERE id_pengajuan='$_GET[id]'");
        $data = mysqli_fetch_array($b);
        if ($data) {
            $jenis_izin = $data['jenis_izin'];
            $dokumen = $data['dokumen'];
            $kontak = $data['kontak'];
            $tgl_pengajuan = $data['tgl_pengajuan'];
        }
    } elseif ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM pengajuan_izin WHERE id_pengajuan='$_GET[id]'");
        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Sukses!');
                    location='pengajuan_izin.php';
                  </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pengajuan Izin</title>
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

    <h1>Pengajuan Izin</h1>
    <div class="container-fluid administrasi">
        <div class="card-wrap mt-5">
            <div class="card administrasi-form">
                <div class="card-header form-peng">
                    <h2 class="card-title text-center">Form Pengajuan Izin</h2>
                </div>
                <div class="card-body">
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label for="disabledTextInput" class="col-sm-3 col-form-label text-start">ID Pengajuan</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="id" value="<?= $data['id_pengajuan'] ?? ''; ?>" placeholder="Otomatis Oleh Sistem" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Nama Pemohon</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="username" value="<?= $nama; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Jenis Izin</label>
                            <div class="col-sm-9">
                                <select name="jenis_izin" class="form-control" id="jenis_izin">
                                    <option value="">Pilih Jenis Izin</option>
                                    <option value="Izin Usaha Budidaya Tanaman Pangan dan Hortikultura" <?php echo ($jenis_izin == 'Izin Usaha Budidaya Tanaman Pangan dan Hortikultura') ? 'selected' : ''; ?>>Izin Usaha Budidaya Tanaman Pangan dan Hortikultura</option>
                                    <option value="Izin Usaha Perkebunan" <?php echo ($jenis_izin == 'Izin Usaha Perkebunan') ? 'selected' : ''; ?>>Izin Usaha Perkebunan</option>
                                    <option value="Izin Usaha Peternakan dan Kesehatan Hewan" <?php echo ($jenis_izin == 'Izin Usaha Peternakan dan Kesehatan Hewan') ? 'selected' : ''; ?>>Izin Usaha Peternakan dan Kesehatan Hewan</option>
                                    <option value="Izin Usaha Perikanan" <?php echo ($jenis_izin == 'Izin Usaha Perikanan') ? 'selected' : ''; ?>>Izin Usaha Perikanan</option>
                                    <option value="Izin Distribusi dan Keamanan Pangan" <?php echo ($jenis_izin == 'Izin Distribusi dan Keamanan Pangan') ? 'selected' : ''; ?>>Izin Distribusi dan Keamanan Pangan</option>
                                    <option value="Izin Penggunaan Sarana dan Prasarana Pertanian dan Perikanan" <?php echo ($jenis_izin == 'Izin Penggunaan Sarana dan Prasarana Pertanian dan Perikanan') ? 'selected' : ''; ?>>Izin Penggunaan Sarana dan Prasarana Pertanian dan Perikanan</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Dokumen</label>
                            <div class="col-sm-9">
                                <?php if (!empty($dokumen)) : ?>
                                    <div class="mb-2">
                                        <span class="text-primary">File saat ini: <?= basename($dokumen); ?></span>
                                    </div>
                                <?php endif; ?>
                                <input class="form-control" type="file" name="dokumen" accept="application/pdf">
                                <input type="hidden" name="dokumen_lama" value="<?= $dokumen; ?>">
                                <small class="text-danger">Format hanya PDF</small>
                            </div>

                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Kontak</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kontak" value="<?= $kontak; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Tgl. Pengajuan</label>
                            <div class=" col-sm-9">
                                <input type="date" class="form-control" name="tgl_pengajuan" value="<?= isset($tgl_pengajuan) ? $tgl_pengajuan : date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label text-start">Status Pemohon</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="status_pemohon" value="<?= $status_pemohon; ?>" disabled>
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
            <h3 class="card-title text-center">Daftar Pengajuan Izin</h3>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr class="text-center">
                        <th>No.</th>
                        <th scope="col">ID Pengajuan Izin</th>
                        <th scope="col">Nama Pemohon</th>
                        <th scope="col">Jenis Izin</th>
                        <th scope="col">Dokumen</th>
                        <th scope="col">Kontak</th>
                        <th scope="col">Tgl Pengajuan</th>
                        <th scope="col">Status Pemohon</th>
                        <th scope="col">Catatan Admin</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    <?php
                    $no = 1;
                    if ($_SESSION['level'] == "petugas" || $_SESSION['level'] == "admin") {
                        $query = "SELECT * FROM pengajuan_izin";
                    } elseif ($_SESSION['level'] == "warga") {
                        $query = "SELECT * FROM pengajuan_izin WHERE nama_pemohon='$nama'";
                    }

                    $query = "SELECT pengajuan_izin.*, user.nama
                    FROM pengajuan_izin
                    JOIN user ON pengajuan_izin.id_user = user.id_user";
                    $result = mysqli_query($koneksi, $query);
                    while ($row = mysqli_fetch_array($result)) :
                        $dokumenPath = "../petugas/uploads/pengajuan_izin/" . $row['dokumen'];
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?php echo $row['id_pengajuan'] ?></td>
                            <td><?php echo $row['nama'] ?></td>
                            <td><?php echo $row['jenis_izin'] ?></td>
                            <td>
                                <?php if (!empty($row['dokumen']) && file_exists($dokumenPath)) : ?>
                                    <a href="<?php echo $dokumenPath; ?>" target="_blank" download><?php echo basename($dokumenPath); ?></a>
                                <?php else : ?>
                                    <span class="text-danger">Dokumen tidak ditemukan</span>
                                <?php endif; ?>
                            </td>

                            <td><?php echo $row['kontak'] ?></td>
                            <td><?php echo $row['tgl_pengajuan'] ?></td>
                            <td><?= !empty($row['status_pemohon']) ? $row['status_pemohon'] : 'Pending'; ?></td> 
                            <td><?php echo $row['catatan_admin'] ?></td>
                            <td class="text-center">
                                <a href="pengajuan_izin.php?hal=edit&id=<?php echo $row['id_pengajuan'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="pengajuan_izin.php?hal=hapus&id=<?php echo $row['id_pengajuan'] ?>" onclick="return confirm('Apakah yakin ingin menghapus data ini?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
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