<?php
session_start();
include "../koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!isset($_SESSION['username'])) {
    die("Anda belum login, klik <a href=\"../index.php\">disini</a> untuk login");
} else {
    $username = $_SESSION['username'];
}

$tanggal = date('Y-m-d'); // mendapatkan tanggal pengisian survey
$skor_penilaian = $_POST['skor_penilaian'] ?? '';
$komentar = $_POST['komentar'] ?? '';

// Cek apakah user sudah mengisi survey
$query_check = "SELECT * FROM survey_kepuasan WHERE id_user = (SELECT id_user FROM user WHERE username = '$username')";
$result_check = $koneksi->query($query_check);

if ($result_check->num_rows > 0) {
    echo "<script>alert('Anda sudah mengisi survey sebelumnya!');</script>";
    header("refresh:2;url=survey_kepuasan.php");
    exit();
}

if (isset($_POST['simpan'])) {
    if (empty($skor_penilaian) || empty($komentar)) {
        echo "<script>alert('Ada Input yang Kosong!');</script>";
        echo "<script>location.href='survey_kepuasan.php';</script>";
    } else {
        // Query untuk mendapatkan id_user berdasarkan username
        $query = "SELECT id_user FROM user WHERE username = '$username'";
        $result = $koneksi->query($query);
        $row = $result->fetch_assoc();
        $id_user = $row['id_user']; 
        
        // Query untuk menyimpan survey dengan id_user
        $sql = "INSERT INTO survey_kepuasan (id_user, tgl_pengisian, skor_penilaian, komentar)
                VALUES ('" . $id_user . "', '" . $tanggal . "', '" . $skor_penilaian . "', '" . $komentar . "')";
        
        $a = $koneksi->query($sql);
        
        if ($a === TRUE) {
            echo "<script>alert('Survey Kepuasan Berhasil Disimpan!');</script>";
            header("refresh:2;url=survey_kepuasan.php");
        } else {
            echo "<script>alert('Gagal Menyimpan Survey!');</script>";
            header("refresh:2;url=survey_kepuasan.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Survey Kepuasan Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        h1 {
            margin-top: 80px;
            text-align: center;
        }

        .star-rating {
            cursor: pointer;
            font-size: 30px;
        }

        .star-rating .fa {
            color: #d3d3d3; /* Warna bintang tidak aktif */
        }

        .star-rating .fa.checked {
            color: #ffcc00; /* Warna bintang aktif */
        }

        .container {
            padding-top: 80px;
        }

        .card-wrap {
            max-width: 600px;
            margin: auto;
            padding: 20px;
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

        .rating-description {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
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
                    <li class="nav-item">
                        <a class="nav-link" href="../petugas/aturan_layanan.php">Layanan</a>
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

    <div class="container-fluid kinerja">
        <h1>Survey Kepuasan Pengguna</h1>
        <div class="card-wrap">
            <div class="card request-form">
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        Berikan review dan penilaian terkait layanan kami agar kami bisa meningkatkan kinerja kami di masa depan.
                    </div>
                    <form action="" method="POST">
                        <?php
                        $a = mysqli_query($koneksi, "select * from user where username='$_SESSION[username]'");
                        $tampil = mysqli_fetch_array($a);
                        ?>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username" value="<?= $tampil['nama'] ?>" disabled>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Tanggal Pengisian</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="tgl_pengisian" value="<?= $tanggal ?>" readonly />
                            </div>
                        </div>

                        <!-- Rating Bintang -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Skor Penilaian</label>
                            <div class="col-sm-9">
                                <div class="star-rating">
                                    <span class="fa fa-star" data-index="1"></span>
                                    <span class="fa fa-star" data-index="2"></span>
                                    <span class="fa fa-star" data-index="3"></span>
                                    <span class="fa fa-star" data-index="4"></span>
                                    <span class="fa fa-star" data-index="5"></span>
                                </div>
                                <input type="hidden" name="skor_penilaian" id="rating" required />
                                <div class="rating-description" id="rating-description">Belum dinilai</div>
                            </div>
                        </div>

                        <!-- Komentar -->
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Komentar</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="komentar" cols="30" rows="5" required></textarea>
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
    </div>

    <script>
        // Menangani Klik pada Bintang
        const stars = document.querySelectorAll('.star-rating .fa');
        const ratingDescription = document.getElementById('rating-description');
        let rating = 0;

        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                rating = index + 1; // Menetapkan nilai rating berdasarkan bintang yang diklik
                document.getElementById('rating').value = rating; // Mengisi input tersembunyi dengan rating
                updateStars(); // Memperbarui tampilan bintang
                updateRatingDescription(); // Memperbarui deskripsi rating
            });
        });

        // Memperbarui tampilan bintang
        function updateStars() {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('checked');
                } else {
                    star.classList.remove('checked');
                }
            });
        }

        // Memperbarui deskripsi rating
        function updateRatingDescription() {
            switch (rating) {
                case 1:
                    ratingDescription.textContent = "Sangat Buruk";
                    break;
                case 2:
                    ratingDescription.textContent = "Buruk";
                    break;
                case 3:
                    ratingDescription.textContent = "Cukup";
                    break;
                case 4:
                    ratingDescription.textContent = "Baik";
                    break;
                case 5:
                    ratingDescription.textContent = "Sangat Baik";
                    break;
                default:
                    ratingDescription.textContent = "Belum dinilai";
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>