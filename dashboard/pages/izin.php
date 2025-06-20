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

// Ambil data pengajuan izin milik user (hanya satu karena id_user mungkin hanya mengajukan sekali)
$sql_pengajuan = "SELECT id_pengajuan, jenis_izin, dokumen, kontak, tgl_pengajuan, status, catatan 
                  FROM pengajuan_izin 
                  WHERE id_user = ?";
$stmt_pengajuan = $conn->prepare($sql_pengajuan);
$stmt_pengajuan->bind_param("i", $id_user);
$stmt_pengajuan->execute();
$result_pengajuan = $stmt_pengajuan->get_result();

$pengajuan = $result_pengajuan->fetch_assoc(); // hanya satu data
$stmt_pengajuan->close();

// Inisialisasi variabel form
$jenis_izin = $pengajuan['jenis_izin'] ?? '';
$dokumen = $pengajuan['dokumen'] ?? '';
$kontak = $pengajuan['kontak'] ?? '';
$tgl_pengajuan = $pengajuan['tgl_pengajuan'] ?? date('Y-m-d');
$status = $pengajuan['status'] ?? 'Diproses';
$catatan = $pengajuan['catatan'] ?? '';
$sukses = '';
$errors = [];

// Handle submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis_izin = trim($_POST['jenis_izin'] ?? '');
    $dokumen = trim($_POST['dokumen'] ?? '');
    $kontak = trim($_POST['kontak'] ?? '');
    $tgl_pengajuan = $_POST['tgl_pengajuan'] ?? date('Y-m-d');

    // Validasi
    if (empty($jenis_izin)) $errors[] = 'Jenis izin wajib diisi.';
    if (empty($dokumen)) $errors[] = 'Dokumen wajib diisi.';
    if (empty($kontak)) $errors[] = 'Kontak wajib diisi.';
    if (empty($tgl_pengajuan)) $errors[] = 'Tanggal pengajuan wajib diisi.';

    // Simpan ke database (insert atau update)
    if (count($errors) === 0) {
        if ($pengajuan) {
            // Sudah ada pengajuan, update
            $stmt = $conn->prepare("UPDATE pengajuan_izin 
                SET jenis_izin = ?, dokumen = ?, kontak = ?, tgl_pengajuan = ?, status = 'Diproses', catatan = NULL 
                WHERE id_user = ?");
            $stmt->bind_param("ssssi", $jenis_izin, $dokumen, $kontak, $tgl_pengajuan, $id_user);
        } else {
            // Belum ada, insert baru
            $stmt = $conn->prepare("INSERT INTO pengajuan_izin 
                (id_user, jenis_izin, dokumen, kontak, tgl_pengajuan, status) 
                VALUES (?, ?, ?, ?, ?, 'Diproses')");
            $stmt->bind_param("issss", $id_user, $jenis_izin, $dokumen, $kontak, $tgl_pengajuan);
        }

        if ($stmt->execute()) {
            $sukses = $pengajuan ? 'Pengajuan izin berhasil diperbarui.' : 'Pengajuan izin berhasil dikirim.';
            // Reset tampilan form
            $pengajuan = [
                'jenis_izin' => $jenis_izin,
                'dokumen' => $dokumen,
                'kontak' => $kontak,
                'tgl_pengajuan' => $tgl_pengajuan,
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
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Nama<br>Pemohon</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Jenis<br>Izin</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Kontak</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Tanggal</th>

                                            <!-- ✅ Kolom Tambahan -->
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="padding: 9px;">Dokumen</th>

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
                                                <p class="text-xs font-weight-bold mb-0">Izin Usaha Peternakan</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">081234567890</p>
                                            </td>
                                            <td class="text-center" style="padding: 9px;">
                                                <p class="text-xs font-weight-bold mb-0">2025-06-18</p>
                                            </td>

                                            <!-- ✅ Konten kolom dokumen -->
                                            <td class="text-center" style="padding: 9px;">
                                                <!-- Simulasi jika dokumen adalah PDF -->
                                                <!-- <a href="uploads/dokumen1.pdf" target="_blank" class="btn btn-sm btn-outline-primary">Lihat PDF</a> -->

                                                <!-- Simulasi jika dokumen adalah gambar -->
                                                <a href="uploads/surat_izin1.jpg" target="_blank">
                                                    <img src="uploads/surat_izin1.jpg" alt="Dokumen" style="max-width: 50px; max-height: 50px;">
                                                </a>
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
                                    </tbody>
                                </table>


                                <script>
                                    const level = document.body.dataset.level;

                                    if (level === 'warga') {
                                        document.querySelectorAll('.col-validasi-petugas, .col-validasi-kadis').forEach(col => col.style.display = 'none');
                                        document.querySelectorAll('.validasi-petugas, .validasi-kadis').forEach(el => el.closest('td').style.display = 'none');
                                    }

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

                                    function handleEdit(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const jenisIzin = row.querySelector('td:nth-child(3)').textContent.trim();
                                        const kontak = row.querySelector('td:nth-child(4)').textContent.trim();
                                        const tanggal = row.querySelector('td:nth-child(5)').textContent.trim();

                                        document.getElementById('edit_id').value = id;
                                        document.getElementById('edit_nama').value = nama;
                                        document.getElementById('edit_jenis_izin').value = jenisIzin;
                                        document.getElementById('edit_kontak').value = kontak;
                                        document.getElementById('edit_tgl_pengajuan').value = tanggal;

                                        new bootstrap.Modal(document.getElementById('editDataModal')).show();
                                    }

                                    function handleDelete(button) {
                                        const row = button.closest('tr');
                                        const id = row.dataset.id;
                                        const nama = row.querySelector('td:nth-child(2)').textContent.trim();
                                        const jenisIzin = row.querySelector('td:nth-child(3)').textContent.trim();

                                        document.getElementById('delete_id').value = id;
                                        document.getElementById('delete_nama_pemohon').textContent = nama;
                                        document.getElementById('delete_jenis_izin').textContent = jenisIzin;

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

    <!-- Modal Tambah Data - Pengajuan Izin -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Pengajuan Izin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahData">
                    <div class="modal-body">
                        <input type="hidden" name="id_user" value="<?= $id_user ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control" name="username" value="<?= $nama ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Izin</label>
                            <input type="text" name="jenis_izin" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kontak</label>
                            <input type="text" name="kontak" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Dokumen (PDF saja)</label>
                            <input type="file" name="dokumen" accept=".pdf" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengajuan</label>
                            <input type="date" name="tgl_pengajuan" class="form-control" value="<?= date('Y-m-d') ?>" required>
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

    <!-- Modal Edit Data - Pengajuan Izin -->
    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataModalLabel">Edit Pengajuan Izin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditData">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id_pengajuan">

                        <div class="mb-3">
                            <label class="form-label">Nama Pemohon</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama_pemohon" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Izin</label>
                            <input type="text" name="jenis_izin" class="form-control" id="edit_jenis_izin" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kontak</label>
                            <input type="text" name="kontak" class="form-control" id="edit_kontak" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Ulang Dokumen (PDF saja)</label>
                            <input type="file" name="dokumen" accept=".pdf" class="form-control">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengajuan</label>
                            <input type="date" name="tgl_pengajuan" class="form-control" id="edit_tgl_pengajuan" required>
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

    <!-- Modal Delete Confirmation - Pengajuan Izin -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pengajuan ini?</p>
                    <p>Detail Pengajuan:</p>
                    <ul>
                        <li>Nama Pemohon: <span id="delete_nama_pemohon"></span></li>
                        <li>Jenis Izin: <span id="delete_jenis_izin"></span></li>
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