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

// Ambil daftar jenis bantuan untuk dropdown
$jenis_bantuan_options = [];
$res_jenis = $conn->query('SELECT id_jenis, nama_jenis FROM jenis_bantuan');
while ($row = $res_jenis->fetch_assoc()) {
    $jenis_bantuan_options[$row['id_jenis']] = $row['nama_jenis'];
}

// Ambil daftar permohonan bantuan user (JOIN ke jenis_bantuan)
$sql_bantuan = "SELECT pb.id_bantuan, pb.alamat, pb.kelurahan, pb.kecamatan, pb.no_telp, jb.nama_jenis AS jenis_bantuan, pb.status_pemohon, pb.tgl_permohonan 
                FROM permohonan_bantuan pb
                JOIN jenis_bantuan jb ON pb.id_jenis = jb.id_jenis
                WHERE pb.id_user = ?";
$stmt_bantuan = $conn->prepare($sql_bantuan);
$stmt_bantuan->bind_param("i", $id_user);
$stmt_bantuan->execute();
$result_bantuan = $stmt_bantuan->get_result();

$bantuan_list = [];
while ($row_bantuan = $result_bantuan->fetch_assoc()) {
    $bantuan_list[] = $row_bantuan;
}
$stmt_bantuan->close();

// Inisialisasi variabel input/form
$alamat = '';
$kelurahan = '';
$kecamatan = '';
$no_telp = '';
$id_jenis = '';
$tgl_permohonan = date('Y-m-d');
$status_pemohon = 'Diproses';
$sukses = '';
$errors = [];

