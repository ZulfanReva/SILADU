<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "siladu2");

// Cek koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari tabel user berdasarkan id_user (misalnya dari sesi)
session_start();
$id_user = $_SESSION['id_user']; // Asumsikan id_user disimpan di sesi

$sql = "SELECT nama, level FROM user WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $nama = $row['nama'];
  $level = $row['level'];
} else {
  $nama = "Pengguna";
  $level = "Tidak Diketahui";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <!-- <link rel="icon" type="image/png" href="../assets/img/favicon.png"> -->
  <link rel="icon" href="../../assets/images/logos/favicon.png" type="image/png">
  <title>
    SILADUMA | Dashboard
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />
  <!-- <link id="pagestyle" href="../../output.css" rel="stylesheet" /> -->
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.html " target="_blank">
        <img src="../../assets/images/logos/logodinasbjm.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">SILADUMA</span>
      </a>
    </div>

    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Utama</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link active" href="../pages/dashboard.html">
            <div class="icon icon-shape bg-gradient-green icon-md shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/dashboard.png" alt="Dashboard Icon" width="50" height="50">
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pengaduan</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/hamadanpenyakit.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Hama & Penyakit</span>
          </a>
        </li>


        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Permohonan</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/ikan.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Perikanan</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/petani.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Pertanian</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/ketahananpangan.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Ketahanan Pangan</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/bantuan.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Bantuan</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/ujitanaman.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Uji Tanaman</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/vaksinasihewan.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Vaksinasi Hewan</span>
          </a>
        </li>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pengajuan</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/izin.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Izin </span>
          </a>
        </li>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Survei</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/surveikepuasan.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Survei Kepuasaan</span>
          </a>
        </li>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Akun</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div
              class="bg-gradient-green icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <img src="../assets/img/profil.png" alt="Dashboard" width="30" height="30">
            </div>
            <span class="nav-link-text ms-1">Profil</span>
          </a>
        </li>

      </ul>
    </div>

    <hr class="horizontal dark mt-0">

    <div class="sidenav-footer mx-3 ">
      <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
        <div class="full-background" style="background-image: url('../assets/img/curved-images/white-curved.jpg')"></div>
        <div class="card-body text-start p-3 w-100">
          <div class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
            <i class="bi bi-telephone-fill text-lg top-0" style="color: #6aac51;" aria-hidden="true" id="sidenavCardIcon"></i>
          </div>
          <div class="docs-info">
            <h6 class="text-white up mb-0">Butuh Bantuan?</h6>
            <p class="text-xs font-weight-bold">Silahkan hubungi admin</p>
            <a href="#" target="_blank" class="btn btn-white btn-sm w-100 mb-0 text-weserve-green">WhatsApp</a>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <div class="collapse navbar-collapse mt-sm-0 mt-2" id="navbar">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item d-flex align-items-center">
              <a class="btn btn-outline-green btn-sm mb-0 me-3" href="../../logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <!-- PENGADUAN -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pengaduan</p>
                    <h5 class="font-weight-bolder mb-0">
                      $53,000
                      <span class="text-success text-sm font-weight-bolder">+55%</span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-green shadow text-center border-radius-md">
                    <i class="bi bi-headset text-lg opacity-10" aria-hidden="true"></i>
                    <!-- Bootstrap icon: Headset -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- PERMOHONAN -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Permohonan</p>
                    <h5 class="font-weight-bolder mb-0">
                      2,300
                      <span class="text-success text-sm font-weight-bolder">+3%</span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-green shadow text-center border-radius-md">
                    <i class="bi bi-send text-lg opacity-10" aria-hidden="true"></i>
                    <!-- Bootstrap icon: Handshake -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- PENGAJUAN -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pengajuan</p>
                    <h5 class="font-weight-bolder mb-0">
                      +3,462
                      <span class="text-danger text-sm font-weight-bolder">-2%</span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-green shadow text-center border-radius-md">
                    <i class="bi bi-file-earmark-text  text-lg opacity-10" aria-hidden="true"></i>
                    <!-- Bootstrap icon: Dokumen -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SURVEY -->
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Survey</p>
                    <h5 class="font-weight-bolder mb-0">
                      $103,430
                      <span class="text-success text-sm font-weight-bolder">+5%</span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-green shadow text-center border-radius-md">
                    <i class="bi bi-star text-lg opacity-10" aria-hidden="true"></i>
                    <!-- Bootstrap icon: Formulir/survey -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-7">
          <!-- Welcome Card -->
          <div class="card mb-4">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-lg-7">
                  <div class="d-flex flex-column h-100">
                    <p class="mb-1 pt-2 text-bold">Dashboard <?php echo htmlspecialchars($level); ?></p>
                    <h5 class="font-weight-bolder">Selamat Datang <?php echo htmlspecialchars($nama); ?>!</h5>
                    <p class="mb-5" style="text-align: justify;">
                      Jelajahi fitur dan kelola aktivitas Anda di sini dengan mudah dari perizinan, bantuan,
                      hingga pengaduan masyarakat dengan cepat dan Efektif.
                    </p>
                    <a href="/dashboard/pages/survey.php" class="text-sm text-weserve-green text-decoration-none">Beri rating disini</a>
                  </div>
                </div>
                <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
                  <div class="border-radius-xl h-100">
                    <div class="position-relative d-flex align-items-center justify-content h-100">
                      <img class="w-100 position-relative" src="../assets/img/illustrations/1.png" alt="rocket">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Data Layanan Card -->
          <div class="card">
            <div class="card-header pb-0">
              <div class="row align-items-center">
                <div class="col-lg-6 col-7">
                  <div class="d-flex align-items-center mb-2">
                    <div class="icon-dashboard">
                      <i class="bi bi-file-earmark-text"></i>
                    </div>

                    <style>
                      .icon-dashboard {
                        width: 40px;
                        height: 40px;
                        background: linear-gradient(310deg, #4e7a3a, #8cd968);
                        border-radius: 0.5rem;
                        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
                        margin-right: 10px;

                        display: flex;
                        justify-content: center;
                        align-items: center;
                      }

                      .icon-dashboard i {
                        font-size: 20px;
                        color: white;
                        line-height: 1;
                        display: inline-block;
                        transform: translateY(1px);
                        /* tweak jika masih terlihat turun */
                      }
                    </style>

                    <h6 class="text-sm mb-0">Data Layanan</h6>
                  </div>
                  <p class="text-sm mb-0">
                    <i class="fa fa-check text-success" aria-hidden="true"></i>
                    <span class="font-weight-bold ms-1">30+ layanan selesai</span> bulan ini
                  </p>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kategori</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sub Kategori</th>
                      <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pengaduan</th>
                      <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Ahmad Yani</h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Pengaduan</span>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Hama dan Penyakit</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold">2025-06-10</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="badge badge-sm bg-gradient-success">Diterima</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Siti Aminah</h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Permohonan</span>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Alat Pertanian</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold">2025-06-12</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="badge badge-sm bg-gradient-warning">Direview</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Budi Santoso</h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Pengajuan</span>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Izin</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold">2025-06-15</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="badge badge-sm bg-gradient-success">Diterima</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Rina Sari</h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Survey</span>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Uji Tanaman</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold">2025-06-16</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="badge badge-sm bg-gradient-secondary">Diproses</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Dedi Pratama</h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Permohonan</span>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Bantuan</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold">2025-06-17</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="badge badge-sm bg-gradient-warning">Direview</span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Lina Wijaya</h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Pengajuan</span>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold">Vaksinasi Hewan</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold">2025-06-17</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="badge badge-sm bg-gradient-danger">Ditolak</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistik Layanan Warga Card -->
        <div class="col-lg-5">
          <div class="card h-100 p-3">
            <h5 class="font-weight-bolder pt-2">Statistik Layanan Warga</h5>

            <!-- Controls -->
            <div class="controls" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 1rem;">
              <div style="flex: 1;">
                <label for="yearSelect" class="" style="font-size: 0.85rem;">Tahun:</label>
                <select id="yearSelect" class="form-select" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: 1.8rem; transition: border-color 0.2s ease-in-out;">
                  <option value="2023">2023</option>
                  <option value="2024" selected>2024</option>
                  <option value="2025">2025</option>
                </select>
              </div>
              <div style="flex: 1;">
                <label for="categorySelect" class="" style="font-size: 0.85rem;">Kategori:</label>
                <select id="categorySelect" class="form-select" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: 1.8rem; transition: border-color 0.2s ease-in-out;">
                  <option value="all">Semua Kategori</option>
                  <option value="pengaduan">Pengaduan</option>
                  <option value="permohonanan">Permohonanan</option>
                  <option value="pengajuan">Pengajuan</option>
                  <option value="survey">Survey</option>
                </select>
              </div>
            </div>
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100" style="background-image: url('../assets/img/thumbnail-chart.png');">
              <span class="mask"></span>
              <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                <!-- Chart Container -->
                <div class="chart-container">
                  <canvas id="wargaChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <script>
          // Data dummy berdasarkan struktur dari gambar
          const chartData = {
            2023: {
              all: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Hama dan Penyakit',
                    data: [65, 59, 80, 81, 56, 55, 70, 65, 75, 85, 90, 95],
                    borderColor: '#e91e63',
                    backgroundColor: 'rgba(233, 30, 99, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Perikanan',
                    data: [45, 49, 60, 71, 46, 45, 50, 55, 65, 75, 80, 85],
                    borderColor: '#9c27b0',
                    backgroundColor: 'rgba(156, 39, 176, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Pertanian',
                    data: [35, 39, 50, 61, 36, 35, 40, 45, 55, 65, 70, 75],
                    borderColor: '#9966FF',
                    backgroundColor: 'rgba(103, 58, 183, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Ketahanan Pangan',
                    data: [25, 29, 40, 51, 26, 25, 30, 35, 45, 55, 60, 65],
                    borderColor: '#FFCD56',
                    backgroundColor: 'rgba(63, 81, 181, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Bantuan',
                    data: [55, 69, 70, 61, 66, 75, 80, 75, 85, 95, 100, 105],
                    borderColor: '#2196f3',
                    backgroundColor: 'rgba(33, 150, 243, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Uji Tanaman',
                    data: [15, 19, 30, 41, 16, 15, 20, 25, 35, 45, 50, 55],
                    borderColor: '#00ACC1',
                    backgroundColor: 'rgba(3, 169, 244, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Vaksinasi Hewan',
                    data: [40, 44, 55, 66, 41, 40, 45, 50, 60, 70, 75, 80],
                    borderColor: '#FF6B6B',
                    backgroundColor: 'rgba(0, 188, 212, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Izin',
                    data: [30, 34, 45, 56, 31, 30, 35, 40, 50, 60, 65, 70],
                    borderColor: '#4bc0c0',
                    backgroundColor: 'rgba(0, 150, 136, 0.1)',
                    tension: 0.4
                  }
                ]
              }
            },
            2024: {
              all: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Hama dan Penyakit',
                    data: [75, 69, 90, 91, 66, 65, 80, 75, 85, 95, 100, 105],
                    borderColor: '#e91e63',
                    backgroundColor: 'rgba(233, 30, 99, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Perikanan',
                    data: [55, 59, 70, 81, 56, 55, 60, 65, 75, 85, 90, 95],
                    borderColor: '#9c27b0',
                    backgroundColor: 'rgba(156, 39, 176, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Pertanian',
                    data: [45, 49, 60, 71, 46, 45, 50, 55, 65, 75, 80, 85],
                    borderColor: '#9966FF',
                    backgroundColor: 'rgba(103, 58, 183, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Ketahanan Pangan',
                    data: [35, 39, 50, 61, 36, 35, 40, 45, 55, 65, 70, 75],
                    borderColor: '#FFCD56',
                    backgroundColor: 'rgba(63, 81, 181, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Bantuan',
                    data: [65, 79, 80, 71, 76, 85, 90, 85, 95, 105, 110, 115],
                    borderColor: '#2196f3',
                    backgroundColor: 'rgba(33, 150, 243, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Uji Tanaman',
                    data: [25, 29, 40, 51, 26, 25, 30, 35, 45, 55, 60, 65],
                    borderColor: '#00ACC1',
                    backgroundColor: 'rgba(3, 169, 244, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Vaksinasi Hewan',
                    data: [50, 54, 65, 76, 51, 50, 55, 60, 70, 80, 85, 90],
                    borderColor: '#FF6B6B',
                    backgroundColor: 'rgba(0, 188, 212, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Izin',
                    data: [40, 44, 55, 66, 41, 40, 45, 50, 60, 70, 75, 80],
                    borderColor: '#4bc0c0',
                    backgroundColor: 'rgba(0, 150, 136, 0.1)',
                    tension: 0.4
                  }
                ]
              },
              pengaduan: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                  label: 'Hama dan Penyakit',
                  data: [75, 69, 90, 91, 66, 65, 80, 75, 85, 95, 100, 105],
                  borderColor: '#e91e63',
                  backgroundColor: 'rgba(233, 30, 99, 0.1)',
                  tension: 0.4
                }]
              },
              permohonanan: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Alat Perikanan',
                    data: [55, 59, 70, 81, 56, 55, 60, 65, 75, 85, 90, 95],
                    borderColor: '#9c27b0',
                    backgroundColor: 'rgba(156, 39, 176, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Pertanian',
                    data: [45, 49, 60, 71, 46, 45, 50, 55, 65, 75, 80, 85],
                    borderColor: '#9966FF',
                    backgroundColor: 'rgba(103, 58, 183, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Ketahanan Pangan',
                    data: [35, 39, 50, 61, 36, 35, 40, 45, 55, 65, 70, 75],
                    borderColor: '#FFCD56',
                    backgroundColor: 'rgba(63, 81, 181, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Bantuan',
                    data: [65, 79, 80, 71, 76, 85, 90, 85, 95, 105, 110, 115],
                    borderColor: '#2196f3',
                    backgroundColor: 'rgba(33, 150, 243, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Uji Tanaman',
                    data: [25, 29, 40, 51, 26, 25, 30, 35, 45, 55, 60, 65],
                    borderColor: '#00ACC1',
                    backgroundColor: 'rgba(3, 169, 244, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Vaksinasi Hewan',
                    data: [50, 54, 65, 76, 51, 50, 55, 60, 70, 80, 85, 90],
                    borderColor: '#FF6B6B',
                    backgroundColor: 'rgba(0, 188, 212, 0.1)',
                    tension: 0.4
                  }
                ]
              },
              pengajuan: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                  label: 'Izin',
                  data: [40, 44, 55, 66, 41, 40, 45, 50, 60, 70, 75, 80],
                  borderColor: '#4bc0c0',
                  backgroundColor: 'rgba(0, 150, 136, 0.1)',
                  tension: 0.4
                }]
              },
              survey: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                  label: 'Survey Data',
                  data: [20, 25, 30, 35, 28, 32, 38, 42, 45, 48, 52, 55],
                  borderColor: '#ff5722',
                  backgroundColor: 'rgba(255, 87, 34, 0.1)',
                  tension: 0.4
                }]
              }
            },
            2025: {
              all: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Hama dan Penyakit',
                    data: [85, 79, 100, 101, 76, 75],
                    borderColor: '#e91e63',
                    backgroundColor: 'rgba(233, 30, 99, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Perikanan',
                    data: [65, 69, 80, 91, 66, 65],
                    borderColor: '#9c27b0',
                    backgroundColor: 'rgba(156, 39, 176, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Pertanian',
                    data: [55, 59, 70, 81, 56, 55],
                    borderColor: '#9966FF',
                    backgroundColor: 'rgba(103, 58, 183, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Alat Ketahanan Pangan',
                    data: [45, 49, 60, 71, 46, 45],
                    borderColor: '#FFCD56',
                    backgroundColor: 'rgba(63, 81, 181, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Bantuan',
                    data: [75, 89, 90, 81, 86, 95],
                    borderColor: '#2196f3',
                    backgroundColor: 'rgba(33, 150, 243, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Uji Tanaman',
                    data: [35, 39, 50, 61, 36, 35],
                    borderColor: '#00ACC1',
                    backgroundColor: 'rgba(3, 169, 244, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Vaksinasi Hewan',
                    data: [60, 64, 75, 86, 61, 60],
                    borderColor: '#FF6B6B',
                    backgroundColor: 'rgba(0, 188, 212, 0.1)',
                    tension: 0.4
                  },
                  {
                    label: 'Izin',
                    data: [50, 54, 65, 76, 51, 50],
                    borderColor: '#4bc0c0',
                    backgroundColor: 'rgba(0, 150, 136, 0.1)',
                    tension: 0.4
                  }
                ]
              }
            }
          };

          let chart;

          function initChart() {
            const ctx = document.getElementById('wargaChart').getContext('2d');

            chart = new Chart(ctx, {
              type: 'line',
              data: chartData[2024].all,
              options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                  legend: {
                    labels: {
                      color: '#ffffff',
                      font: {
                        size: 10
                      },
                      usePointStyle: true,
                      pointStyle: 'circle'
                    }
                  }
                },
                scales: {
                  y: {
                    beginAtZero: true,
                    ticks: {
                      color: '#ffffff'
                    },
                    grid: {
                      color: 'rgba(255, 255, 255, 0.2)'
                    },
                    title: {
                      display: true,
                      text: 'Jumlah',
                      color: '#ffffff'
                    }
                  },
                  x: {
                    ticks: {
                      color: '#ffffff'
                    },
                    grid: {
                      color: 'rgba(255, 255, 255, 0.2)'
                    },
                    title: {
                      display: true,
                      text: 'Periode',
                      color: '#ffffff'
                    }
                  }
                },
                elements: {
                  point: {
                    radius: 3,
                    hoverRadius: 6
                  }
                }
              }
            });
          }

          function updateChart() {
            const year = document.getElementById('yearSelect').value;
            const category = document.getElementById('categorySelect').value;

            if (chartData[year] && chartData[year][category]) {
              chart.data = chartData[year][category];
              chart.update();
            }
          }

          // Event listeners
          document.getElementById('yearSelect').addEventListener('change', updateChart);
          document.getElementById('categorySelect').addEventListener('change', updateChart);

          // Initialize chart when page loads
          document.addEventListener('DOMContentLoaded', function() {
            initChart();
          });
        </script>

      </div>

      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="#" class="font-weight-bold" target="_blank">Hilda Nurfadilah</a>
                in Banjarmasin
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="#" class="nav-link text-muted" target="_blank">Instagram</a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link text-muted" target="_blank">Twitter</a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link text-muted" target="_blank">Youtube</a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link pe-0 text-muted" target="_blank">Facebook</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#fff",
          data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 15,
              font: {
                size: 14,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false
            },
            ticks: {
              display: false
            },
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#cb0c9f",
            borderWidth: 3,
            backgroundColor: gradientStroke1,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

          },
          {
            label: "Websites",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#3A416F",
            borderWidth: 3,
            backgroundColor: gradientStroke2,
            fill: true,
            data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
            maxBarThickness: 6
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#b2b9bf',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#b2b9bf',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>