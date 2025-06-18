<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

// Variabel dari form (sesuaikan form input jika diperlukan)
$id_permohonan = $_POST['id_permohonan'];
$jenis_tanaman = $_POST['jenis_tanaman'];
$alamat_pemohon = $_POST['alamat_pemohon'];
$tgl_permohonan = date("Y-m-d");
$status = "Diajukan";  // Default status
$tgl_uji = $_POST['tgl_uji'];
$hasil_uji = $_POST['hasil_uji'];
$keterangan_petugas = $_POST['keterangan_petugas'];
$petugas = $_SESSION['nama'];

if (isset($_POST['simpan'])) {
    if (!empty($jenis_tanaman) && !empty($alamat_pemohon)) {
        $sql = "INSERT INTO permohonan_uji_tanaman (id_user, jenis_tanaman, alamat_pemohon, tgl_permohonan, status, tgl_uji, hasil_uji, keterangan_petugas) 
                VALUES ('{$_SESSION['id_user']}', '$jenis_tanaman', '$alamat_pemohon', '$tgl_permohonan', '$status', '$tgl_uji', '$hasil_uji', '$keterangan_petugas')";
        $a = $koneksi->query($sql);
        if ($a === true) {
            echo "<script>alert('Permohonan Uji Tanaman Berhasil Disimpan!');</script>";
            header("refresh:2;url=hasil_permohonan_uji_tanaman.php");
        } else {
            echo "<script>alert('Gagal Menyimpan Permohonan Uji Tanaman!');</script>";
            header("refresh:2;url=hasil_permohonan_uji_tanaman.php");
        }
    } else {
        echo "<script>alert('Ada Input yang Kosong!');</script>";
        echo "<script>history.back();</script>";
    }
}

if (isset($_GET['hal'])) {
    if ($_GET['hal'] == "hapus") {
        $hapus = mysqli_query($koneksi, "DELETE FROM permohonan_uji_tanaman WHERE id_permohonan='$_GET[id]'");
        if ($hapus) {
            echo "<script>alert('Hapus Data Sukses!'); location='hasil_permohonan_uji_tanaman.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data');</script>";
        }
    } elseif ($_GET['hal'] == "setuju") {
        $update = mysqli_query($koneksi, "UPDATE permohonan_uji_tanaman SET status='Diverifikasi' WHERE id_permohonan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Permohonan Diverifikasi!'); location='hasil_permohonan_uji_tanaman.php';</script>";
        } else {
            echo "<script>alert('Gagal Memverifikasi!');</script>";
        }
    } elseif ($_GET['hal'] == "jadwalkan") {
        $update = mysqli_query($koneksi, "UPDATE permohonan_uji_tanaman SET status='Dijadwalkan' WHERE id_permohonan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Pengujian Dijadwalkan!'); location='hasil_permohonan_uji_tanaman.php';</script>";
        } else {
            echo "<script>alert('Gagal Menjadwalkan!');</script>";
        }
    } elseif ($_GET['hal'] == "selesai") {
        $update = mysqli_query($koneksi, "UPDATE permohonan_uji_tanaman SET status='Selesai' WHERE id_permohonan='$_GET[id]'");
        if ($update) {
            echo "<script>alert('Pengujian Selesai!'); location='hasil_permohonan_uji_tanaman.php';</script>";
        } else {
            echo "<script>alert('Gagal Mengubah Status!');</script>";
        }
    }
}

