<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: ../../index.php');
    exit();
}

// Inisialisasi koneksi database
$conn = new mysqli("localhost", "root", "", "siladu2");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari tabel user berdasarkan id_user
$id_user = $_SESSION['id_user'];

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

// Ambil data pengaduan dari tabel pengaduan_hama_penyakit_tanaman berdasarkan id_user
$sql_pengaduan = "SELECT id_pengaduan, jenis_tanaman, jenis_hama_penyakit, alamat_pengadu, tgl_pengaduan, status 
                  FROM pengaduan_hama_penyakit_tanaman 
                  WHERE id_user = ?";
$stmt_pengaduan = $conn->prepare($sql_pengaduan);
$stmt_pengaduan->bind_param("i", $id_user);
$stmt_pengaduan->execute();
$result_pengaduan = $stmt_pengaduan->get_result();
$pengaduan_list = [];

if ($result_pengaduan->num_rows > 0) {
    while ($row_pengaduan = $result_pengaduan->fetch_assoc()) {
        $pengaduan_list[] = $row_pengaduan;
    }
}
$stmt_pengaduan->close();

// Inisialisasi variabel
$jenis_tanaman = "";
$jenis_hama_penyakit = "";
$alamat_pengadu = "";
$tgl_pengaduan = date('Y-m-d'); // default hari ini
$status = "Diproses"; // default status
$sukses = "";
$errors = [];

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_tanaman = $_POST['jenis_tanaman'];
    $jenis_hama_penyakit = $_POST['jenis_hama_penyakit'];
    $alamat_pengadu = trim($_POST['alamat_pengadu']);
    $tgl_pengaduan = $_POST['tgl_pengaduan'];

    // Validasi
    if (empty($jenis_tanaman)) $errors[] = "Jenis tanaman harus dipilih.";
    if (empty($jenis_hama_penyakit)) $errors[] = "Jenis hama/penyakit harus dipilih.";
    if (empty($alamat_pengadu)) $errors[] = "Alamat pengadu harus diisi.";

    if (count($errors) === 0) {
        $stmt = $conn->prepare("INSERT INTO pengaduan_hama_penyakit_tanaman (id_user, jenis_tanaman, jenis_hama_penyakit, alamat_pengadu, tgl_pengaduan, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $_SESSION['id_user'], $jenis_tanaman, $jenis_hama_penyakit, $alamat_pengadu, $tgl_pengaduan, $status);

        if ($stmt->execute()) {
            $sukses = "Pengaduan berhasil dikirim.";
            // Reset input
            $jenis_tanaman = $jenis_hama_penyakit = $alamat_pengadu = "";
            $tgl_pengaduan = date('Y-m-d');
        } else {
            $errors[] = "Gagal menyimpan data: " . $stmt->error;
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
        SILADUMA | Hama & Penyakit
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
                            <h6 class="mb-0">Tabel Pengaduan Hama & Penyakit</h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm bg-gradient-green btn-sm mb-0 text-white" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                                    <i class="bi bi-file-earmark-pdf" style="font-size: 0.9rem;"></i>
                                </button>
                                <button type="button" class="btn btn-sm bg-gradient-green btn-sm mb-0 text-white" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                                    <i class="bi bi-file-earmark-spreadsheet" style="font-size: 0.9rem;"></i>
                                </button>
                                <button type="button" class="btn btn-sm bg-gradient-green btn-sm mb-0 text-white" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                                    Tambah Data
                                </button>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="5%">No.</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="10%">Nama<br>Pemohon</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="10%">Jenis<br>Tanaman</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="15%">Jenis<br>Hama/Penyakit</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="15%">Alamat</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="10%">Tanggal</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="10%">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="10%">Validasi<br>Petugas</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="10%">Validasi<br>Kepala</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="5%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">1</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">Ari Pratama</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">Padi</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">Wereng</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">Jl. Kenanga No. 10</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">2025-06-18</p>
                                            </td>
                                            <td class="text-center status">
                                                <span class="badge badge-sm bg-gradient-secondary">Diproses</span>
                                            </td>
                                            <td class="text-center">
                                                <select class="form-control form-control-sm validasi-petugas mx-auto" onchange="handlePetugasChange(this)" style="width: 80px;">
                                                    <option value="">Pilih</option>
                                                    <option value="ya">Ya</option>
                                                    <option value="tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select class="form-control form-control-sm validasi-kadis d-none mx-auto" onchange="handleKadisChange(this)" style="width: 80px;">
                                                    <option value="">Pilih</option>
                                                    <option value="ya">Ya</option>
                                                    <option value="tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
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

                                    // Function to handle edit button click
                                    function handleEdit(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id; // Make sure to add data-id attribute to your tr elements
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const jenisTanaman = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const jenisHamaPenyakit = row.querySelector('td:nth-child(4)').textContent.trim();
                                        const alamat = row.querySelector('td:nth-child(5)').textContent.trim();
                                        const tanggal = row.querySelector('td:nth-child(6)').textContent.trim();

                                        // Populate the edit modal
                                        document.getElementById('edit_id').value = id;
                                        document.getElementById('edit_nama_pemohon').value = nama;
                                        document.getElementById('edit_jenis_tanaman').value = jenisTanaman;
                                        document.getElementById('edit_jenis_hama_penyakit').value = jenisHamaPenyakit;
                                        document.getElementById('edit_alamat_pengadu').value = alamat;
                                        document.getElementById('edit_tgl_pengaduan').value = tanggal;

                                        // Show the modal
                                        new bootstrap.Modal(document.getElementById('editDataModal')).show();
                                    }

                                    // Function to handle delete button click
                                    function handleDelete(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id; // Make sure to add data-id attribute to your tr elements
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const jenisTanaman = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const jenisHamaPenyakit = row.querySelector('td:nth-child(4)').textContent.trim();

                                        // Populate the delete modal
                                        document.getElementById('delete_id').value = id;
                                        document.getElementById('delete_nama_pemohon').textContent = nama;
                                        document.getElementById('delete_jenis_tanaman').textContent = jenisTanaman;
                                        document.getElementById('delete_jenis_hama_penyakit').textContent = jenisHamaPenyakit;

                                        // Show the modal
                                        new bootstrap.Modal(document.getElementById('deleteModal')).show();
                                    }

                                    // Update the form submission handlers
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
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Sidebar -->
        <?php include '../components/footer.php'; ?>
    </main>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data Pengaduan Hama & Penyakit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahData">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                            <div>
                                <input type="text" class="form-control" name="username" value="<?= $nama; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Tanaman</label>
                            <select name="jenis_tanaman" class="form-control" id="jenis_tanaman">
                                <option value="">Pilih Jenis Tanaman</option>
                                <option value="Padi" <?= ($jenis_tanaman == "Padi") ? 'selected' : '' ?>>Padi</option>
                                <option value="Cabai" <?= ($jenis_tanaman == "Cabai") ? 'selected' : '' ?>>Cabai</option>
                                <option value="Tomat" <?= ($jenis_tanaman == "Tomat") ? 'selected' : '' ?>>Tomat</option>
                                <option value="Terong" <?= ($jenis_tanaman == "Terong") ? 'selected' : '' ?>>Terong</option>
                                <option value="Bayam" <?= ($jenis_tanaman == "Bayam") ? 'selected' : '' ?>>Bayam</option>
                                <option value="Kangkung" <?= ($jenis_tanaman == "Kangkung") ? 'selected' : '' ?>>Kangkung</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Hama/Penyakit</label>
                            <select name="jenis_hama_penyakit" class="form-control" id="jenis_hama_penyakit">
                                <option value="">Pilih Jenis Hama/Penyakit</option>
                                <option value="Hama Wereng Hijau" <?= ($jenis_hama_penyakit == "Hama Wereng Hijau") ? 'selected' : '' ?>>Hama Wereng Hijau</option>
                                <option value="Penyakit Tungro" <?= ($jenis_hama_penyakit == "Penyakit Tungro") ? 'selected' : '' ?>>Penyakit Tungro</option>
                                <option value="Hama Kutu Daun" <?= ($jenis_hama_penyakit == "Hama Kutu Daun") ? 'selected' : '' ?>>Hama Kutu Daun</option>
                                <option value="Penyakit Antraknosa" <?= ($jenis_hama_penyakit == "Penyakit Antraknosa") ? 'selected' : '' ?>>Penyakit Antraknosa</option>
                                <option value="Hama Lalat Buah" <?= ($jenis_hama_penyakit == "Hama Lalat Buah") ? 'selected' : '' ?>>Hama Lalat Buah</option>
                                <option value="Penyakit Layu Bakteri" <?= ($jenis_hama_penyakit == "Penyakit Layu Bakteri") ? 'selected' : '' ?>>Penyakit Layu Bakteri</option>
                                <option value="Hama Trips" <?= ($jenis_hama_penyakit == "Hama Trips") ? 'selected' : '' ?>>Hama Trips</option>
                                <option value="Penyakit Kutu Kebul" <?= ($jenis_hama_penyakit == "Penyakit Kutu Kebul") ? 'selected' : '' ?>>Penyakit Kutu Kebul</option>
                                <option value="Hama Ulat Grayak" <?= ($jenis_hama_penyakit == "Hama Ulat Grayak") ? 'selected' : '' ?>>Hama Ulat Grayak</option>
                                <option value="Penyakit Bercak Daun" <?= ($jenis_hama_penyakit == "Penyakit Bercak Daun") ? 'selected' : '' ?>>Penyakit Bercak Daun</option>
                                <option value="Hama Belalang" <?= ($jenis_hama_penyakit == "Hama Belalang") ? 'selected' : '' ?>>Hama Belalang</option>
                                <option value="Penyakit Layu Fusarium" <?= ($jenis_hama_penyakit == "Penyakit Layu Fusarium") ? 'selected' : '' ?>>Penyakit Layu Fusarium</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Pengadu</label>
                            <input type="text" name="alamat_pengadu" class="form-control" value="<?= htmlspecialchars($alamat_pengadu) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengaduan</label>
                            <input type="date" name="tgl_pengaduan" class="form-control" value="<?= $tgl_pengaduan ?>" required>
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
                    <h5 class="modal-title" id="editDataModalLabel">Edit Data Pengaduan Hama & Penyakit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditData">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_nama_pemohon" class="form-label">Nama Pemohon</label>
                            <div>
                                <input type="text" class="form-control" id="edit_nama_pemohon" disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Tanaman</label>
                            <select name="jenis_tanaman" class="form-control" id="edit_jenis_tanaman">
                                <option value="">Pilih Jenis Tanaman</option>
                                <option value="Padi">Padi</option>
                                <option value="Cabai">Cabai</option>
                                <option value="Tomat">Tomat</option>
                                <option value="Terong">Terong</option>
                                <option value="Bayam">Bayam</option>
                                <option value="Kangkung">Kangkung</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Hama/Penyakit</label>
                            <select name="jenis_hama_penyakit" class="form-control" id="edit_jenis_hama_penyakit">
                                <option value="">Pilih Jenis Hama/Penyakit</option>
                                <option value="Hama Wereng Hijau">Hama Wereng Hijau</option>
                                <option value="Penyakit Tungro">Penyakit Tungro</option>
                                <option value="Hama Kutu Daun">Hama Kutu Daun</option>
                                <option value="Penyakit Antraknosa">Penyakit Antraknosa</option>
                                <option value="Hama Lalat Buah">Hama Lalat Buah</option>
                                <option value="Penyakit Layu Bakteri">Penyakit Layu Bakteri</option>
                                <option value="Hama Trips">Hama Trips</option>
                                <option value="Penyakit Kutu Kebul">Penyakit Kutu Kebul</option>
                                <option value="Hama Ulat Grayak">Hama Ulat Grayak</option>
                                <option value="Penyakit Bercak Daun">Penyakit Bercak Daun</option>
                                <option value="Hama Belalang">Hama Belalang</option>
                                <option value="Penyakit Layu Fusarium">Penyakit Layu Fusarium</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Pengadu</label>
                            <input type="text" name="alamat_pengadu" class="form-control" id="edit_alamat_pengadu" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengaduan</label>
                            <input type="date" name="tgl_pengaduan" class="form-control" id="edit_tgl_pengaduan" required>
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
                    <p>Apakah Anda yakin ingin menghapus pengaduan ini?</p>
                    <p>Detail Pengaduan:</p>
                    <ul>
                        <li>Nama Pemohon: <span id="delete_nama_pemohon"></span></li>
                        <li>Jenis Tanaman: <span id="delete_jenis_tanaman"></span></li>
                        <li>Jenis Hama/Penyakit: <span id="delete_jenis_hama_penyakit"></span></li>
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
    <script>
        // Event listener untuk tombol Tambah Data
        document.querySelector('a.btn.bg-gradient-green').addEventListener('click', function(e) {
            e.preventDefault();
            var modal = new bootstrap.Modal(document.getElementById('tambahDataModal'));
            modal.show();
        });

        // Event listener untuk form submission
        document.getElementById('formTambahData').addEventListener('submit', function(e) {
            e.preventDefault();
            // Collect form data
            const formData = new FormData(this);

            // Here you would typically send the data to your server
            // For now, we'll just log it and close the modal
            console.log('Form submitted:', Object.fromEntries(formData));

            // Close the modal
            var modal = bootstrap.Modal.getInstance(document.getElementById('tambahDataModal'));
            modal.hide();

            // Reset the form
            this.reset();
        });
    </script>

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