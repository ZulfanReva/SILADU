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

// Ambil data permohonan bantuan
$sql_bantuan = "SELECT id_bantuan, nik, alamat, no_telp, jenis_bantuan, deskripsi_bantuan, status_pemohon, tgl_permohonan 
                FROM permohonan_bantuan 
                WHERE id_user = ?";
$stmt_bantuan = $conn->prepare($sql_bantuan);
$stmt_bantuan->bind_param("i", $id_user);
$stmt_bantuan->execute();
$result_bantuan = $stmt_bantuan->get_result();

$bantuan_list = [];
while ($row_bantuan = $result_bantuan->fetch_assoc()) {
    $bantuan_list[] = $row_bantuan;
}
$stmt_bantuan->close();

// Inisialisasi variabel
$nik = '';
$alamat = '';
$no_telp = '';
$jenis_bantuan = '';
$deskripsi_bantuan = '';
$tgl_permohonan = date('Y-m-d');
$status_pemohon = 'Diproses';
$sukses = '';
$errors = [];

// Handle submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = trim($_POST['nik'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $jenis_bantuan = $_POST['jenis_bantuan'] ?? '';
    $deskripsi_bantuan = trim($_POST['deskripsi_bantuan'] ?? '');
    $tgl_permohonan = $_POST['tgl_permohonan'] ?? date('Y-m-d');

    // Validasi
    if (empty($nik) || strlen($nik) !== 16 || !ctype_digit($nik)) $errors[] = 'NIK harus 16 digit angka.';
    if (empty($alamat)) $errors[] = 'Alamat wajib diisi.';
    if (empty($no_telp)) $errors[] = 'Nomor telepon wajib diisi.';
    if (!in_array($jenis_bantuan, ['Pangan', 'Pertanian', 'Perikanan', 'UMKM'])) {
        $errors[] = 'Jenis bantuan tidak valid.';
    }
    if (empty($deskripsi_bantuan)) $errors[] = 'Deskripsi bantuan wajib diisi.';
    if (empty($tgl_permohonan)) $errors[] = 'Tanggal permohonan wajib diisi.';

    // Simpan ke database
    if (count($errors) === 0) {
        $stmt = $conn->prepare("INSERT INTO permohonan_bantuan 
            (id_user, nik, alamat, no_telp, jenis_bantuan, deskripsi_bantuan, status_pemohon, tgl_permohonan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("isssssss", $id_user, $nik, $alamat, $no_telp, $jenis_bantuan, $deskripsi_bantuan, $status_pemohon, $tgl_permohonan);

        if ($stmt->execute()) {
            $sukses = 'Permohonan bantuan berhasil dikirim.';
            // Reset
            $nik = $alamat = $no_telp = $jenis_bantuan = $deskripsi_bantuan = '';
            $tgl_permohonan = date('Y-m-d');
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
        SILADUMA | Ketahanan Pangan
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
                            <h6 class="mb-0">Tabel Ketahanan Pangan</h6>
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
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">NIK</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Alamat</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">No. Telp</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Jenis<br>Bantuan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Deskripsi<br>Bantuan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Tanggal</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Validasi<br>Petugas</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Validasi<br>Kepala</th>
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
                                                <p class="text-xs font-weight-bold mb-0">1234567890123456</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Jl. Mawar No.1</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">081234567890</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Pangan</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">Bantuan beras 10kg</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">2025-06-18</p>
                                            </td>
                                            <td class="text-center status" style="padding: 9px;">
                                                <span class="badge badge-sm bg-gradient-secondary">Diproses</span>
                                            </td>
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
                                    </tbody>
                                </table>
                                <!-- JavaScript Logic -->
                                <script>
                                    const level = document.body.dataset.level;

                                    if (level === 'warga') {
                                        document.querySelectorAll('.col-validasi-petugas, .col-validasi-kadis').forEach(col => col.style.display = 'none');
                                        document.querySelectorAll('.validasi-petugas, .validasi-kadis').forEach(el => el.closest('td').style.display = 'none');
                                    }

                                    function updateStatusBadge(cell, status) {
                                        let className = 'badge badge-sm ';
                                        let label = status;

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

                                        cell.innerHTML = `<span class="${className}">${label}</span>`;
                                    }

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

                                    function handleKadisChange(selectEl) {
                                        const val = selectEl.value;
                                        const statusCell = selectEl.closest('tr').querySelector('.status');

                                        if (val === 'ya') {
                                            updateStatusBadge(statusCell, 'Diterima');
                                        } else if (val === 'tidak') {
                                            updateStatusBadge(statusCell, 'Ditolak');
                                        } else {
                                            updateStatusBadge(statusCell, 'Direview');
                                        }
                                    }

                                    function handleEdit(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const nik = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const alamat = row.querySelector('td:nth-child(4)').textContent.trim();
                                        const telp = row.querySelector('td:nth-child(5)').textContent.trim();
                                        const jenis = row.querySelector('td:nth-child(6)').textContent.trim();
                                        const deskripsi = row.querySelector('td:nth-child(7)').textContent.trim();
                                        const tanggal = row.querySelector('td:nth-child(8)').textContent.trim();

                                        // Isi form modal
                                        document.getElementById('edit_id').value = id;
                                        document.getElementById('edit_nik').value = nik;
                                        document.getElementById('edit_alamat').value = alamat;
                                        document.getElementById('edit_telp').value = telp;
                                        document.getElementById('edit_jenis_bantuan').value = jenis;
                                        document.getElementById('edit_deskripsi').value = deskripsi;
                                        document.getElementById('edit_tgl_permohonan').value = tanggal;

                                        // Tampilkan modal
                                        new bootstrap.Modal(document.getElementById('editDataModal')).show();
                                    }


                                    function handleDelete(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const nik = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const jenis = row.querySelector('td:nth-child(6)').textContent.trim();

                                        document.getElementById('delete_id').value = id;
                                        document.getElementById('delete_nama_pemohon').textContent = nama;
                                        document.getElementById('delete_nik').textContent = nik;
                                        document.getElementById('delete_jenis_bantuan').textContent = jenis;

                                        new bootstrap.Modal(document.getElementById('deleteModal')).show();
                                    }

                                    document.getElementById('formEditData').addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        // Add your AJAX call here to update the data
                                        // After successful update, close modal and refresh data
                                        const modal = bootstrap.Modal.getInstance(document.getElementById('editDataModal'));
                                        modal.hide();
                                    });

                                    document.getElementById('confirmDelete').addEventListener('click', function() {
                                        const id = document.getElementById('delete_id').value;
                                        // Add your AJAX call here to delete the data
                                        // After successful deletion, close modal and refresh data
                                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                                        modal.hide();
                                    });

                                    // Event listener for Tambah Data button
                                    document.querySelector('a.btn-success').addEventListener('click', function(e) {
                                        e.preventDefault();
                                        var modal = new bootstrap.Modal(document.getElementById('tambahDataModal'));
                                        modal.show();
                                    });

                                    // Event listener for form submission
                                    document.getElementById('formTambahData').addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        // Collect form data
                                        const formData = new FormData(this);

                                        // Log data and close modal (replace with AJAX call)
                                        console.log('Form submitted:', Object.fromEntries(formData));

                                        // Close the modal
                                        var modal = bootstrap.Modal.getInstance(document.getElementById('tambahDataModal'));
                                        modal.hide();

                                        // Reset the form
                                        this.reset();
                                    });

                                    // New function for enlarging image
                                    function enlargeImage(img) {
                                        const modal = document.createElement('div');
                                        modal.style.position = 'fixed';
                                        modal.style.top = '0';
                                        modal.style.left = '0';
                                        modal.style.width = '100%';
                                        modal.style.height = '100%';
                                        modal.style.backgroundColor = 'rgba(0,0,0,0.8)';
                                        modal.style.display = 'flex';
                                        modal.style.justifyContent = 'center';
                                        modal.style.alignItems = 'center';
                                        modal.style.zIndex = '1000';

                                        const largeImg = document.createElement('img');
                                        largeImg.src = img.src;
                                        largeImg.style.maxWidth = '90%';
                                        largeImg.style.maxHeight = '90%';
                                        largeImg.style.objectFit = 'contain';

                                        modal.appendChild(largeImg);
                                        document.body.appendChild(modal);

                                        modal.addEventListener('click', () => {
                                            document.body.removeChild(modal);
                                        });
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



    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data Permohonan Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahData" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id_user" value="<?= $id_user ?>">

                        <div class="mb-3">
                            <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control" name="username" value="<?= $nama ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telp</label>
                            <input type="text" name="no_telp" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Bantuan</label>
                            <select name="jenis_bantuan" class="form-control" required>
                                <option value="">Pilih Jenis Bantuan</option>
                                <option value="Pangan">Pangan</option>
                                <option value="Pertanian">Pertanian</option>
                                <option value="Perikanan">Perikanan</option>
                                <option value="UMKM">UMKM</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Bantuan</label>
                            <textarea name="deskripsi_bantuan" class="form-control" required></textarea>
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

    <!-- Modal Edit Data -->
    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataModalLabel">Edit Data Permohonan Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditData" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id_bantuan">

                        <div class="mb-3">
                            <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control" name="username" value="<?= $nama ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" id="edit_nik" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" id="edit_alamat" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telp</label>
                            <input type="text" name="no_telp" class="form-control" id="edit_telp" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Bantuan</label>
                            <select name="jenis_bantuan" class="form-control" id="edit_jenis_bantuan" required>
                                <option value="">Pilih Jenis Bantuan</option>
                                <option value="Pangan">Pangan</option>
                                <option value="Pertanian">Pertanian</option>
                                <option value="Perikanan">Perikanan</option>
                                <option value="UMKM">UMKM</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Bantuan</label>
                            <textarea name="deskripsi_bantuan" class="form-control" id="edit_deskripsi" required></textarea>
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

    <!-- Modal Delete Confirmation -->
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
                        <li>NIK: <span id="delete_nik"></span></li>
                        <li>Jenis Bantuan: <span id="delete_jenis_bantuan"></span></li>
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