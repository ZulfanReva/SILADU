<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: ../../index.php');
    exit();
}

// Koneksi database
$conn = new mysqli('localhost', 'root', '', 'siladu2');
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Ambil data user berdasarkan id_user
$id_user = $_SESSION['id_user'];

$sql = 'SELECT nama, email, alamat, tlp, level, nip FROM user WHERE id_user = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $user = [
        'nama' => 'Tidak diketahui',
        'email' => '',
        'alamat' => '',
        'tlp' => '',
        'level' => '',
        'nip' => '',
    ];
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
        SILADUMA | Profil
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
    <!-- Sidebar -->
    <?php include '../components/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <?php include '../components/navbar.php'; ?>

        <div class="container-fluid">
            <div class="page-header min-height-300 border-radius-xl mt-4"
                style="background-image: url('../assets/img/bgprofil.png'); background-position-y: 50%;">
            </div>

            <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative bg-gradient-green">
                            <img src="../assets/img/ikonprofil.png" alt="profile_image"
                                class="w-100 border-radius-lg shadow-sm">
                        </div>
                    </div>

                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                <?= htmlspecialchars($user['nama']) ?>
                            </h5>
                            <p class="mb-0 font-weight-bold text-sm">
                                <?= htmlspecialchars(ucwords(str_replace('_', ' ', $user['level']))) ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3" data-bs-toggle="modal"
                        href="javascript:;" role="tab" aria-selected="false" data-bs-target="#editPasswordModal">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="javascript:;"
                                        role="tab" aria-selected="false">
                                        <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>Edit Kata Sandi</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(304.000000, 151.000000)">
                                                            <polygon class="color-background" opacity="0.596981957" points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                                            </polygon>
                                                            <path class="color-background" d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z" opacity="0.596981957"></path>
                                                            <path class="color-background" d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        <span class="ms-1">Edit Kata Sandi</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan (Opsional) -->
                <div class="row mt-4">
                    <!-- Kolom 1: Nama dan Email -->
                    <div class="col-md-4">
                        <p><strong>Nama Lengkap</strong><br> <?= htmlspecialchars($user['nama']) ?></p>
                        <p><strong>Email</strong><br> <?= htmlspecialchars($user['email']) ?></p>
                    </div>

                    <!-- Kolom 2: Alamat -->
                    <div class="col-md-4">
                        <p><strong>Alamat</strong><br> <?= nl2br(htmlspecialchars($user['alamat'])) ?></p>
                    </div>

                    <!-- Kolom 3: Telepon + NIP/Kode Admin jika ada -->
                    <div class="col-md-4">
                        <p><strong>Telepon</strong><br> <?= htmlspecialchars($user['tlp']) ?></p>
                        <?php if (in_array($user['level'], ['petugas', 'kepala_dinas', 'admin']) && !empty($user['nip'])): ?>
                            <p><strong><?= $user['level'] === 'admin' ? 'Kode Admin' : 'NIP' ?></strong><br> <?= htmlspecialchars($user['nip']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <script>
                let editPasswordModal;
                document.addEventListener('DOMContentLoaded', function() {
                    editPasswordModal = new bootstrap.Modal(document.getElementById('editPasswordModal'));

                    document.getElementById('passwordForm').addEventListener('submit', function(event) {
                        event.preventDefault();

                        const currentPassword = document.getElementById('currentPassword').value.trim();
                        const newPassword = document.getElementById('newPassword').value.trim();
                        const confirmPassword = document.getElementById('confirmPassword').value.trim();

                        if (!currentPassword || !newPassword || !confirmPassword) {
                            alert('Semua field harus diisi!');
                            return;
                        }

                        if (newPassword.length < 8) {
                            alert('Kata sandi baru minimal 8 karakter!');
                            return;
                        }

                        if (newPassword !== confirmPassword) {
                            alert('Konfirmasi kata sandi tidak cocok!');
                            return;
                        }

                        if (currentPassword === newPassword) {
                            alert('Kata sandi baru tidak boleh sama dengan yang lama!');
                            return;
                        }

                        const formData = new FormData();
                        formData.append('current_password', currentPassword);
                        formData.append('new_password', newPassword);
                        formData.append('new_password_confirmation', confirmPassword);

                        const submitButton = event.target.querySelector('button[type="submit"]');
                        const originalText = submitButton.innerHTML;
                        submitButton.disabled = true;
                        submitButton.innerHTML = 'Menyimpan...';

                        fetch('update_password.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Kata sandi berhasil diperbarui!');
                                    event.target.reset();
                                    if (editPasswordModal) {
                                        editPasswordModal.hide();
                                    }
                                } else {
                                    alert(data.message || 'Terjadi kesalahan saat memperbarui password.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan jaringan.');
                            })
                            .finally(() => {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalText;
                            });
                    });
                });
            </script>
        </div>


        <!-- Footer -->
        <?php include '../components/footer.php'; ?>
    </main>

    <!-- Modal Edit Password -->
    <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPasswordModalLabel">Edit Kata Sandi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="update_password.php" id="passwordForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Kata Sandi Saat Ini</label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Kata Sandi Baru</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" class="form-control" id="confirmPassword" name="new_password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-gradient-green text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>

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