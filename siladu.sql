-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Feb 2025 pada 10.57
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siladu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `administrasi`
--

CREATE TABLE `administrasi` (
  `id` int(11) NOT NULL,
  `userId` varchar(16) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `data` varchar(100) NOT NULL,
  `tanggal` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `administrasi`
--

INSERT INTO `administrasi` (`id`, `userId`, `nama`, `jenis`, `deskripsi`, `data`, `tanggal`) VALUES
(2, 'warga1', 'Warga sini', 'Surat Keterangan Ahli Waris', 'ini deskripsi betul', 'Case Time Management.pdf', '2022-06-12 12:11:59'),
(4, 'anjay', 'hilda ', 'Surat Keterangan Tidak Mampu', 'aku ga mampu', '9c0b7e8fa298bb3ff5879e263a3e9606.pdf', '2024-12-27 19:50:09'),
(5, 'petugas', 'rahmi', 'Surat Keterangan Tidak Mampu', 'tidak mampu jer', '9c0b7e8fa298bb3ff5879e263a3e9606.pdf', '2024-12-27 20:34:13'),
(6, 'warga1', 'Warga sini', 'Permohonan Sosialisasi atau Penyuluhan', 'penyuluhan tentang ikan', '9c0b7e8fa298bb3ff5879e263a3e9606.pdf', '2025-01-13 19:14:05'),
(7, 'warga1', 'Warga sini', 'Permohonan Sosialisasi atau Penyuluhan', 'penyuluhan tentang ikan', '9c0b7e8fa298bb3ff5879e263a3e9606.pdf', '2025-01-13 19:14:29'),
(8, 'anjay', 'hilda ', 'Permohonan Sosialisasi atau Penyuluhan', 'penyuluhan ', 'TSE.2016.2632115.pdf', '2025-01-16 18:57:18'),
(9, 'anjay', 'hilda ', 'Permohonan Sosialisasi atau Penyuluhan', 'sosialisasi', 'pemotong rumput.pdf', '2025-01-16 19:14:42'),
(11, 'anjay', 'hilda ', 'Permohonan Sosialisasi atau Penyuluhan', 'sosialisasi', 'ijnjknn.pdf', '2025-01-19 19:02:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aturan_layanan`
--

CREATE TABLE `aturan_layanan` (
  `id` int(10) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `aturan` longtext NOT NULL,
  `template_data` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `petugas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aturan_layanan`
--

INSERT INTO `aturan_layanan` (`id`, `id_layanan`, `aturan`, `template_data`, `petugas`) VALUES
(1, 2, '- Surat pengantar dari RT dan keterangan dari dukuh setempat yang mengonfirmasi kondisi ekonomi keluarga.\r\n- Surat pernyataan yang menyatakan bahwa pemohon atau keluarga belum terdaftar dalam DTKS (Data Terpadu Kesejahteraan Sosial), disertai tanda tangan yang sah.\r\n- Rincian biaya yang dibutuhkan, seperti biaya untuk pemulihan atau pengadaan benih bibit pertanian, alat pertanian, atau biaya lainnya yang terkait dengan pemenuhan ketahanan pangan di wilayah pemohon.\r\n- Fotokopi Kartu Keluarga dan menunjukkan yang asli.\r\n- Fotokopi dan e-KTP yang asli.\r\n- Surat pernyataan tidak mampu yang diketahui oleh RT dan 2 orang saksi, yang mengonfirmasi kondisi ketidakmampuan atau kekurangan dalam bidang ekonomi terkait pemenuhan kebutuhan pangan atau sarana pertanian.\r\n- Tanda lunas Pajak Bumi dan Bangunan (PBB) yang menunjukkan bahwa pemohon atau keluarga yang mengajukan permohonan bebas dari kewajiban pajak terkait.\r\n- Foto-foto rumah dan lokasi tanah pertanian atau perikanan yang diusahakan (dari posisi depan, samping, dan area usaha yang relevan), dengan ukuran foto 5R.\r\n- Dokumen pendukung lain yang relevan sesuai dengan jenis bantuan atau pelayanan yang dimohonkan, misalnya izin usaha pertanian/perikanan, atau bukti sertifikasi tanah.', 'Case Time Management.pdf', 'Administrator1'),
(15, 4, '1. Ketentuan Umum\r\n- Permohonan dapat diajukan oleh individu, kelompok tani/nelayan, atau lembaga pendidikan yang berada di wilayah Kota Banjarmasin.\r\n- Penyuluhan dilakukan berdasarkan jadwal dan prioritas kebutuhan masyarakat.\r\n- Semua permohonan yang disetujui akan ditangani oleh penyuluh resmi dari dinas atau tenaga ahli terkait.\r\n\r\n2. Syarat Pengajuan Permohonan\r\nPengisian formulir permohonan di aplikasi atau langsung ke kantor dinas, yang memuat:\r\n- Nama pemohon (individu/kelompok/lembaga).\r\n- Alamat dan kontak yang dapat dihubungi.\r\n- Lokasi dan tanggal yang diusulkan untuk kegiatan.\r\n- Jenis penyuluhan atau topik yang diinginkan.\r\n- Melampirkan dokumen pendukung (jika diperlukan):\r\n- Profil kelompok (bagi kelompok tani/nelayan).\r\n- Proposal kegiatan untuk lembaga pendidikan.\r\n\r\n3. Hak dan Kewajiban Pemohon\r\nHak Pemohon:\r\n- Mendapatkan bimbingan atau edukasi sesuai kebutuhan yang diajukan.\r\n- Menyampaikan usulan tambahan selama proses penyuluhan berlangsung.\r\n- Kewajiban Pemohon:\r\n- Memberikan informasi permohonan dengan lengkap dan benar.\r\n- Menyediakan tempat atau fasilitas pendukung untuk pelaksanaan kegiatan.\r\n- Mematuhi tata tertib selama penyuluhan berlangsung.\r\n\r\nSosialisasi atau penyuluhan tidak dipungut biaya kecuali ada kesepakatan khusus untuk pendanaan sarana dan prasarana yang diperlukan (misalnya, acara besar yang memerlukan tambahan fasilitas).', '9c0b7e8fa298bb3ff5879e263a3e9606.pdf', 'Administrator1'),
(16, 5, '1. Pengelolaan dan Pembagian Distribusi\r\nSumber Dana atau Bantuan:\r\n- Semua distribusi bantuan yang berasal dari anggaran pemerintah harus melalui prosedur yang sesuai dengan regulasi yang ada, seperti melalui - - program Bantuan Langsung, atau penerima subsidi dari pemerintah.\r\nKesesuaian dengan Program Dinas:\r\n- Distribusi dilakukan sesuai dengan prioritas dan kebutuhan yang sudah diverifikasi melalui data lapangan atau survei yang memadai.\r\n\r\n2. Kriteria dan Penentuan Penerima Bantuan\r\nKelompok Penerima yang Tepat:\r\n- Pemerataan distribusi harus memenuhi kriteria yang ditetapkan, seperti keanggotaan dalam kelompok tani atau nelayan yang sah, atau petani dengan lahan dan skala usaha tertentu.\r\n- Menggunakan data valid dan terbaru dari Dinas Pertanian dan Perikanan tentang jumlah anggota, luas lahan, dan jenis usaha.\r\n\r\n3. Pengawasan dan Pemantauan Distribusi\r\nMonitoring Pelaksanaan:\r\n- Lakukan pemantauan secara berkala untuk memastikan bantuan sampai ke penerima yang berhak dan sesuai jadwal. Ini melibatkan pihak terkait, seperti aparat desa, kelompok tani/nelayan, dan petugas lapangan dinas.\r\nDokumentasi dan Arsip:\r\n- Setiap transaksi distribusi atau pemberian bantuan harus dilengkapi dengan dokumentasi resmi dan diterima oleh pihak penerima untuk menghindari penyalahgunaan.', 'Case Time Management.pdf', 'Administrator1'),
(17, 6, '- Semua proses pengajuan izin dilakukan melalui aplikasi berbasis daring, termasuk pengisian formulir dan pengunggahan dokumen yang diperlukan.\r\n- Semua pengajuan akan diproses dalam waktu yang wajar, sesuai dengan jenis izin yang diajukan.\r\n- Bagi pemohon yang membutuhkan, tersedia layanan pendampingan atau penyuluhan tentang cara mengajukan izin atau memenuhi syarat yang dibutuhkan.\r\n- Izin usaha yang diterbitkan berlaku selama 5 tahun dan dapat diperbarui sesuai prosedur yang ditetapkan oleh dinas.\r\n- Setiap pemegang izin usaha perikanan dan produksi pangan wajib mematuhi ketentuan yang berlaku, dan Dinas Ketahanan Pangan, Pertanian, dan Perikanan akan melakukan pengawasan rutin.\r\n- Jika ada perubahan dalam kegiatan usaha atau lokasi, pemohon wajib mengajukan permohonan perubahan izin kepada Dinas.\r\n', '9c0b7e8fa298bb3ff5879e263a3e9606.pdf', 'Administrator1'),
(18, 7, '- Bantuan modal usaha berasal dari anggaran pemerintah kota, pemerintah provinsi, atau dana lain yang sah digunakan untuk mendukung sektor pertanian/perikanan.\r\n- Individu: Petani atau nelayan yang tinggal di Kota Banjarmasin.\r\n- Kelompok: Kelompok tani/nelayan yang terdaftar resmi di Dinas Ketahanan Pangan, Pertanian, dan Perikanan Kota Banjarmasin. Kelompok usaha pertanian dan perikanan harus terorganisir dengan baik dan memiliki struktur yang jelas.\r\n- Hibah langsung (tanpa kewajiban pengembalian, berdasarkan persyaratan yang ada).\r\n- Bantuan dalam bentuk barang (misalnya alat atau mesin).\r\n- Bantuan modal dalam bentuk uang (terbatas dan disertai dengan laporan penggunaan anggaran).\r\n', 'Case Time Management.pdf', 'Administrator1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `userId` varchar(100) NOT NULL,
  `tanggapan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `feedback`
--

INSERT INTO `feedback` (`id`, `userId`, `tanggapan`) VALUES
(1, 'administrator001', 'ini adalah tanggapan dariku');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_administrasi`
--

CREATE TABLE `hasil_administrasi` (
  `id` int(11) NOT NULL,
  `administrasiId` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `file` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal` date NOT NULL,
  `petugas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil_administrasi`
--

INSERT INTO `hasil_administrasi` (`id`, `administrasiId`, `nama`, `deskripsi`, `file`, `tanggal`, `petugas`) VALUES
(1, 2, 'Warga sini', 'ini tanggapan', 'Tugas Problem Solving GANJIL.pdf', '2022-06-12', 'Siti Aminah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_pengaduan`
--

CREATE TABLE `hasil_pengaduan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `pengaduanId` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `file` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tanggal` date NOT NULL,
  `petugas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil_pengaduan`
--

INSERT INTO `hasil_pengaduan` (`id`, `nama`, `pengaduanId`, `deskripsi`, `file`, `tanggal`, `petugas`) VALUES
(9, 'Warga sini', 289, 'ini tanggapan', '1-article.en.id.pdf', '2022-06-12', 'Administrator1'),
(10, 'warga sini', 289, 'mantap', 'Case Time Management.pdf', '2024-12-27', 'Administrator1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_pengaduan_alat_ikan`
--

CREATE TABLE `hasil_pengaduan_alat_ikan` (
  `id_pengaduan` int(11) NOT NULL,
  `nama_pemohon` varchar(100) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `waktu_kerusakan` date NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Pending','Disetujui','Ditolak') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `keterangan_petugas` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_pengaduan_alat_tani`
--

CREATE TABLE `hasil_pengaduan_alat_tani` (
  `id` int(11) NOT NULL,
  `pengaduanID` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `waktu_kerusakan` date NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Pending','Disetujui','Ditolak') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `keterangan_petugas` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_pengajuan_izin`
--

CREATE TABLE `hasil_pengajuan_izin` (
  `id_pengajuan` int(11) NOT NULL,
  `nama_pemohon` varchar(100) NOT NULL,
  `jenis_izin` varchar(100) NOT NULL,
  `dokumen` text NOT NULL,
  `kontak` varchar(100) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `status` enum('Menunggu','Diproses','Disetujui','Ditolak') NOT NULL,
  `catatan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_survey`
--

CREATE TABLE `hasil_survey` (
  `id_survey` int(11) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `tgl_pengisian` date NOT NULL,
  `skor_penilaian` varchar(50) NOT NULL,
  `komentar` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kepengurusan`
--

CREATE TABLE `kepengurusan` (
  `id` int(11) NOT NULL,
  `nip` varchar(18) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kepengurusan`
--

INSERT INTO `kepengurusan` (`id`, `nip`, `foto`, `nama`, `jabatan`) VALUES
(1, '123456789098765432', '852-mleyot-removebg-preview.png', 'Bambang Eka', 'Kepala Desa'),
(18, '888888888888888888', '480-himatif_store.png', 'coba', 'Anggota');

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id` int(10) NOT NULL,
  `jenis` enum('Pengaduan','Administrasi','perizinan','permohonan bantuan') NOT NULL,
  `spesifikasi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `layanan`
--

INSERT INTO `layanan` (`id`, `jenis`, `spesifikasi`) VALUES
(1, 'Pengaduan', 'Ketahanan Pangan'),
(2, 'Administrasi', 'Surat Keterangan Tidak Mampu'),
(4, 'Administrasi', 'Permohonan Sosialisasi atau Penyuluhan'),
(5, 'Pengaduan', 'Permasalahan distribusi hasil pertanian/perikanan'),
(6, 'perizinan', 'Izin usaha dll'),
(7, 'permohonan bantuan', 'Bantuan Alat Tangkap');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` int(10) NOT NULL,
  `userId` varchar(16) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `data` varchar(255) NOT NULL DEFAULT '-',
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `userId`, `nama`, `jenis`, `deskripsi`, `data`, `tanggal`) VALUES
(6, 'administrator001', 'Administrator1', 'Pengaduan Listrik', 'ini deskripsi setelah update', 'to do list 404.pdf', '2022-06-12 18:17:58'),
(280, 'administrator001', 'Administrator1', '(1Pengaduan Listrik)', 'deskripsi keempat', '', '2022-06-04 21:06:11'),
(281, 'administrator001', 'Administrator1', 'Pengaduan Listrik', 'ya ini coba saja keempat', '', '2022-06-05 10:08:20'),
(288, 'warga1', 'Warga sini', 'Pengaduan Sembako', 'ini deskripsi', '', '2022-06-07 09:46:07'),
(289, 'warga1', 'Warga sini', 'Pengaduan Listrik', 'ini deskripsi', 'surat izin ortu 001.pdf', '2022-06-12 19:51:48'),
(290, 'administrator001', 'Administrator1', 'Pengaduan Sembako', 'ini deskripsi', '', '2022-06-12 17:53:47'),
(292, 'administrator001', 'Administrator1', 'Pengaduan Listrik', 'ini deskripsi', 'Berita Acara HMPS-TI.pdf', '2022-06-12 18:08:29'),
(293, 'administrator001', 'Administrator1', 'Pengaduan Listrik', 'ini deskripsi', 'tang2010.pdf', '2022-06-13 09:43:12'),
(294, 'anjay', 'hilda ', 'Pengaduan Sembako', 'sembako harganya mahal bangg', '9c0b7e8fa298bb3ff5879e263a3e9606.pdf', '2024-12-27 19:48:50'),
(295, 'petugas', 'rahmi', 'Pengaduan Sembako', 'mahal bngt', '9c0b7e8fa298bb3ff5879e263a3e9606.pdf', '2024-12-27 20:33:24'),
(296, 'anjay', 'hilda ', 'minta bantuan dana', 'kdd duit', 'Case Time Management.pdf', '2024-12-27 21:44:11'),
(297, 'administrator001', 'Administrator1', 'Permasalahan distribusi hasil pertanian/perikanan', 'diselesaikan', 'Case Time Management.pdf', '2025-01-04 14:31:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_alat_ketahananpangan`
--

CREATE TABLE `pengaduan_alat_ketahananpangan` (
  `id_pengaduan` int(11) NOT NULL,
  `nama_pemohon` varchar(100) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `waktu_kerusakan` date NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Pending','Disetujui','Ditolak') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `keterangan_petugas` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_alat_ketahananpangan`
--

INSERT INTO `pengaduan_alat_ketahananpangan` (`id_pengaduan`, `nama_pemohon`, `jenis_alat`, `waktu_kerusakan`, `penyebab_kerusakan`, `permintaan`, `path_gambar`, `stts_pengaduan`, `tgl_pengaduan`, `tgl_selesai`, `keterangan_petugas`) VALUES
(1, 'Warga sini', 'Timbangan Digital', '2025-02-07', 'rusak', 'Perbaikan', 'timbangan digital pangan rusak.jpg', 'Pending', '2025-02-07', '0000-00-00', ''),
(2, 'Warga sini', 'Timbangan Digital', '2025-02-07', 'rusak', 'Perbaikan', 'timbangan digital pangan rusak.jpg', 'Pending', '2025-02-07', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_alat_perikanan`
--

CREATE TABLE `pengaduan_alat_perikanan` (
  `id_pengaduan` int(11) NOT NULL,
  `nama_pemohon` varchar(100) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `waktu_kerusakan` date NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Pending','Disetujui','Ditolak') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `keterangan_petugas` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_alat_perikanan`
--

INSERT INTO `pengaduan_alat_perikanan` (`id_pengaduan`, `nama_pemohon`, `jenis_alat`, `waktu_kerusakan`, `penyebab_kerusakan`, `permintaan`, `path_gambar`, `stts_pengaduan`, `tgl_pengaduan`, `tgl_selesai`, `keterangan_petugas`) VALUES
(2, 'Warga sini', 'Jaring Gill Net', '2025-02-09', 'jaring tersebut sudah mulai sobek', 'Ganti Baru', 'foto kerusakan jaring gill net.jpg', 'Pending', '2025-02-09', '0000-00-00', ''),
(3, 'hilda ', 'Keramba Jaring Apung (KJA)', '2025-02-10', 'rusak', 'Ganti Baru', 'foto cangkul patah.jpg', 'Pending', '2025-02-10', '0000-00-00', ''),
(4, 'hilda ', 'Keramba Jaring Apung (KJA)', '2025-02-10', 'rusak', 'Ganti Baru', 'foto cangkul patah.jpg', 'Pending', '2025-02-10', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_alat_pertanian`
--

CREATE TABLE `pengaduan_alat_pertanian` (
  `id_pengaduan` int(11) NOT NULL,
  `nama_pemohon` varchar(100) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `waktu_kerusakan` date NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Pending','Disetujui','Ditolak') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `keterangan_petugas` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_alat_pertanian`
--

INSERT INTO `pengaduan_alat_pertanian` (`id_pengaduan`, `nama_pemohon`, `jenis_alat`, `waktu_kerusakan`, `penyebab_kerusakan`, `permintaan`, `path_gambar`, `stts_pengaduan`, `tgl_pengaduan`, `tgl_selesai`, `keterangan_petugas`) VALUES
(1, 'Warga sini', 'Cangkul', '2025-02-07', 'patah', 'Ganti Baru', 'foto cangkul patah.jpg', 'Pending', '2025-02-07', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `id_pengajuan` int(11) NOT NULL,
  `nama_pemohon` varchar(100) NOT NULL,
  `jenis_izin` varchar(100) NOT NULL,
  `dokumen` text NOT NULL,
  `kontak` varchar(100) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `status_pemohon` enum('Menunggu','Diproses','Disetujui','Ditolak') NOT NULL,
  `catatan_admin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` (`id_pengajuan`, `nama_pemohon`, `jenis_izin`, `dokumen`, `kontak`, `tgl_pengajuan`, `status_pemohon`, `catatan_admin`) VALUES
(2, 'basir', 'Izin Usaha Pertanian', 'pemotong rumput.pdf', '082251750710', '2025-01-13', '', 'buka usaha'),
(3, 'hilda', 'Izin Usaha Pertanian', 'ijjnj.pdf', '082251750710', '2025-01-19', 'Disetujui', 'izin membuka usah pertanian didesa'),
(4, 'hilda ', 'Izin Usaha Budidaya Tanaman Pangan dan Hortikultura', '1-article.en.id.pdf', '082251750710', '2025-02-10', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_bantuan`
--

CREATE TABLE `permohonan_bantuan` (
  `id_bantuan` int(11) NOT NULL,
  `nama_pemohon` varchar(255) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `jenis_bantuan` enum('Pangan','Pertanian','Perikanan','UMKM') NOT NULL,
  `deskripsi_bantuan` varchar(255) NOT NULL,
  `status_pemohon` enum('Diajukan','Diproses','Disetujui','Ditolak') NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `tgl_persetujuan` date NOT NULL,
  `catatan_admin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan_bantuan`
--

INSERT INTO `permohonan_bantuan` (`id_bantuan`, `nama_pemohon`, `nik`, `alamat`, `no_telp`, `jenis_bantuan`, `deskripsi_bantuan`, `status_pemohon`, `tgl_pengajuan`, `tgl_persetujuan`, `catatan_admin`) VALUES
(1, 'Warga sini', '0871243', 'jl mekarsari', '082354678912', 'Pertanian', 'bantuan untuk padi', '', '2025-02-06', '0000-00-00', ''),
(2, 'Warga sini', '0871243', 'jl mekarsari', '082354678912', 'Pertanian', 'bantuan untuk padi', '', '2025-02-06', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `userId` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` enum('Pengaduan','Administrasi') NOT NULL,
  `request` varchar(100) NOT NULL,
  `alasan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `request`
--

INSERT INTO `request` (`id`, `userId`, `nama`, `jenis`, `request`, `alasan`) VALUES
(2, 'administrator001', 'Administrator1', 'Pengaduan', 'Pengaduan Sembako mahal', 'sembako makin mahal, sedangkan pendapatan saya tetap'),
(3, 'warga1', 'Warga sini', 'Administrasi', 'cek', 'yaya'),
(4, 'anjay', 'hilda ', 'Pengaduan', 'panen', 'hasil panen yang tidak memuaskan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `survey_kepuasan`
--

CREATE TABLE `survey_kepuasan` (
  `id_survey` int(11) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `tgl_pengisian` date NOT NULL,
  `skor_penilaian` varchar(50) NOT NULL,
  `komentar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `survey_kepuasan`
--

INSERT INTO `survey_kepuasan` (`id_survey`, `nama_pengguna`, `tgl_pengisian`, `skor_penilaian`, `komentar`) VALUES
(2, 'anjay', '2024-12-27', '4', 'baik'),
(3, 'anjay', '2024-12-27', '3', 'puas'),
(4, 'anjay', '2024-12-27', '4', 'baikkk'),
(5, 'anjay', '2024-12-27', '5', 'baik sekalii cakep'),
(6, 'anjay', '2024-12-27', '3', 'cukup'),
(7, 'anjay', '2025-01-19', '5', 'sangat baik'),
(8, '', '2025-02-07', '5', 'mantap'),
(9, '', '2025-02-07', '4', 'aplikasi nya baik sekali sangat membantu'),
(10, '', '2025-02-09', '5', 'mantappp'),
(11, '', '2025-02-09', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `tlp` varchar(15) NOT NULL,
  `level` enum('admin','petugas','warga') NOT NULL,
  `nip` varchar(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `email`, `alamat`, `tlp`, `level`, `nip`) VALUES
(1, '3561234567894560', '0d337de5b17c8e47a868cecfcdc7c0fa', 'Faris Yahya', 'faris@gmail.com', 'Perum Citra Land B-2 ', '089765893443', 'warga', ''),
(2, '54321', '0192023a7bbd73250516f069df18b500', 'Administrator1', 'admin1@gmail.com', 'disini', '087675144322', 'admin', ''),
(3, 'administrator001', '21232f297a57a5a743894a0e4a801fc3', 'Administrator1', 'admin1@gmail.com', 'disini', '-', 'admin', ''),
(4, 'anjay', 'de12f5798f86bdcc5c759a645e913e4c', 'hilda ', 'hildanurfadilah0610@gmail.com', 'jl.handil bakti permai', '087867556456', 'warga', ''),
(5, 'hilda', 'ad31b478525413f0b1b1d8bf0aebeb7c', 'hilda nurfadilah', 'hildanurfadilah0610@gmail.com', 'jl.handil bakti permai', '087867556456', 'admin', ''),
(6, 'petugas', 'afb91ef692fd08c445e8cb1bab2ccf9c', 'rahmi', 'rahmi123@gmail.com', 'jl.purnas', '083114629451', 'petugas', '112212123454657898'),
(7, 'petugas1', '570c396b3fc856eceb8aa7357f32af1a', 'Siti Aminah', 'sitiaminah@gmail.com', 'Jalan Nuri no. 9', '087678987678', 'petugas', '356418279057438920'),
(8, 'petugas2', '6fb35e77d7c816fd0ee7c305e77a1156', 'Yayan', 'yayan@gmail.com', 'Disini', '0123', 'petugas', '123456789098765432'),
(9, 'warga1', 'de6e8d19a4c315b73b6784f1f69471c1', 'Warga sini', 'warga@gmail.con', 'disini', '0123', 'warga', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `administrasi`
--
ALTER TABLE `administrasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indeks untuk tabel `aturan_layanan`
--
ALTER TABLE `aturan_layanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- Indeks untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indeks untuk tabel `hasil_administrasi`
--
ALTER TABLE `hasil_administrasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `administrasiId` (`administrasiId`);

--
-- Indeks untuk tabel `hasil_pengaduan`
--
ALTER TABLE `hasil_pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengaduanId` (`pengaduanId`);

--
-- Indeks untuk tabel `hasil_pengaduan_alat_ikan`
--
ALTER TABLE `hasil_pengaduan_alat_ikan`
  ADD PRIMARY KEY (`id_pengaduan`);

--
-- Indeks untuk tabel `hasil_pengaduan_alat_tani`
--
ALTER TABLE `hasil_pengaduan_alat_tani`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengaduanID` (`pengaduanID`);

--
-- Indeks untuk tabel `hasil_pengajuan_izin`
--
ALTER TABLE `hasil_pengajuan_izin`
  ADD PRIMARY KEY (`id_pengajuan`);

--
-- Indeks untuk tabel `hasil_survey`
--
ALTER TABLE `hasil_survey`
  ADD PRIMARY KEY (`id_survey`);

--
-- Indeks untuk tabel `kepengurusan`
--
ALTER TABLE `kepengurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indeks untuk tabel `pengaduan_alat_ketahananpangan`
--
ALTER TABLE `pengaduan_alat_ketahananpangan`
  ADD PRIMARY KEY (`id_pengaduan`);

--
-- Indeks untuk tabel `pengaduan_alat_perikanan`
--
ALTER TABLE `pengaduan_alat_perikanan`
  ADD PRIMARY KEY (`id_pengaduan`);

--
-- Indeks untuk tabel `pengaduan_alat_pertanian`
--
ALTER TABLE `pengaduan_alat_pertanian`
  ADD PRIMARY KEY (`id_pengaduan`);

--
-- Indeks untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD PRIMARY KEY (`id_pengajuan`);

--
-- Indeks untuk tabel `permohonan_bantuan`
--
ALTER TABLE `permohonan_bantuan`
  ADD PRIMARY KEY (`id_bantuan`);

--
-- Indeks untuk tabel `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indeks untuk tabel `survey_kepuasan`
--
ALTER TABLE `survey_kepuasan`
  ADD PRIMARY KEY (`id_survey`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `administrasi`
--
ALTER TABLE `administrasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `aturan_layanan`
--
ALTER TABLE `aturan_layanan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `hasil_administrasi`
--
ALTER TABLE `hasil_administrasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `hasil_pengaduan`
--
ALTER TABLE `hasil_pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `hasil_pengaduan_alat_ikan`
--
ALTER TABLE `hasil_pengaduan_alat_ikan`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hasil_pengaduan_alat_tani`
--
ALTER TABLE `hasil_pengaduan_alat_tani`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hasil_pengajuan_izin`
--
ALTER TABLE `hasil_pengajuan_izin`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hasil_survey`
--
ALTER TABLE `hasil_survey`
  MODIFY `id_survey` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;

--
-- AUTO_INCREMENT untuk tabel `pengaduan_alat_ketahananpangan`
--
ALTER TABLE `pengaduan_alat_ketahananpangan`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pengaduan_alat_perikanan`
--
ALTER TABLE `pengaduan_alat_perikanan`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pengaduan_alat_pertanian`
--
ALTER TABLE `pengaduan_alat_pertanian`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `permohonan_bantuan`
--
ALTER TABLE `permohonan_bantuan`
  MODIFY `id_bantuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `survey_kepuasan`
--
ALTER TABLE `survey_kepuasan`
  MODIFY `id_survey` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `administrasi`
--
ALTER TABLE `administrasi`
  ADD CONSTRAINT `administrasi_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`username`);

--
-- Ketidakleluasaan untuk tabel `aturan_layanan`
--
ALTER TABLE `aturan_layanan`
  ADD CONSTRAINT `aturan_layanan_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id`);

--
-- Ketidakleluasaan untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`username`);

--
-- Ketidakleluasaan untuk tabel `hasil_administrasi`
--
ALTER TABLE `hasil_administrasi`
  ADD CONSTRAINT `hasil_administrasi_ibfk_1` FOREIGN KEY (`administrasiId`) REFERENCES `administrasi` (`id`);

--
-- Ketidakleluasaan untuk tabel `hasil_pengaduan`
--
ALTER TABLE `hasil_pengaduan`
  ADD CONSTRAINT `hasil_pengaduan_ibfk_1` FOREIGN KEY (`pengaduanId`) REFERENCES `pengaduan` (`id`);

--
-- Ketidakleluasaan untuk tabel `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD CONSTRAINT `pengaduan_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`username`);

--
-- Ketidakleluasaan untuk tabel `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