// Tangani form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alamat = trim($_POST['alamat'] ?? '');
    $kelurahan = trim($_POST['kelurahan'] ?? '');
    $kecamatan = trim($_POST['kecamatan'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $id_jenis = $_POST['jenis_bantuan'] ?? '';
    $tgl_permohonan = $_POST['tgl_permohonan'] ?? date('Y-m-d');

    // Validasi input
    if (empty($alamat)) $errors[] = 'Alamat wajib diisi.';
    if (empty($kelurahan)) $errors[] = 'Kelurahan wajib diisi.';
    if (empty($kecamatan)) $errors[] = 'Kecamatan wajib diisi.';
    if (empty($no_telp)) $errors[] = 'Nomor telepon wajib diisi.';
    if (!array_key_exists($id_jenis, $jenis_bantuan_options)) {
        $errors[] = 'Jenis bantuan tidak valid.';
    }
    if (empty($tgl_permohonan)) $errors[] = 'Tanggal permohonan wajib diisi.';

    // Simpan jika valid
    if (count($errors) === 0) {
        $stmt = $conn->prepare("INSERT INTO permohonan_bantuan 
            (id_user, alamat, kelurahan, kecamatan, no_telp, id_jenis, status_pemohon, tgl_permohonan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("issssiss", $id_user, $alamat, $kelurahan, $kecamatan, $no_telp, $id_jenis, $status_pemohon, $tgl_permohonan);

        if ($stmt->execute()) {
            $sukses = 'Permohonan bantuan berhasil dikirim.';
            $alamat = $kelurahan = $kecamatan = $no_telp = '';
            $id_jenis = '';
            $tgl_permohonan = date('Y-m-d');
        } else {
            $errors[] = 'Gagal menyimpan: ' . $stmt->error;
        }

        $stmt->close();
    }
}

// CRUD Jenis Bantuan (khusus admin)
if ($level === 'admin') {
    // Tambah jenis bantuan
    if (isset($_POST['tambah_jenis'])) {
        $nama_jenis = trim($_POST['nama_jenis'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        if ($nama_jenis !== '') {
            $stmt = $conn->prepare('INSERT INTO jenis_bantuan (nama_jenis, deskripsi) VALUES (?, ?)');
            $stmt->bind_param('ss', $nama_jenis, $deskripsi);
            $stmt->execute();
            $stmt->close();
            header('Location: bantuan.php');
            exit();
        }
    }
    // Edit jenis bantuan
    if (isset($_POST['edit_jenis'])) {
        $id_jenis = intval($_POST['edit_id_jenis']);
        $nama_jenis = trim($_POST['edit_nama_jenis'] ?? '');
        $deskripsi = trim($_POST['edit_deskripsi'] ?? '');
        if ($id_jenis && $nama_jenis !== '') {
            $stmt = $conn->prepare('UPDATE jenis_bantuan SET nama_jenis=?, deskripsi=? WHERE id_jenis=?');
            $stmt->bind_param('ssi', $nama_jenis, $deskripsi, $id_jenis);
            $stmt->execute();
            $stmt->close();
            header('Location: bantuan.php');
            exit();
        }
    }
    // Hapus jenis bantuan
    if (isset($_POST['hapus_jenis'])) {
        $id_jenis = intval($_POST['hapus_id_jenis']);
        if ($id_jenis) {
            $stmt = $conn->prepare('DELETE FROM jenis_bantuan WHERE id_jenis=?');
            $stmt->bind_param('i', $id_jenis);
            $stmt->execute();
            $stmt->close();
            header('Location: bantuan.php');
            exit();
        }
    }
    // Ambil ulang data jenis bantuan untuk tabel admin
    $jenis_bantuan_admin = [];
    $res_jenis_admin = $conn->query('SELECT * FROM jenis_bantuan');
    while ($row = $res_jenis_admin->fetch_assoc()) {
        $jenis_bantuan_admin[] = $row;
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
        SILADUMA | Bantuan
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
                            <h6 class="mb-0">Tabel Bantuan</h6>
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
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Alamat</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Kelurahan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Kecamatan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">No. Telp</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Jenis<br>Bantuan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Tanggal</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-validasi-petugas" style="padding: 9px;">Validasi<br>Petugas</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-validasi-kadis" style="padding: 9px;">Validasi<br>Kepala</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($bantuan_list) > 0): $no = 1;
                                            foreach ($bantuan_list as $bantuan): ?>
                                                <tr data-id="<?= $bantuan['id_bantuan'] ?>">
                                                    <td class="text-center" style="padding: 9px;">
                                                        <p class="text-xs font-weight-bold mb-0"><?= $no++ ?></p>
                                                    </td>
                                                    <td class="text-center" style="padding: 9px;">
                                                        <p class="text-xs font-weight-bold mb-0"><?= $nama ?></p>
                                                    </td>
                                                    <td class="text-center" style="padding: 9px;">
                                                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($bantuan['alamat']) ?></p>
                                                    </td>
                                                    <td class="text-center" style="padding: 9px;">
                                                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($bantuan['kelurahan']) ?></p>
                                                    </td>
                                                    <td class="text-center" style="padding: 9px;">
                                                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($bantuan['kecamatan']) ?></p>
                                                    </td>
                                                    <td class="text-center" style="padding: 9px;">
                                                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($bantuan['no_telp']) ?></p>
                                                    </td>
                                                    <td class="text-center" style="padding: 9px;">
                                                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($bantuan['jenis_bantuan']) ?></p>
                                                    </td>
                                                    <td class="text-center" style="padding: 9px;">
                                                        <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($bantuan['tgl_permohonan']) ?></p>
                                                    </td>
                                                    <td class="text-center status" style="padding: 9px;"><span class="badge badge-sm bg-gradient-secondary"><?= htmlspecialchars($bantuan['status_pemohon']) ?></span></td>
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
                                            <?php endforeach;
                                        else: ?>
                                            <tr>
                                                <td colspan="12" class="text-center">Belum ada data permohonan bantuan.</td>
                                            </tr>
                                        <?php endif; ?>
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
                                        const row = selectEl.closest('tr');
                                        const statusCell = row.querySelector('.status');
                                        const kadisSelect = row.querySelector('.validasi-kadis');

                                        if (selectEl.value === 'ya') {
                                            updateStatusBadge(statusCell, 'Direview');
                                            kadisSelect.classList.remove('d-none');
                                        } else if (selectEl.value === 'tidak') {
                                            updateStatusBadge(statusCell, 'Ditolak');
                                            kadisSelect.classList.add('d-none');
                                        } else {
                                            updateStatusBadge(statusCell, 'Diproses');
                                            kadisSelect.classList.add('d-none');
                                        }
                                    }

                                    function handleKadisChange(selectEl) {
                                        const statusCell = selectEl.closest('tr').querySelector('.status');
                                        if (selectEl.value === 'ya') {
                                            updateStatusBadge(statusCell, 'Diterima');
                                        } else if (selectEl.value === 'tidak') {
                                            updateStatusBadge(statusCell, 'Ditolak');
                                        } else {
                                            updateStatusBadge(statusCell, 'Direview');
                                        }
                                    }

                                    function handleEdit(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const alamat = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const kelurahan = row.querySelector('td:nth-child(4)').textContent.trim();
                                        const kecamatan = row.querySelector('td:nth-child(5)').textContent.trim();
                                        const telp = row.querySelector('td:nth-child(6)').textContent.trim();
                                        const jenis = row.querySelector('td:nth-child(7)').textContent.trim();
                                        const deskripsi = row.querySelector('td:nth-child(8)').textContent.trim();
                                        const tanggal = row.querySelector('td:nth-child(9)').textContent.trim();

                                        document.getElementById('edit_id').value = id;
                                        document.getElementById('edit_nama').value = nama;
                                        document.getElementById('edit_alamat').value = alamat;
                                        document.getElementById('edit_kelurahan').value = kelurahan;
                                        document.getElementById('edit_kecamatan').value = kecamatan;
                                        document.getElementById('edit_telp').value = telp;
                                        document.getElementById('edit_jenis_bantuan').value = jenis;
                                        document.getElementById('edit_deskripsi').value = deskripsi;
                                        document.getElementById('edit_tgl_permohonan').value = tanggal;

                                        new bootstrap.Modal(document.getElementById('editDataModal')).show();
                                    }

                                    function handleDelete(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const jenis = row.querySelector('td:nth-child(7)').textContent.trim();

                                        document.getElementById('delete_id').value = id;
                                        document.getElementById('delete_nama_pemohon').textContent = nama;
                                        document.getElementById('delete_jenis_bantuan').textContent = jenis;

                                        new bootstrap.Modal(document.getElementById('deleteModal')).show();
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($level === 'admin'): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Kelola Jenis Bantuan</h6>
                                <button type="button" class="btn btn-sm bg-gradient-green text-white" data-bs-toggle="modal" data-bs-target="#modalTambahJenis">Tambah Jenis</button>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">No.</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Nama<br>Jenis</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Desrkipsi</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($jenis_bantuan_admin as $jenis): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($jenis['nama_jenis']) ?></td>
                                                    <td class="text-center"><?= htmlspecialchars($jenis['deskripsi']) ?></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm bg-gradient-info text-white me-2" data-bs-toggle="modal" data-bs-target="#modalEditJenis" onclick="editJenis(<?= $jenis['id_jenis'] ?>, '<?= htmlspecialchars(addslashes($jenis['nama_jenis'])) ?>', '<?= htmlspecialchars(addslashes($jenis['deskripsi'])) ?>')">
                                                            <i class="bi bi-pencil-square" style="font-size: 0.8rem;"></i>
                                                        </button>
                                                        <button class="btn btn-sm bg-gradient-danger text-white" data-bs-toggle="modal" data-bs-target="#modalHapusJenis" onclick="hapusJenis(<?= $jenis['id_jenis'] ?>, '<?= htmlspecialchars(addslashes($jenis['nama_jenis'])) ?>')">
                                                            <i class="bi bi-trash" style="font-size: 0.8rem;"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Tambah Jenis Bantuan -->
                <div class="modal fade" id="modalTambahJenis" tabindex="-1" aria-labelledby="modalTambahJenisLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTambahJenisLabel">Tambah Jenis Bantuan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Jenis Bantuan</label>
                                        <input type="text" name="nama_jenis" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" name="tambah_jenis" class="btn btn-success">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Jenis Bantuan -->
                <div class="modal fade" id="modalEditJenis" tabindex="-1" aria-labelledby="modalEditJenisLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <input type="hidden" name="edit_id_jenis" id="edit_id_jenis">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditJenisLabel">Edit Jenis Bantuan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Jenis Bantuan</label>
                                        <input type="text" name="edit_nama_jenis" id="edit_nama_jenis" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="edit_deskripsi" id="edit_deskripsi" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" name="edit_jenis" class="btn btn-info text-white">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Hapus Jenis Bantuan -->
                <div class="modal fade" id="modalHapusJenis" tabindex="-1" aria-labelledby="modalHapusJenisLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <input type="hidden" name="hapus_id_jenis" id="hapus_id_jenis">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalHapusJenisLabel">Hapus Jenis Bantuan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin ingin menghapus jenis bantuan <b id="hapus_nama_jenis"></b>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" name="hapus_jenis" class="btn btn-danger">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function editJenis(id, nama, deskripsi) {
                        document.getElementById('edit_id_jenis').value = id;
                        document.getElementById('edit_nama_jenis').value = nama;
                        document.getElementById('edit_deskripsi').value = deskripsi;
                    }

                    function hapusJenis(id, nama) {
                        document.getElementById('hapus_id_jenis').value = id;
                        document.getElementById('hapus_nama_jenis').textContent = nama;
                    }
                </script>
            <?php endif; ?>
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
                <form id="formTambahData" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id_user" value="<?= $id_user ?>">

                        <div class="mb-3">
                            <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control" name="username" value="<?= $nama ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telp</label>
                            <input type="text" name="no_telp" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Bantuan</label>
                            <select name="jenis_bantuan" class="form-control" required>
                                <option value="">Pilih Jenis Bantuan</option>
                                <?php foreach ($jenis_bantuan_options as $id => $nama_jenis): ?>
                                    <option value="<?= $id ?>" <?= $id_jenis == $id ? 'selected' : '' ?>><?= htmlspecialchars($nama_jenis) ?></option>
                                <?php endforeach; ?>
                            </select>
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
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" id="edit_alamat" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control" id="edit_kelurahan" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" id="edit_kecamatan" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telp</label>
                            <input type="text" name="no_telp" class="form-control" id="edit_telp" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Bantuan</label>
                            <select name="jenis_bantuan" class="form-control" id="edit_jenis_bantuan" required>
                                <option value="">Pilih Jenis Bantuan</option>
                                <?php foreach ($jenis_bantuan_options as $id => $nama_jenis): ?>
                                    <option value="<?= $id ?>" <?= $id_jenis == $id ? 'selected' : '' ?>><?= htmlspecialchars($nama_jenis) ?></option>
                                <?php endforeach; ?>
                            </select>
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