// Proses update keterangan petugas jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_keterangan'])) {
    $id_permohonan = $_POST['id_permohonan'];
    $keterangan_petugas = mysqli_real_escape_string($koneksi, $_POST['keterangan_petugas']);

    $query = "UPDATE permohonan_uji_tanaman SET keterangan_petugas='$keterangan_petugas' WHERE id_permohonan='$id_permohonan'";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
            alert('Keterangan berhasil disimpan!');
            window.location.href='hasil_permohonan_uji_tanaman.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menyimpan keterangan!');
            window.history.back();
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Permohonan Uji Tanaman</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .text-center {
            text-align: center;
        }

        .cetak-btn {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        h1 {
            margin-top: 80px;
            text-align: center;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .hasil {
            width: 95%;
        }

        .navbar-brand {
            font-weight: bold;
            color: #cdc2ae;
        }

        @media print {
            .btn-success,
            .btn-danger,
            .btn-warning,
            th:nth-child(10),
            td:nth-child(10) {
                display: none;
            }

            body {
                font-size: 12px;
                color: black;
            }

            table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-lg fixed-top" style="background-color: #68A7AD;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../home.php">S I L A D U M A</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
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
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo htmlspecialchars($username); ?>
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

    <h1>Form Permohonan Uji Tanaman</h1>
    <div class="box box-primary">
        <div class="box-header with-border text-center">
            <button type="button" class="btn btn-success cetak-btn" onclick="printReport()">
                Cetak Laporan
            </button>
        </div>

        <div class="container-fluid hasil">
            <div class="card-wrap mt-4">
                <div class="card-body">
                    <!-- Form Pencarian -->
                    <form method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari berdasarkan jenis tanaman atau alamat pemohon..."
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>

                    <table class="table table-bordered table-striped">
                        <tr class="text-center">
                            <th>No.</th>
                            <th>Jenis Tanaman</th>
                            <th>Alamat Pemohon</th>
                            <th>Tanggal Permohonan</th>
                            <th>Status</th>
                            <th>Tanggal Uji</th>
                            <th>Hasil Uji</th>
                            <th>Keterangan Petugas</th>
                            <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'petugas') { ?>
                                <th>Aksi</th>
                            <?php } ?>
                        </tr>

                        <?php
                        $no = 1;
                        $search = isset($_GET['search']) ? trim(mysqli_real_escape_string($koneksi, $_GET['search'])) : '';

                        if ($_SESSION['level'] == "petugas" || $_SESSION['level'] == "admin") {
                            $query = "SELECT permohonan_uji_tanaman.*, user.nama 
                                      FROM permohonan_uji_tanaman
                                      JOIN user ON permohonan_uji_tanaman.id_user = user.id_user";
                        } elseif ($_SESSION['level'] == "warga") {
                            $query = "SELECT permohonan_uji_tanaman.*, user.nama 
                                      FROM permohonan_uji_tanaman
                                      JOIN user ON permohonan_uji_tanaman.id_user = user.id_user
                                      WHERE user.nama = '{$_SESSION['nama']}'";
                        }

                        if (!empty($search)) {
                            if (strpos($query, "WHERE") !== false) {
                                $query .= " AND (permohonan_uji_tanaman.jenis_tanaman LIKE '%$search%' OR permohonan_uji_tanaman.alamat_pemohon LIKE '%$search%')";
                            } else {
                                $query .= " WHERE (permohonan_uji_tanaman.jenis_tanaman LIKE '%$search%' OR permohonan_uji_tanaman.alamat_pemohon LIKE '%$search%')";
                            }
                        }

                        $result = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($result)) :
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['jenis_tanaman']) ?></td>
                                <td><?= htmlspecialchars($row['alamat_pemohon']) ?></td>
                                <td><?= htmlspecialchars($row['tgl_permohonan']) ?></td>
                                <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                                <td><?= !empty($row['tgl_uji']) ? htmlspecialchars($row['tgl_uji']) : '-' ?></td>
                                <td><?= !empty($row['hasil_uji']) ? htmlspecialchars($row['hasil_uji']) : '-' ?></td>
                                <td><?= !empty($row['keterangan_petugas']) ? htmlspecialchars($row['keterangan_petugas']) : '-' ?></td>

                                <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'petugas') { ?>
                                    <td class="text-center">
                                        <a href="ed_permohonan_uji_tanaman.php?hal=edit&id=<?= $row['id_permohonan'] ?>"
                                            class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>

                                        <a href="hasil_permohonan_uji_tanaman.php?hal=hapus&id=<?= $row['id_permohonan'] ?>"
                                            onclick="return confirm('Apakah yakin ingin menghapus data ini?')"
                                            class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </a>

                                        <a href="hasil_permohonan_uji_tanaman.php?hal=setuju&id=<?= $row['id_permohonan'] ?>"
                                            onclick="return confirm('Setujui permohonan ini?')"
                                            class="btn btn-success btn-sm" title="Setujui">
                                            <i class="fa-solid fa-check"></i> Setuju
                                        </a>

                                        <a href="hasil_permohonan_uji_tanaman.php?hal=jadwalkan&id=<?= $row['id_permohonan'] ?>"
                                            onclick="return confirm('Jadwalkan pengujian?')"
                                            class="btn btn-primary btn-sm" title="Jadwalkan">
                                            <i class="fa-solid fa-calendar"></i> Jadwalkan
                                        </a>

                                        <a href="hasil_permohonan_uji_tanaman.php?hal=selesai&id=<?= $row['id_permohonan'] ?>"
                                            onclick="return confirm('Tandai pengujian selesai?')"
                                            class="btn btn-secondary btn-sm" title="Selesai">
                                            <i class="fa-solid fa-check-double"></i> Selesai
                                        </a>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>

        <script>
            function printReport() {
                window.print();
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
