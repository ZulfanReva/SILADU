<?php
include "koneksi.php";
if (empty($_SESSION)) {
    session_start();
}


if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>SILADUMA | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/logos/favicon.png" />

    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        
        /* Dropdown Styles */
        .dropdown-content {
            display: none;
            background-color: #2c3136;
            padding-left: 20px;
        }

        .dropdown-content.active {
            display: block;
        }

        .dropdown-toggle {
            cursor: pointer;
            position: relative;
        }

        .dropdown-toggle::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 20px;
            transition: transform 0.3s;
        }

        .dropdown-toggle.active::after {
            transform: rotate(180deg);
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .sidebar.hide {
            transform: translateX(-250px);
        }

        .sidebar a {
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: flex;
            align-items: center;
            transition: background-color 0.3s, padding-left 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #495057;
            padding-left: 25px;
        }

        .sidebar h5 {
            color: #fff;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 18px;
            border-bottom: 1px solid #495057;
        }

        /* Tombol toggle sidebar untuk mobile */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #343a40;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 18px;
            cursor: pointer;
            z-index: 1100;
            border-radius: 5px;
        }

        /* Konten Utama */
        .content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
            background-color: #f8f9fa;
            overflow-y: auto;
            transition: margin-left 0.3s ease-in-out;
        }

        .content.expand {
            margin-left: 0;
        }

        /* Banner */
        .banner {
            background-image: url('image/bg_home.jpg');
            background-size: cover;
            background-position: center;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 20px;
            color: #FFFFFF;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .banner h4 {
            font-size: 30px;
            font-weight: bold;
            line-height: 1.3;
            letter-spacing: clamp(2px, 0.5vw, 5px);
        }

        /* Footer */
        footer {
            background-color: #343a40;
            color: white;
            padding: 10px;
            width: 100%;
            position: relative;
            bottom: 0;
            text-align: center;
        }

        footer a {
            color: #f8f9fa;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="home.php"><i class="fas fa-home"></i> Home</a>
        <h5>Master Data</h5>
        
        <!-- Pengaduan Dropdown -->
        <a href="#" class="dropdown-toggle" data-dropdown="pengaduan">
            <i class="fas fa-exclamation-circle"></i> Pengaduan
        </a>
        <div class="dropdown-content" id="pengaduan-dropdown">
            <a href="pengguna/pengaduan_alat_perikanan.php"><i class="fas fa-fish"></i> Alat Perikanan</a>
            <a href="pengguna/pengaduan_alat_pertanian.php"><i class="fas fa-seedling"></i> Alat Pertanian</a>
            <a href="pengguna/pengaduan_alat_ketahananpangan.php"><i class="fas fa-carrot"></i> Alat Ketahanan Pangan</a>
            <a href="pengguna/pengaduan_hama_penyakit_tanaman.php"><i class="fas fa-bug"></i> Hama & Penyakit Tanaman</a>
        </div>

        <!-- Permohonan Dropdown -->
        <a href="#" class="dropdown-toggle" data-dropdown="permohonan">
            <i class="fas fa-hand-holding-heart"></i> Permohonan
        </a>
        <div class="dropdown-content" id="permohonan-dropdown">
            <a href="pengguna/permohonan_bantuan.php"><i class="fas fa-hands-helping"></i> Bantuan</a>
            <a href="pengguna/permohonan_uji_tanaman.php"><i class="fas fa-flask"></i> Uji Tanaman</a>
            <a href="pengguna/permohonan_vaksinasi_hewan.php"><i class="fas fa-syringe"></i> Vaksinasi Hewan</a>
        </div>

        <!-- Pengajuan -->
        <a href="#" class="dropdown-toggle" data-dropdown="pengajuan">
            <i class="fas fa-file-signature"></i> Pengajuan
        </a>
        <div class="dropdown-content" id="pengajuan-dropdown">
            <a href="pengguna/pengajuan_izin.php"><i class="fas fa-file-alt"></i> Izin</a>
        </div>

        <!-- Survey -->
        <a href="#" class="dropdown-toggle" data-dropdown="survey">
            <i class="fas fa-poll"></i> Survey
        </a>
        <div class="dropdown-content" id="survey-dropdown">
            <a href="pengguna/survey_kepuasan.php"><i class="fas fa-smile"></i> Kepuasan Masyarakat</a>
        </div>

        <?php if ($_SESSION['level'] != 'warga') { ?>
            <h5>Laporan Pelayanan</h5>
            <a href="petugas/hasil_pengaduan_alat_ikan.php"><i class="fas fa-fish"></i> Laporan Pengaduan Alat Perikanan</a>
            <a href="petugas/hasil_pengaduan_alat_tani.php"><i class="fas fa-seedling"></i> Laporan Pengaduan Alat Pertanian</a>
            <a href="petugas/hasil_pengaduan_alat_pangan.php"><i class="fas fa-carrot"></i> Laporan Pengaduan Alat Ketahanan Pangan</a>
            <a href="petugas/hasil_permohonan_bantuan.php"><i class="fas fa-hand-holding-heart"></i> Laporan Permohonan Bantuan</a>
            <a href="petugas/hasil_permohonan_uji_tanaman.php"><i class="fas fa-hand-holding-heart"></i> Laporan Permohonan Uji Tanaman</a>
            <a href="petugas/hasil_permohonan_vaksinasi_hewan.php"><i class="fas fa-hand-holding-heart"></i> Laporan Permohonan Vaksinasi Hewan</a>
            <a href="petugas/hasil_pengaduan_hama_penyakit_tanaman.php"><i class="fas fa-seedling"></i> Laporan Pengaduan Hama Penyakit dan Tanaman</a>
            <a href="petugas/hasil_pengajuan_izin.php"><i class="fas fa-file-signature"></i> Laporan Pengajuan Izin</a>
            <a href="petugas/hasil_survey.php"><i class="fas fa-poll"></i> Laporan Survey</a>
            <a href="petugas/statistik.php"><i class="fas fa-poll"></i> Laporan Statistik</a>
            <a href="petugas/rekap_pengaduan.php"><i class="fas fa-book"></i> Rekap Pengaduan</a>
            <a href="petugas/rekap_pelayanan.php"><i class="fas fa-book"></i> Rekap Perizinan</a>
            <a href="petugas/rekap_bantuan.php"><i class="fas fa-book"></i> Rekap Bantuan</a>
        <?php } ?>

        <a href="#tentang"><i class="fas fa-info-circle"></i> Tentang</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>


    <!-- Content -->
    <div class="content" id="content">
        <!-- Banner -->
        <div class="banner">
            <div class="container text-center">
                <h4>Selamat Datang <br><span>Di Sistem Informasi Pelayanan Terpadu Untuk Perizinan Permohonan Bantuan dan Pengaduan Masyarakat Dinas Ketahanan Pangan Pertanian dan Perikanan di Kota Banjarmasin</span></h4>
            </div>
        </div>

        <!-- Layanan -->
        <div class="container-fluid layanan pt-5 pb-5">
            <div class="container text-center">
                <?php
                $level = $_SESSION['level'];
                echo "Hai! Kamu login sebagai $level.<br><br>";
                echo "Selamat datang di Aplikasi Sistem Informasi Pelayanan, Permohonan Izin, dan Pengaduan Masyarakat Dinas Ketahanan Pangan, Pertanian, dan Perikanan Kota Banjarmasin!  
                      Aplikasi ini dirancang untuk memudahkan Anda dalam mengakses layanan publik, menyampaikan pengaduan, serta mengajukan permohonan izin dengan cepat dan efisien. Kami berkomitmen memberikan layanan yang transparan, responsif, dan berbasis teknologi untuk mendukung kesejahteraan masyarakat Kota Banjarmasin.  
                      Bersama-sama, kita wujudkan pelayanan yang lebih baik, transparan, dan terjangkau!<br><br>";
                ?>
            </div>
        </div>

        <!-- Tentang -->
        <div class="container-fluid pt-5 pb-5" id="tentang">
            <div class="container">
                <h2 class="display-6 text-center fw-bold">Tentang</h2>
                <p class="text-center fw-bold">Sistem Informasi Pelayanan Terpadu untuk Perizinan Permohonan Bantuan dan Pengaduan Masyarakat</p>
                <div class="clearfix pt5">
                    <img src="image/aboutus.jpg" class="col-md-6 float-md-end mb-3 tentang crop-image" />
                    <p>
                        Sistem Informasi Pelayanan Terpadu untuk Perizinan Permohonan Bantuan dan Pengaduan Masyarakat atau disingkat <strong>SILADUMA</strong> merupakan wadah bagi masyarakat untuk menyampaikan aspirasi dan permintaannya terkait dengan pengaduan masyarakat dan pelayanan administrasi. <br>
                        <br> Tujuan adanya sistem informasi ini adalah agar warga Desa Kenanten bisa terbantu dalam pengurusan pengaduan dan administrasi sehingga tidak menggangu mobilitas masyarakat <br>
                        <br> Adapun pada laman ini, petugas desa juga terbantu dalam mengidentifikasi identitas warga, agar dalam pelayanannya dapat selesai dalam waktu yang cepat
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-dark text-white pt-4 pb-4">
            <div class="container text-center text-md-start">
                <div class="row text-center text-md-start">
                    <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-info">SILADUMA</h5>
                        <p>Sampaikan Laporanmu, Kami akan Melayanimu</p>
                    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle dropdown toggles
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const dropdownId = this.getAttribute('data-dropdown');
                    const dropdownContent = document.getElementById(dropdownId + '-dropdown');
                    
                    // Toggle active class for the clicked dropdown
                    this.classList.toggle('active');
                    dropdownContent.classList.toggle('active');
                    
                    // Close other dropdowns
                    dropdownToggles.forEach(otherToggle => {
                        if (otherToggle !== this) {
                            const otherId = otherToggle.getAttribute('data-dropdown');
                            const otherContent = document.getElementById(otherId + '-dropdown');
                            otherToggle.classList.remove('active');
                            otherContent.classList.remove('active');
                        }
                    });
                });
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown-toggle') && !e.target.closest('.dropdown-content')) {
                    dropdownToggles.forEach(toggle => {
                        const dropdownId = toggle.getAttribute('data-dropdown');
                        const dropdownContent = document.getElementById(dropdownId + '-dropdown');
                        toggle.classList.remove('active');
                        dropdownContent.classList.remove('active');
                    });
                }
            });
        });
    </script>

                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-info">Jenis Layanan</h5>
                        <p>
                            <a href="#" class="text-white" style="text-decoration:none;">Laporan Pengaduan</a>
                        </p>
                        <p>
                            <a href="#" class="text-white" style="text-decoration:none;">Permohonan Bantuan</a>
                        </p>
                        <p>
                            <a href="#" class="text-white" style="text-decoration:none;">Perizinan</a>
                        </p>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-info">Hubungi Kami</h5>
                        <p>
                            <i class="fas fa-home mr-3"></i> Jln. Lkr. Dalam Utara, Komplek Screen House, Kel. Benua Anyar, Kota Banjarmasin
                        </p>
                        <p>
                            <i class="fas fa-envelope mr-3"></i> distankan.bjm@gmail.com
                        </p>
                        <p>
                            <i class="fab fa-instagram mr-3"></i> @dkp3banjarmasinkota
                        </p>
                        <p>
                            <i class="fas fa-globe mr-3"></i> <a href="https://dkp3.banjarmasinkota.go.id/" target="_blank">https://dkp3.banjarmasinkota.go.id/</a>
                        </p>
                    </div>
                    <hr class="mb-3">
                    <div class="col-md-7 col-lg-8">
                        <p>Copyright ©2024 All-Rights Reserved by:
                            <strong class="text-warning">Hilda Nurfadilah</strong>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>