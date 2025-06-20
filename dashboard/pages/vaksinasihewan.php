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

// Ambil data permohonan vaksinasi hewan milik user (hanya satu karena id_user UNIQUE)
$sql_permohonan = "SELECT id_permohonan, jenis_ternak, gejala, alamat_ternak, tgl_permohonan, status, catatan 
                   FROM permohonan_vaksinasi_hewan 
                   WHERE id_user = ?";
$stmt_permohonan = $conn->prepare($sql_permohonan);
$stmt_permohonan->bind_param("i", $id_user);
$stmt_permohonan->execute();
$result_permohonan = $stmt_permohonan->get_result();

$permohonan = $result_permohonan->fetch_assoc(); // hanya satu data
$stmt_permohonan->close();

// Inisialisasi variabel form
$jenis_ternak = $permohonan['jenis_ternak'] ?? '';
$gejala = $permohonan['gejala'] ?? '';
$alamat_ternak = $permohonan['alamat_ternak'] ?? '';
$tgl_permohonan = $permohonan['tgl_permohonan'] ?? date('Y-m-d');
$status = $permohonan['status'] ?? 'Diproses';
$catatan = $permohonan['catatan'] ?? '';
$sukses = '';
$errors = [];

// Handle submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis_ternak = trim($_POST['jenis_ternak'] ?? '');
    $gejala = trim($_POST['gejala'] ?? '');
    $alamat_ternak = trim($_POST['alamat_ternak'] ?? '');
    $tgl_permohonan = $_POST['tgl_permohonan'] ?? date('Y-m-d');

    // Validasi
    if (empty($jenis_ternak)) $errors[] = 'Jenis ternak wajib diisi.';
    if (empty($gejala)) $errors[] = 'Gejala wajib diisi.';
    if (empty($alamat_ternak)) $errors[] = 'Alamat ternak wajib diisi.';
    if (empty($tgl_permohonan)) $errors[] = 'Tanggal permohonan wajib diisi.';

    // Simpan ke database (insert atau update)
    if (count($errors) === 0) {
        if ($permohonan) {
            // Sudah ada permohonan, update
            $stmt = $conn->prepare("UPDATE permohonan_vaksinasi_hewan 
                SET jenis_ternak = ?, gejala = ?, alamat_ternak = ?, tgl_permohonan = ?, status = 'Diproses', catatan = NULL 
                WHERE id_user = ?");
            $stmt->bind_param("ssssi", $jenis_ternak, $gejala, $alamat_ternak, $tgl_permohonan, $id_user);
        } else {
            // Belum ada, insert baru
            $stmt = $conn->prepare("INSERT INTO permohonan_vaksinasi_hewan 
                (id_user, jenis_ternak, gejala, alamat_ternak, tgl_permohonan, status) 
                VALUES (?, ?, ?, ?, ?, 'Diproses')");
            $stmt->bind_param("issss", $id_user, $jenis_ternak, $gejala, $alamat_ternak, $tgl_permohonan);
        }

        if ($stmt->execute()) {
            $sukses = $permohonan ? 'Permohonan berhasil diperbarui.' : 'Permohonan berhasil dikirim.';
            // Reset tampilan form
            $permohonan = [
                'jenis_ternak' => $jenis_ternak,
                'gejala' => $gejala,
                'alamat_ternak' => $alamat_ternak,
                'tgl_permohonan' => $tgl_permohonan,
                'status' => 'Diproses',
                'catatan' => null,
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
        SILADUMA | Vaksinasi Hewan
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
                            <h6 class="mb-0">Tabel Vaksinasi Hewan</h6>
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
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Nama<br>Pemohon</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Jenis<br>Ternak</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Gejala</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Alamat<br>Ternak</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Tanggal</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-validasi-petugas" style="padding: 9px;">Validasi<br>Petugas</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-validasi-kadis" style="padding: 9px;">Validasi<br>Kepala</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-id="1">
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">1</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Ari Pratama</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Sapi</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Demam, Lesu</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Jl. Ternak No.1</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">2025-06-18</p>
                                            </td>
                                            <td class="text-center status" style="padding: 9px;"><span class="badge badge-sm bg-gradient-secondary">Diproses</span></td>
                                            <td class="text-center" style="padding: 9px;">
                                                <select class="form-control form-control-sm validasi-petugas mx-auto" onchange="handlePetugasChange(this)" style="width: 80px;">
                                                    <option value="">Pilih</option>
                                                    <option value="ya">Ya</option>
                                                    <option value="tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <select class="form-control form-control-sm validasi-kadis d-none mx-auto" onchange="handleKadisChange(this)" style="width: 80px;">
                                                    <option value="">Pilih</option>
                                                    <option value="ya">Ya</option>
                                                    <option value="tidak">Tidak</option>
                                                </select>
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

                                        <tr data-id="2">
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">2</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Rina Sari</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Ayam</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Tidak mau makan</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Dusun Ayam No.3</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">2025-06-15</p>
                                            </td>
                                            <td class="text-center status" style="padding: 9px;"><span class="badge badge-sm bg-gradient-warning">Direview</span></td>
                                            <td class="text-center" style="padding: 9px;">
                                                <select class="form-control form-control-sm validasi-petugas mx-auto" onchange="handlePetugasChange(this)" style="width: 80px;">
                                                    <option value="">Pilih</option>
                                                    <option value="ya" selected>Ya</option>
                                                    <option value="tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <select class="form-control form-control-sm validasi-kadis mx-auto" onchange="handleKadisChange(this)" style="width: 80px;">
                                                    <option value="">Pilih</option>
                                                    <option value="ya">Ya</option>
                                                    <option value="tidak" selected>Tidak</option>
                                                </select>
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
                                    const level = document.body.dataset.level;

                                    // Sembunyikan kolom validasi jika user adalah warga
                                    if (level === 'warga') {
                                        document.querySelectorAll('.col-validasi-petugas, .col-validasi-kadis').forEach(col => col.style.display = 'none');
                                        document.querySelectorAll('.validasi-petugas, .validasi-kadis').forEach(el => el.closest('td').style.display = 'none');
                                    }

                                    // Update tampilan badge status
                                    function updateStatusBadge(cell, status) {
                                        let className = 'badge badge-sm ';
                                        switch (status) {
                                            case 'Diproses':
                                                className += 'bg-gradient-secondary';
                                                break;
                                            case 'Direview':
                                                className += 'bg-gradient-warning';
                                                break;
                                            case 'Diterima':
                                                className += 'bg-gradient-success';
                                                break;
                                            case 'Ditolak':
                                                className += 'bg-gradient-danger';
                                                break;
                                        }
                                        cell.innerHTML = `<span class="${className}">${status}</span>`;
                                    }

                                    // Ketika petugas memvalidasi
                                    function handlePetugasChange(selectEl) {
                                        const val = selectEl.value;
                                        const row = selectEl.closest('tr');
                                        const statusCell = row.querySelector('.status');
                                        const kadisSelect = row.querySelector('.validasi-kadis');

                                        if (val === 'ya') {
                                            updateStatusBadge(statusCell, 'Direview');
                                            kadisSelect.classList.remove('d-none');
                                        } else if (val === 'tidak') {
                                            updateStatusBadge(statusCell, 'Ditolak');
                                            kadisSelect.classList.add('d-none');
                                        } else {
                                            updateStatusBadge(statusCell, 'Diproses');
                                            kadisSelect.classList.add('d-none');
                                        }
                                    }

                                    // Ketika kepala dinas memvalidasi
                                    function handleKadisChange(selectEl) {
                                        const val = selectEl.value;
                                        const row = selectEl.closest('tr');
                                        const statusCell = row.querySelector('.status');

                                        if (val === 'ya') {
                                            updateStatusBadge(statusCell, 'Diterima');
                                        } else if (val === 'tidak') {
                                            updateStatusBadge(statusCell, 'Ditolak');
                                        } else {
                                            updateStatusBadge(statusCell, 'Direview');
                                        }
                                    }

                                    // Isi modal edit
                                    function handleEdit(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const jenisTernak = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const gejala = row.querySelector('td:nth-child(4)').textContent.trim();
                                        const alamat = row.querySelector('td:nth-child(5)').textContent.trim();
                                        const tanggal = row.querySelector('td:nth-child(6)').textContent.trim();

                                        document.getElementById('edit_id').value = id;
                                        document.getElementById('edit_nama').value = nama;
                                        document.getElementById('edit_jenis_ternak').value = jenisTernak;
                                        document.getElementById('edit_gejala').value = gejala;
                                        document.getElementById('edit_alamat_ternak').value = alamat;
                                        document.getElementById('edit_tgl_permohonan').value = tanggal;

                                        new bootstrap.Modal(document.getElementById('editDataModal')).show();
                                    }

                                    // Isi modal hapus
                                    function handleDelete(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const jenisTernak = row.querySelector('td:nth-child(3)').textContent.trim();

                                        document.getElementById('delete_id').value = id;
                                        document.getElementById('delete_nama_pemohon').textContent = nama;
                                        document.getElementById('delete_jenis_ternak').textContent = jenisTernak;

                                        new bootstrap.Modal(document.getElementById('deleteModal')).show();
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

    <!-- Modal Tambah Data - Permohonan Vaksinasi Hewan -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Permohonan Vaksinasi Hewan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahData">
                    <div class="modal-body">
                        <input type="hidden" name="id_user" value="<?= $id_user ?>">

                        <div class="mb-3">
                            <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control" name="username" value="<?= $nama ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Ternak</label>
                            <select name="jenis_ternak" class="form-control" required>
                                <option value="">Pilih Jenis</option>
                                <option value="Sapi">Sapi</option>
                                <option value="Kambing">Kambing</option>
                                <option value="Ikan">Ikan</option>
                                <option value="Ayam">Ayam</option>
                                <option value="Burung">Burung</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gejala</label>
                            <input type="text" name="gejala" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Ternak</label>
                            <input type="text" name="alamat_ternak" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Permohonan</label>
                            <input type="date" name="tgl_permohonan" class="form-control" value="<?= date('Y-m-d') ?>" required>
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

    <!-- Modal Edit Data - Permohonan Vaksinasi Hewan -->
    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataModalLabel">Edit Permohonan Vaksinasi Hewan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditData">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id_permohonan">

                        <div class="mb-3">
                            <label class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama_pemohon" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Ternak</label>
                            <select name="jenis_ternak" id="edit_jenis_ternak" class="form-control" required>
                                <option value="">Pilih Jenis</option>
                                <option value="Sapi">Sapi</option>
                                <option value="Kambing">Kambing</option>
                                <option value="Ikan">Ikan</option>
                                <option value="Ayam">Ayam</option>
                                <option value="Burung">Burung</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gejala</label>
                            <input type="text" name="gejala" class="form-control" id="edit_gejala" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Ternak</label>
                            <input type="text" name="alamat_ternak" class="form-control" id="edit_alamat_ternak" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Permohonan</label>
                            <input type="date" name="tgl_permohonan" class="form-control" id="edit_tgl_permohonan" required>
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

    <!-- Modal Delete Confirmation - Permohonan Vaksinasi Hewan -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus permohonan ini?</p>
                    <p>Detail Permohonan:</p>
                    <ul>
                        <li>Nama Pemohon: <span id="delete_nama_pemohon"></span></li>
                        <li>Jenis Ternak: <span id="delete_jenis_ternak"></span></li>
                    </ul>
                    <input type="hidden" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn bg-gradient-danger text-white" id="confirmDelete">Hapus</button>
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