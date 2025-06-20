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

// Ambil data pengaduan
$sql_pengaduan = "SELECT id_permohonan, jenis_alat, penyebab_kerusakan, permintaan, tgl_permohonan, path_gambar, status 
                  FROM permohonan_alat_ketahananpangan
                  WHERE id_user = ?";
$stmt_pengaduan = $conn->prepare($sql_pengaduan);
$stmt_pengaduan->bind_param("i", $id_user);
$stmt_pengaduan->execute();
$result_pengaduan = $stmt_pengaduan->get_result();

while ($row_pengaduan = $result_pengaduan->fetch_assoc()) {
    $pengaduan_list[] = $row_pengaduan;
}
$stmt_pengaduan->close();

// Inisialisasi variabel
$jenis_alat = '';
$penyebab_kerusakan = '';
$permintaan = '';
$tgl_permohonan = date('Y-m-d'); // default hari ini
$status = 'Diproses'; // default status
$sukses = '';
$errors = [];

// Handle submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis_alat = $_POST['jenis_alat'] ?? '';
    $penyebab_kerusakan = trim($_POST['penyebab_kerusakan'] ?? '');
    $permintaan = $_POST['permintaan'] ?? '';
    $tgl_permohonan = $_POST['tgl_permohonan'] ?? date('Y-m-d');
    $path_gambar = '';

    // Validasi
    if (empty($jenis_alat)) $errors[] = 'Jenis alat harus dipilih.';
    if (empty($penyebab_kerusakan)) $errors[] = 'Penyebab kerusakan harus diisi.';
    if (empty($permintaan) || !in_array($permintaan, ['Perbaikan', 'Ganti Baru'])) {
        $errors[] = 'Permintaan harus dipilih dengan benar.';
    }
    if (empty($tgl_permohonan)) $errors[] = 'Tanggal pengaduan harus diisi.';

    // Validasi dan upload gambar
    if (!empty($_FILES['gambar']['name'])) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_ext)) {
            $errors[] = 'File harus berupa JPG, JPEG, PNG, atau GIF.';
        } else {
            $upload_dir = 'uploads/pengaduan/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $file_name = uniqid('img_', true) . '.' . $file_ext;
            $path_gambar = $upload_dir . $file_name;

            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $path_gambar)) {
                $errors[] = 'Gagal mengunggah gambar.';
            }
        }
    } else {
        $errors[] = 'Gambar wajib diunggah.';
    }

    // Simpan ke database
    if (count($errors) === 0) {
        $stmt = $conn->prepare("INSERT INTO permohonan_alat_ketahananpangan (id_user, jenis_alat, penyebab_kerusakan, permintaan, tgl_permohonan, path_gambar, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $id_user, $jenis_alat, $penyebab_kerusakan, $permintaan, $tgl_permohonan, $path_gambar, $status);

        if ($stmt->execute()) {
            $sukses = 'Pengaduan berhasil dikirim.';
            // Reset
            $jenis_alat = $penyebab_kerusakan = $permintaan = $path_gambar = '';
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
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">No.</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Nama<br>Pemohon</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Jenis<br>Alat</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Penyebab<br>Kerusakan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Permintaan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Tanggal</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Gambar</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Validasi<br>Petugas</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Validasi<br>Kepala</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                style="padding: 10px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" style="padding: 10px;">
                                                <p class="text-xs font-weight-bold mb-0">1</p>
                                            </td>
                                            <td class="text-center" style="padding: 10px;">
                                                <p class="text-xs font-weight-bold mb-0">Ari Pratama</p>
                                            </td>
                                            <td class="text-center" style="padding: 10px;">
                                                <p class="text-xs font-weight-bold mb-0">Traktor</p>
                                            </td>
                                            <td class="text-center" style="padding: 10px;">
                                                <p class="text-xs font-weight-bold mb-0">Mesin Mati</p>
                                            </td>
                                            <td class="text-center" style="padding: 10px;">
                                                <p class="text-xs font-weight-bold mb-0">Perbaikan Mesin</p>
                                            </td>
                                            <td class="text-center" style="padding: 10px;">
                                                <p class="text-xs font-weight-bold mb-0">2025-06-18</p>
                                            </td>
                                            <td class="text-center" style="padding: 10px;">
                                                <img src="path/to/image.jpg" alt="Gambar Alat"
                                                    style="width: 50px; height: auto;" onclick="enlargeImage(this)">
                                            </td>
                                            <td class="text-center status">
                                                <span class="badge badge-sm bg-gradient-secondary">Diproses</span>
                                            </td>
                                            <td class="text-center" style="padding: 10px;">
                                                <select class="form-control form-control-sm validasi-petugas mx-auto"
                                                    onchange="handlePetugasChange(this)" style="width: 80px;">
                                                    <option value="">Pilih</option>
                                                    <option value="ya">Ya</option>
                                                    <option value="tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td class="text-center" style="padding: 10px;">
                                                <select
                                                    class="form-control form-control-sm validasi-kadis d-none mx-auto"
                                                    onchange="handleKadisChange(this)" style="width: 80px;">
                                                    <option value="">Pilih</option>
                                                    <option value="ya">Ya</option>
                                                    <option value="tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td class="text-center" style="padding: 10px;">
                                                <button class="btn btn-sm bg-gradient-info text-white me-2"
                                                    onclick="handleEdit(this)">
                                                    <i class="bi bi-pencil-square" style="font-size: 0.8rem;"></i>
                                                </button>
                                                <button class="btn btn-sm bg-gradient-danger text-white"
                                                    onclick="handleDelete(this)">
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
                                        const jenisAlat = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const waktuKerusakan = row.querySelector('td:nth-child(4)').textContent.trim();
                                        const penyebabKerusakan = row.querySelector('td:nth-child(5)').textContent.trim();
                                        const permintaan = row.querySelector('td:nth-child(6)').textContent.trim();
                                        const tanggal = row.querySelector('td:nth-child(7)').textContent.trim();

                                        // Populate the edit modal
                                        document.getElementById('edit_id').value = id;
                                        document.getElementById('edit_jenis_alat').value = jenisAlat;
                                        document.getElementById('edit_penyebab_kerusakan').value = penyebabKerusakan;
                                        document.getElementById('edit_permintaan').value = permintaan;
                                        document.getElementById('edit_tgl_permohonan').value = tanggal;
                                        document.getElementById('edit_gambar').value = '';

                                        // Show the modal
                                        new bootstrap.Modal(document.getElementById('editDataModal')).show();
                                    }

                                    function handleDelete(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const jenisAlat = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const waktuKerusakan = row.querySelector('td:nth-child(4)').textContent.trim();
                                        const penyebabKerusakan = row.querySelector('td:nth-child(5)').textContent.trim();

                                        // Populate the delete modal
                                        document.getElementById('delete_id').value = id;
                                        document.getElementById('delete_nama_pemohon').textContent = nama;
                                        document.getElementById('delete_jenis_alat').textContent = jenisAlat;
                                        document.getElementById('delete_penyebab_kerusakan').textContent = penyebabKerusakan;

                                        // Show the modal
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
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data Permohonan Ketahanan Pangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahData" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                            <div>
                                <input type="text" class="form-control" name="username" value="<?= $nama; ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Alat</label>
                            <select name="jenis_alat" class="form-control" id="jenis_alat">
                                <option value="">Pilih Jenis Alat</option>
                                <optgroup label="1. Alat Penyimpanan dan Pengawetan Pangan">
                                    <option value="Cold Storage" <?= ($jenis_alat == "Cold Storage") ? 'selected' : '' ?>>Cold Storage (Ruang Pendingin) → Untuk menyimpan bahan pangan agar tetap segar.</option>
                                    <option value="Vacuum Sealer" <?= ($jenis_alat == "Vacuum Sealer") ? 'selected' : '' ?>>Vacuum Sealer → Untuk mengemas makanan agar tahan lama.</option>
                                    <option value="Silo" <?= ($jenis_alat == "Silo") ? 'selected' : '' ?>>Silo → Digunakan untuk menyimpan gabah atau bahan pangan dalam jumlah besar.</option>
                                    <option value="Gudang Penyimpanan Pangan" <?= ($jenis_alat == "Gudang Penyimpanan Pangan") ? 'selected' : '' ?>>Gudang Penyimpanan Pangan → Untuk menjaga stok pangan dalam jangka waktu tertentu.</option>
                                </optgroup>
                                <optgroup label="2. Alat Pengolahan Pangan">
                                    <option value="Mesin Pengering Pangan" <?= ($jenis_alat == "Mesin Pengering Pangan") ? 'selected' : '' ?>>Mesin Pengering Pangan → Untuk mengurangi kadar air pada bahan pangan seperti beras, jagung, dan ikan.</option>
                                    <option value="Alat Penggiling Padi" <?= ($jenis_alat == "Alat Penggiling Padi") ? 'selected' : '' ?>>Alat Penggiling Padi (Rice Mill) → Untuk menggiling gabah menjadi beras siap konsumsi.</option>
                                    <option value="Alat Penepung" <?= ($jenis_alat == "Alat Penepung") ? 'selected' : '' ?>>Alat Penepung (Disk Mill) → Untuk menggiling bahan pangan seperti jagung dan kedelai menjadi tepung.</option>
                                    <option value="Alat Pengasapan Ikan" <?= ($jenis_alat == "Alat Pengasapan Ikan") ? 'selected' : '' ?>>Alat Pengasapan Ikan → Untuk mengawetkan ikan dengan proses pengasapan.</option>
                                    <option value="Alat Pengolahan Produk Olahan" <?= ($jenis_alat == "Alat Pengolahan Produk Olahan") ? 'selected' : '' ?>>Alat Pengolahan Produk Olahan → Seperti mesin pembuat kerupuk, mesin pembuat nugget, dan lainnya.</option>
                                </optgroup>
                                <optgroup label="3. Alat Pengujian Mutu Pangan">
                                    <option value="Moisture Meter" <?= ($jenis_alat == "Moisture Meter") ? 'selected' : '' ?>>Moisture Meter → Untuk mengukur kadar air dalam beras, jagung, atau bahan pangan lainnya.</option>
                                    <option value="pH Meter" <?= ($jenis_alat == "pH Meter") ? 'selected' : '' ?>>pH Meter → Untuk mengukur tingkat keasaman bahan pangan.</option>
                                    <option value="Alat Uji Formalin dan Boraks" <?= ($jenis_alat == "Alat Uji Formalin dan Boraks") ? 'selected' : '' ?>>Alat Uji Formalin dan Boraks → Untuk mendeteksi bahan kimia berbahaya dalam makanan.</option>
                                    <option value="Spektrofotometer" <?= ($jenis_alat == "Spektrofotometer") ? 'selected' : '' ?>>Spektrofotometer → Untuk menguji kandungan gizi dan zat kimia dalam bahan pangan.</option>
                                </optgroup>
                                <optgroup label="4. Alat Distribusi dan Logistik Pangan">
                                    <option value="Truk Berpendingin" <?= ($jenis_alat == "Truk Berpendingin") ? 'selected' : '' ?>>Truk Berpendingin (Refrigerated Truck) → Untuk mendistribusikan bahan pangan yang mudah rusak seperti daging dan susu.</option>
                                    <option value="Kontainer Berpendingin" <?= ($jenis_alat == "Kontainer Berpendingin") ? 'selected' : '' ?>>Kontainer Berpendingin → Untuk menyimpan dan mengangkut stok pangan dalam jumlah besar.</option>
                                    <option value="Timbangan Digital" <?= ($jenis_alat == "Timbangan Digital") ? 'selected' : '' ?>>Timbangan Digital → Untuk menimbang hasil pertanian dan pangan sebelum distribusi.</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penyebab Kerusakan</label>
                            <input type="text" name="penyebab_kerusakan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Permintaan</label>
                            <select name="permintaan" class="form-control" id="edit_permintaan" required>
                                <option value="">Pilih Permintaan</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Ganti Baru">Ganti Baru</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tgl_permohonan" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*" required>
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
                    <h5 class="modal-title" id="editDataModalLabel">Edit Data Permohonan Ketahanan Pangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditData" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control" name="username" value="<?= $nama; ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Alat</label>
                            <select name="jenis_alat" class="form-control" id="edit_jenis_alat">
                                <option value="">Pilih Jenis Alat</option>
                                <optgroup label="1. Alat Penyimpanan dan Pengawetan Pangan">
                                    <option value="Cold Storage" <?= ($jenis_alat == "Cold Storage") ? 'selected' : '' ?>>Cold Storage (Ruang Pendingin) → Untuk menyimpan bahan pangan agar tetap segar.</option>
                                    <option value="Vacuum Sealer" <?= ($jenis_alat == "Vacuum Sealer") ? 'selected' : '' ?>>Vacuum Sealer → Untuk mengemas makanan agar tahan lama.</option>
                                    <option value="Silo" <?= ($jenis_alat == "Silo") ? 'selected' : '' ?>>Silo → Digunakan untuk menyimpan gabah atau bahan pangan dalam jumlah besar.</option>
                                    <option value="Gudang Penyimpanan Pangan" <?= ($jenis_alat == "Gudang Penyimpanan Pangan") ? 'selected' : '' ?>>Gudang Penyimpanan Pangan → Untuk menjaga stok pangan dalam jangka waktu tertentu.</option>
                                </optgroup>
                                <optgroup label="2. Alat Pengolahan Pangan">
                                    <option value="Mesin Pengering Pangan" <?= ($jenis_alat == "Mesin Pengering Pangan") ? 'selected' : '' ?>>Mesin Pengering Pangan → Untuk mengurangi kadar air pada bahan pangan seperti beras, jagung, dan ikan.</option>
                                    <option value="Alat Penggiling Padi" <?= ($jenis_alat == "Alat Penggiling Padi") ? 'selected' : '' ?>>Alat Penggiling Padi (Rice Mill) → Untuk menggiling gabah menjadi beras siap konsumsi.</option>
                                    <option value="Alat Penepung" <?= ($jenis_alat == "Alat Penepung") ? 'selected' : '' ?>>Alat Penepung (Disk Mill) → Untuk menggiling bahan pangan seperti jagung dan kedelai menjadi tepung.</option>
                                    <option value="Alat Pengasapan Ikan" <?= ($jenis_alat == "Alat Pengasapan Ikan") ? 'selected' : '' ?>>Alat Pengasapan Ikan → Untuk mengawetkan ikan dengan proses pengasapan.</option>
                                    <option value="Alat Pengolahan Produk Olahan" <?= ($jenis_alat == "Alat Pengolahan Produk Olahan") ? 'selected' : '' ?>>Alat Pengolahan Produk Olahan → Seperti mesin pembuat kerupuk, mesin pembuat nugget, dan lainnya.</option>
                                </optgroup>
                                <optgroup label="3. Alat Pengujian Mutu Pangan">
                                    <option value="Moisture Meter" <?= ($jenis_alat == "Moisture Meter") ? 'selected' : '' ?>>Moisture Meter → Untuk mengukur kadar air dalam beras, jagung, atau bahan pangan lainnya.</option>
                                    <option value="pH Meter" <?= ($jenis_alat == "pH Meter") ? 'selected' : '' ?>>pH Meter → Untuk mengukur tingkat keasaman bahan pangan.</option>
                                    <option value="Alat Uji Formalin dan Boraks" <?= ($jenis_alat == "Alat Uji Formalin dan Boraks") ? 'selected' : '' ?>>Alat Uji Formalin dan Boraks → Untuk mendeteksi bahan kimia berbahaya dalam makanan.</option>
                                    <option value="Spektrofotometer" <?= ($jenis_alat == "Spektrofotometer") ? 'selected' : '' ?>>Spektrofotometer → Untuk menguji kandungan gizi dan zat kimia dalam bahan pangan.</option>
                                </optgroup>
                                <optgroup label="4. Alat Distribusi dan Logistik Pangan">
                                    <option value="Truk Berpendingin" <?= ($jenis_alat == "Truk Berpendingin") ? 'selected' : '' ?>>Truk Berpendingin (Refrigerated Truck) → Untuk mendistribusikan bahan pangan yang mudah rusak seperti daging dan susu.</option>
                                    <option value="Kontainer Berpendingin" <?= ($jenis_alat == "Kontainer Berpendingin") ? 'selected' : '' ?>>Kontainer Berpendingin → Untuk menyimpan dan mengangkut stok pangan dalam jumlah besar.</option>
                                    <option value="Timbangan Digital" <?= ($jenis_alat == "Timbangan Digital") ? 'selected' : '' ?>>Timbangan Digital → Untuk menimbang hasil pertanian dan pangan sebelum distribusi.</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penyebab Kerusakan</label>
                            <input type="text" name="penyebab_kerusakan" class="form-control" id="edit_penyebab_kerusakan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Permintaan</label>
                            <select name="permintaan" class="form-control" id="edit_permintaan" required>
                                <option value="">Pilih Permintaan</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Ganti Baru">Ganti Baru</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tgl_permohonan" class="form-control" id="edit_tgl_permohonan" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar</label>
                            <input type="file" name="gambar" class="form-control" id="edit_gambar" accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
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
                        <li>Jenis Alat: <span id="delete_jenis_alat"></span></li>
                        <li>Penyebab Kerusakan: <span id="delete_penyebab_kerusakan"></span></li>
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