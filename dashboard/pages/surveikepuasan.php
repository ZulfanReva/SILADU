<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: ../../index.php');
    exit();
}

// Inisialisasi koneksi database
$conn = new mysqli('localhost', 'root', '', 'siladu2');
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Ambil data dari tabel user berdasarkan id_user
$id_user = $_SESSION['id_user'];

$sql = 'SELECT nama, level FROM user WHERE id_user = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nama = $row['nama'];
    $level = $row['level'];
} else {
    $nama = 'Pengguna';
    $level = 'Tidak Diketahui';
}

// Ambil data survei milik user (jika user hanya mengisi sekali)
$sql_survei = "SELECT id_survei, tgl_survei, skor, pesan 
               FROM survei_kepuasan 
               WHERE id_user = ?";
$stmt_survei = $conn->prepare($sql_survei);
$stmt_survei->bind_param("i", $id_user);
$stmt_survei->execute();
$result_survei = $stmt_survei->get_result();

$survei = $result_survei->fetch_assoc(); // hanya satu data
$stmt_survei->close();

// Inisialisasi variabel form
$tgl_survei = $survei['tgl_survei'] ?? date('Y-m-d');
$skor = $survei['skor'] ?? '';
$pesan = $survei['pesan'] ?? '';
$sukses = '';
$errors = [];

