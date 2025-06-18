<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    // $nama_petugas = $_SESSION['nama_pengguna'];
    $username = $_SESSION['username'];
}

// Query untuk mengambil data survey
$sql = "SELECT survey_kepuasan.*, user.nama
        FROM survey_kepuasan
        JOIN user ON survey_kepuasan.id_user = user.id_user
        ORDER BY tgl_pengisian DESC";
$result = $koneksi->query($sql);

// Query untuk menghitung rata-rata skor penilaian dan jumlah ulasan
$sql_avg = "SELECT AVG(skor_penilaian) as avg_score, COUNT(*) as total_reviews FROM survey_kepuasan";
$avg_result = $koneksi->query($sql_avg);
$avg_data = $avg_result->fetch_assoc();

$average_score = round($avg_data['avg_score'], 1); // Rata-rata skor dibulatkan 1 desimal
$total_reviews = $avg_data['total_reviews'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Data Survey Kepuasan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        h1 {
            margin-top: 80px;
            text-align: center;
        }

        .container {
            padding-top: 80px;
        }

        .rating {
            font-size: 24px;
            color: #ffcc00; /* Warna bintang aktif */
        }
    </style>
</head>

<body>
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
                                <?php echo "$username"; ?>
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

    <div class="container">
        <h1>Data Survey Kepuasan Pengguna</h1>
        
        <div class="text-center mb-4">
            <div class="rating">
                <span><?php echo $average_score; ?>/5</span>
            </div>
            <p>Total Ulasan: <?php echo $total_reviews; ?></p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Tanggal Pengisian</th>
                        <th>Skor Penilaian</th>
                        <th>Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['nama']}</td>
                                    <td>{$row['tgl_pengisian']}</td>
                                    <td>{$row['skor_penilaian']}</td>
                                    <td>{$row['komentar']}</td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Tidak ada data survey</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>