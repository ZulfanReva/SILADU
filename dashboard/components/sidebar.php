<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="#">
            <img src="../../assets/images/logos/logodinasbjm.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">SILADUMA</span>
        </a>
    </div>

    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">

            <!-- UTAMA -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Utama</h6>
            </li>

            <li class="nav-item">
                <?php
                $isActive = $current_page == 'dashboard.php';
                $iconClass = $isActive ? 'icon-md' : 'icon-sm';
                $iconSize = $isActive ? 50 : 30;
                ?>
                <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="../pages/dashboard.php">
                    <div class="icon icon-shape bg-gradient-green <?= $iconClass ?> shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="../assets/img/dashboard.png" alt="Dashboard Icon" width="<?= $iconSize ?>" height="<?= $iconSize ?>">
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <!-- PENGADUAN -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pengaduan</h6>
            </li>

            <li class="nav-item">
                <?php
                $isActive = $current_page == 'hamadanpenyakit.php';
                $iconClass = $isActive ? 'icon-md' : 'icon-sm';
                $iconSize = $isActive ? 50 : 30;
                ?>
                <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="../pages/hamadanpenyakit.php">
                    <div class="bg-gradient-green icon-shape <?= $iconClass ?> shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="../assets/img/hamadanpenyakit.png" alt="Hama & Penyakit" width="<?= $iconSize ?>" height="<?= $iconSize ?>">
                    </div>
                    <span class="nav-link-text ms-1">Hama & Penyakit</span>
                </a>
            </li>

            <!-- PERMOHONAN -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Permohonan</h6>
            </li>

            <?php
            $menu_pages = [
                'perikanan' => 'perikanan.php',
                'pertanian' => 'pertanian.php',
                'ketahananpangan' => 'ketahananpangan.php',
                'bantuan' => 'bantuan.php',
                'ujitanaman' => 'ujitanaman.php',
                'vaksinasihewan' => 'vaksinasihewan.php',
            ];
            foreach ($menu_pages as $key => $page):
                $isActive = $current_page == $page;
                $iconClass = $isActive ? 'icon-md' : 'icon-sm';
                $iconSize = $isActive ? 50 : 30;
            ?>
                <li class="nav-item">
                    <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="../pages/<?= $page ?>">
                        <div class="bg-gradient-green icon-shape <?= $iconClass ?> shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img src="../assets/img/<?= $key ?>.png" alt="<?= $key ?>" width="<?= $iconSize ?>" height="<?= $iconSize ?>">
                        </div>
                        <span class="nav-link-text ms-1"><?= ucwords(str_replace('-', ' ', $key)) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- PENGAJUAN -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Pengajuan</h6>
            </li>

            <li class="nav-item">
                <?php
                $isActive = $current_page == 'izin.php';
                $iconClass = $isActive ? 'icon-md' : 'icon-sm';
                $iconSize = $isActive ? 50 : 30;
                ?>
                <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="../pages/izin.php">
                    <div class="bg-gradient-green icon-shape <?= $iconClass ?> shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="../assets/img/izin.png" alt="Izin" width="<?= $iconSize ?>" height="<?= $iconSize ?>">
                    </div>
                    <span class="nav-link-text ms-1">Izin</span>
                </a>
            </li>

            <!-- SURVEI -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Survei</h6>
            </li>

            <li class="nav-item">
                <?php
                $isActive = $current_page == 'surveikepuasan.php';
                $iconClass = $isActive ? 'icon-md' : 'icon-sm';
                $iconSize = $isActive ? 50 : 30;
                ?>
                <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="../pages/surveikepuasan.php">
                    <div class="bg-gradient-green icon-shape <?= $iconClass ?> shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="../assets/img/surveikepuasan.png" alt="Survei" width="<?= $iconSize ?>" height="<?= $iconSize ?>">
                    </div>
                    <span class="nav-link-text ms-1">Survei Kepuasan</span>
                </a>
            </li>

            <!-- AKUN -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Akun</h6>
            </li>

            <li class="nav-item">
                <?php
                $isActive = $current_page == 'profil.php';
                $iconClass = $isActive ? 'icon-md' : 'icon-sm';
                $iconSize = $isActive ? 50 : 30;
                ?>
                <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="../pages/profil.php">
                    <div class="bg-gradient-green icon-shape <?= $iconClass ?> shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="../assets/img/profil.png" alt="Profil" width="<?= $iconSize ?>" height="<?= $iconSize ?>">
                    </div>
                    <span class="nav-link-text ms-1">Profil</span>
                </a>
            </li>

        </ul>
    </div>

    <hr class="horizontal dark mt-0">

    <div class="sidenav-footer mx-3">
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