// Handle submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tgl_survei = $_POST['tgl_survei'] ?? date('Y-m-d');
    $skor = isset($_POST['skor']) ? (int) $_POST['skor'] : null;
    $pesan = trim($_POST['pesan'] ?? '');

    // Validasi
    if (empty($tgl_survei)) $errors[] = 'Tanggal survei wajib diisi.';
    if ($skor === null || $skor < 1 || $skor > 5) $errors[] = 'Skor harus antara 1 sampai 5.';
    if (empty($pesan)) $errors[] = 'Pesan wajib diisi.';

    // Simpan ke database (insert atau update)
    if (count($errors) === 0) {
        if ($survei) {
            // Sudah pernah survei, update
            $stmt = $conn->prepare("UPDATE survei_kepuasan 
                SET tgl_survei = ?, skor = ?, pesan = ? 
                WHERE id_user = ?");
            $stmt->bind_param("sisi", $tgl_survei, $skor, $pesan, $id_user);
        } else {
            // Belum ada, insert baru
            $stmt = $conn->prepare("INSERT INTO survei_kepuasan 
                (id_user, tgl_survei, skor, pesan) 
                VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isis", $id_user, $tgl_survei, $skor, $pesan);
        }

        if ($stmt->execute()) {
            $sukses = $survei ? 'Survei berhasil diperbarui.' : 'Survei berhasil dikirim.';
            // Reset tampilan form
            $survei = [
                'tgl_survei' => $tgl_survei,
                'skor' => $skor,
                'pesan' => $pesan,
            ];
        } else {
            $errors[] = 'Gagal menyimpan: ' . $stmt->error;
        }

        $stmt->close();
    }
}

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
        SILADUMA | Izin
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

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Tabel Izin</h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm bg-gradient-green btn-sm mb-0 text-white"
                                    data-bs-toggle="modal" data-bs-target="#">
                                    <i class="bi bi-file-earmark-pdf" style="font-size: 0.9rem;"></i>
                                </button>
                                <button type="button" class="btn btn-sm bg-gradient-green btn-sm mb-0 text-white"
                                    data-bs-toggle="modal" data-bs-target="#">
                                    <i class="bi bi-file-earmark-spreadsheet" style="font-size: 0.9rem;"></i>
                                </button>
                                <button type="button" class="btn btn-sm bg-gradient-green btn-sm mb-0 text-white"
                                    data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                                    Tambah Data
                                </button>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">No.</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Nama<br>Pengisi</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Tanggal<br>Survei</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Skor</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Pesan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dummy Row -->
                                        <tr data-id="1">
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">1</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Ari Pratama</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">2025-06-20</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <span class="badge badge-sm">5</span> <!-- ini akan otomatis diberi class bg-gradient-success -->
                                            </td>

                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs mb-0">Pelayanan sangat baik dan cepat!</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <button class="btn btn-sm bg-gradient-info text-white me-2" onclick="handleEdit(this)">
                                                    <i class="bi bi-pencil-square" style="font-size: 0.8rem;"></i>
                                                </button>
                                                <button class="btn btn-sm bg-gradient-danger text-white" onclick="handleDelete(this)">
                                                    <i class="bi bi-trash" style="font-size: 0.8rem;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <script>
                                    function applySkorBadgeColor() {
                                        const rows = document.querySelectorAll('table tbody tr');

                                        rows.forEach(row => {
                                            const skorCell = row.querySelector('td:nth-child(4) span'); // Skor ada di kolom ke-4
                                            if (!skorCell) return;

                                            const skor = parseInt(skorCell.textContent.trim());

                                            let bgClass = 'bg-gradient-secondary'; // Default jika error

                                            if (skor >= 1 && skor <= 2) {
                                                bgClass = 'bg-gradient-danger';
                                            } else if (skor === 3) {
                                                bgClass = 'bg-gradient-warning';
                                            } else if (skor === 4) {
                                                bgClass = 'bg-gradient-info';
                                            } else if (skor === 5) {
                                                bgClass = 'bg-gradient-success';
                                            }

                                            skorCell.className = `badge badge-sm ${bgClass}`;
                                        });
                                    }

                                    // Jalankan saat halaman dimuat
                                    document.addEventListener('DOMContentLoaded', applySkorBadgeColor);

                                    function handleEdit(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const tgl = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const skor = row.querySelector('td:nth-child(4) span').textContent.trim();
                                        const pesan = row.querySelector('td:nth-child(5)').textContent.trim();

                                        document.getElementById('edit_id').value = id;
                                        document.getElementById('edit_tgl_survei').value = tgl;
                                        document.getElementById('edit_skor').value = skor;
                                        document.getElementById('edit_pesan').value = pesan;

                                        new bootstrap.Modal(document.getElementById('editSurveiModal')).show();
                                    }

                                    function handleDelete(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const tgl = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const skor = row.querySelector('td:nth-child(4) span').textContent.trim();
                                        const pesan = row.querySelector('td:nth-child(5)').textContent.trim();

                                        document.getElementById('delete_id_survei').value = id;
                                        document.getElementById('delete_tgl_survei').textContent = tgl;
                                        document.getElementById('delete_skor').textContent = skor;
                                        document.getElementById('delete_pesan').textContent = pesan;

                                        new bootstrap.Modal(document.getElementById('deleteSurveiModal')).show();
                                    }
                                </script>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <?php include '../components/footer.php'; ?>
    </main>

    <!-- Modal  Tambah -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Survei Kepuasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahSurvei">
                    <div class="modal-body">
                        <input type="hidden" name="id_user" value="<?= $id_user ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" value="<?= $nama ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Survei</label>
                            <input type="date" name="tgl_survei" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Skor Kepuasan (1 - 5)</label>
                            <select name="skor" class="form-control" required>
                                <option value="">Pilih Skor</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pesan</label>
                            <textarea name="pesan" class="form-control" rows="3" placeholder="Tuliskan pesan atau masukan Anda" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-gradient-green text-white">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editSurveiModal" tabindex="-1" aria-labelledby="editSurveiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSurveiModalLabel">Edit Survei Kepuasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditSurvei">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id_survei">

                        <div class="mb-3">
                            <label class="form-label">Tanggal Survei</label>
                            <input type="date" name="tgl_survei" class="form-control" id="edit_tgl_survei" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Skor Kepuasan</label>
                            <select name="skor" class="form-control" id="edit_skor" required>
                                <option value="">Pilih Skor</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pesan</label>
                            <textarea name="pesan" class="form-control" id="edit_pesan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-gradient-info text-white">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="deleteSurveiModal" tabindex="-1" aria-labelledby="deleteSurveiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSurveiModalLabel">Konfirmasi Hapus Survei</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus survei ini?</p>
                    <ul>
                        <li>Tanggal Survei: <span id="delete_tgl_survei"></span></li>
                        <li>Skor: <span id="delete_skor"></span></li>
                        <li>Pesan: <span id="delete_pesan"></span></li>
                    </ul>
                    <input type="hidden" id="delete_id_survei">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn bg-gradient-danger text-white" id="confirmDeleteSurvei">Hapus</button>
                </div>
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