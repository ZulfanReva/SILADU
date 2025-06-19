-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Bulan Mei 2025 pada 15.57
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
-- Database: `siladu2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_alat_ketahananpangan`
--

CREATE TABLE `pengaduan_alat_ketahananpangan` (
  `id_pengaduan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL,
  `tgl_pengaduan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_alat_ketahananpangan`
--

INSERT INTO `pengaduan_alat_ketahananpangan` 
(`id_pengaduan`, `id_user`, `jenis_alat`, `penyebab_kerusakan`, `permintaan`, `path_gambar`, `stts_pengaduan`, `tgl_pengaduan`)
VALUES
(1, 1, 'Cold Storage', 'ero', 'Ganti Baru', '1739623109_foto cangkul patah.jpg', 'Diterima', '2025-02-15'),
(2, 2, 'Alat Penggiling Padi', 'taktau', 'Perbaikan', '1739623146_alat penggiling padi.jpg', 'Ditolak', '2025-02-15'),
(3, 3, 'Alat Penggiling Padi', 'tau', 'Ganti Baru', '1739623170_alat penggiling padi.jpg', 'Diterima', '2025-02-15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_alat_perikanan`
--

CREATE TABLE `pengaduan_alat_perikanan` (
  `id_pengaduan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_alat_perikanan`
--

INSERT INTO `pengaduan_alat_perikanan` 
(`id_pengaduan`, `id_user`, `jenis_alat`, `penyebab_kerusakan`, `permintaan`, `tgl_pengaduan`, `path_gambar`, `stts_pengaduan`) VALUES
(1, 1, 'Jaring Pukat (Trawl)', 'teshy', 'Ganti Baru', '2025-02-15', '1739619552_foto kerusakan jaring gill net.jpg', 'Diterima'),
(2, 2, 'Kincir Air', 'gatau', 'Perbaikan', '2025-02-15', '1739619588_kincir air.jpeg', 'Ditolak'),
(3, 3, 'Jaring Pukat (Trawl)', 'sobek', 'Perbaikan', '2025-02-15', '1739619617_9c0b7e8fa298bb3ff5879e263a3e9606.png', 'Ditolak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_alat_pertanian`
--

CREATE TABLE `pengaduan_alat_pertanian` (
  `id_pengaduan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_alat_pertanian`
--

INSERT INTO `pengaduan_alat_pertanian` 
(`id_pengaduan`, `id_user`, `jenis_alat`, `penyebab_kerusakan`, `permintaan`, `tgl_pengaduan`, `path_gambar`, `stts_pengaduan`) VALUES
(1, 1, 'Cangkul', 'ek', 'Perbaikan', '2025-02-16', '1739613655_download.jpg', 'Diterima'),
(2, 2, 'Cangkul', 'patah', 'Ganti Baru', '2025-02-15', '1739619718_foto cangkul patah.jpg', 'Diterima'),
(3, 3, 'Bajak Singkal', 'kelepas', 'Perbaikan', '2025-02-15', '1739619746_bajak singkal.jpg', 'Ditolak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_hama_penyakit_tanaman`
--

CREATE TABLE `pengaduan_hama_penyakit_tanaman` (
  `id_pengaduan` INT(11) NOT NULL,
  `id_user` INT(11) NOT NULL,
  `jenis_tanaman` ENUM('Padi','Cabai','Tomat','Terong','Bayam','Kangkung') NOT NULL,
  `jenis_hama_penyakit` ENUM(
    'Hama Wereng Hijau & Penyakit Tungro',
    'Hama Kutu Daun & Penyakit Antraknosa',
    'Hama Lalat Buah & Penyakit Layu Bakteri',
    'Hama Trips & Penyakit Kutu Kebul',
    'Hama Ulat Grayak & Penyakit Bercak Daun',
    'Hama Belalang & Penyakit Layu Fusarium'
  ) NOT NULL,
  `alamat_pengadu` VARCHAR(255) NOT NULL,
  `tgl_pengaduan` DATE NOT NULL,
  `status` ENUM('Diproses','Direview','Diterima','Ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Dumping data untuk tabel `pengaduan_hama_penyakit_tanaman`
--

INSERT INTO `pengaduan_hama_penyakit_tanaman` 
(`id_pengaduan`, `id_user`, `jenis_tanaman`, `jenis_hama_penyakit`, `alamat_pengadu`, `tgl_pengaduan`, `status`) VALUES
(1, 1, 'Padi', 'Hama Wereng Hijau & Penyakit Tungro', 'Jl. Sawah Raya No. 12', '2025-02-15', 'Diproses'),
(2, 2, 'Cabai', 'Hama Kutu Daun & Penyakit Antraknosa', 'Desa Tani Makmur', '2025-02-16', 'Diterima'),
(3, 3, 'Tomat', 'Hama Lalat Buah & Penyakit Layu Bakteri', 'RT 03 RW 01 Kampung Sayur', '2025-02-17', 'Ditolak');


-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_kesehatan_ternak`
--

CREATE TABLE `pengaduan_kesehatan_ternak` (
  `id_pengaduan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_ternak` enum('Sapi','Kambing','Ikan','Ayam','Burung') NOT NULL,
  `gejala` varchar(255) NOT NULL,
  `alamat_ternak` varchar(255) NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `status` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pengaduan_kesehatan_ternak` 
(`id_pengaduan`, `id_user`, `jenis_ternak`, `gejala`, `alamat_ternak`, `tgl_pengaduan`, `status`) 
VALUES
(1, 1, 'Sapi', 'Demam tinggi, tidak mau makan', 'Desa Sukamaju, Kec. Tani Maju', '2025-02-15', 'Diproses'),
(2, 2, 'Kambing', 'Luka di kaki, jalan pincang', 'Jl. Peternakan No. 12, Desa Suka', '2025-02-15', 'Direview'),
(3, 3, 'Ayam', 'Nafas tersengal, bulu rontok', 'RT 03 RW 01, Kampung Ayam', '2025-02-16', 'Diterima');


-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `id_pengajuan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_izin` varchar(100) NOT NULL,
  `dokumen` text NOT NULL,
  `kontak` varchar(100) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `status_pemohon` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` 
(`id_pengajuan`, `id_user`, `jenis_izin`, `dokumen`, `kontak`, `tgl_pengajuan`, `status_pemohon`) VALUES
(1, 1, 'Izin Usaha Budidaya Tanaman Pangan dan Hortikultura', '1739620147_1739605476_1739392335_kosong.pdf', '1213dfd', '2025-02-15', 'Diterima'),
(2, 2, 'Izin Usaha Perikanan', '1739620116_1739605476_1739392335_kosong.pdf', 's', '2025-02-15', 'Ditolak'),
(3, 3, 'Izin Usaha Perkebunan', '1739676615_1739605476_1739392335_kosong.pdf', '123', '2025-02-16', 'Diterima');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_bantuan`
--

CREATE TABLE `permohonan_bantuan` (
  `id_bantuan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
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

INSERT INTO `permohonan_bantuan` 
(`id_bantuan`, `id_user`, `nik`, `alamat`, `no_telp`, `jenis_bantuan`, `deskripsi_bantuan`, `status_pemohon`, `tgl_pengajuan`, `tgl_persetujuan`, `catatan_admin`) VALUES
(1, 1, '3275012301123456', 'Jl. Merdeka No. 10, Bandung', '081234567890', 'Pangan', 'Permohonan bantuan sembako untuk keluarga kurang mampu.', 'Disetujui', '2025-01-10', '2025-01-15', 'Disetujui setelah verifikasi data.'),
(2, 2, '3275021401987654', 'Desa Sukamaju, Cianjur', '082345678901', 'Pertanian', 'Butuh bantuan alat semprot hama dan bibit padi.', 'Diproses', '2025-02-01', '2025-02-10', 'Masih dalam tahap verifikasi lapangan.'),
(3, 3, '3275030703891234', 'Kp. Nelayan, Indramayu', '083456789012', 'Perikanan', 'Memohon bantuan peralatan jaring ikan.', 'Ditolak', '2025-03-05', '2025-03-12', 'Ditolak karena tidak memenuhi kriteria program.');


-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_uji_tanaman`
--

CREATE TABLE `permohonan_uji_tanaman` (
  `id_permohonan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_tanaman` varchar(255) NOT NULL,
  `alamat_pemohon` varchar(255) NOT NULL,
  `tgl_permohonan` date NOT NULL,
  `status` enum('Diproses','Direview','Diterima','Ditolak') NOT NULL,
  `tgl_uji` date NOT NULL,
  `hasil_uji` varchar(255) NOT NULL,
  `keterangan_petugas` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan_uji_tanaman`
--

INSERT INTO `permohonan_uji_tanaman` 
(`id_permohonan`, `id_user`, `jenis_tanaman`, `alamat_pemohon`, `tgl_permohonan`, `status`, `tgl_uji`, `hasil_uji`, `keterangan_petugas`)
VALUES
(1, 1, 'Padi Ciherang', 'Desa Mekarsari, Kabupaten Bogor', '2025-02-01', 'Diterima', '2025-02-10', 'Tahan terhadap hama wereng', 'Tanaman layak untuk dikembangkan.'),
(2, 2, 'Jagung Hibrida', 'Jl. Pertanian No. 12, Garut', '2025-03-05', 'Diproses', '2025-03-12', 'Masih menunggu hasil uji laboratorium', 'Sampel diterima, sedang dianalisis.'),
(3, 3, 'Kedelai Anjasmoro', 'Kp. Tani, Cirebon', '2025-04-01', 'Ditolak', '2025-04-08', 'Data spesimen tidak valid', 'Permohonan ditolak karena data tidak lengkap.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_vaksinasi_hewan`
--

CREATE TABLE `permohonan_vaksinasi_hewan` (
  `id_permohonan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_ternak` enum('Sapi','Kambing','Domba','Ayam','Bebek') NOT NULL,
  `jumlah_ternak` varchar(255) NOT NULL,
  `jenis_vaksin` varchar(255) NOT NULL,
  `alamat_pemohon` varchar(255) NOT NULL,
  `status` enum('Diproses','Direview','Diterima','Ditolak') NOT NULL,
  `tgl_permohonan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan_vaksinasi_hewan`
--

INSERT INTO `permohonan_vaksinasi_hewan`
(`id_permohonan`, `id_user`, `jenis_ternak`, `jumlah_ternak`, `jenis_vaksin`, `alamat_pemohon`, `status`, `tgl_permohonan`)
VALUES
(1, 1, 'Sapi', '10 ekor', 'Vaksin PMK', 'Desa Ternak Sejahtera, Kabupaten Sumedang', 'Diterima', '2025-01-20'),
(2, 2, 'Ayam', '200 ekor', 'Vaksin ND (Newcastle Disease)', 'Kampung Ayam Jaya, Kabupaten Bandung', 'Diproses', '2025-02-10'),
(3, 3, 'Kambing', '15 ekor', 'Vaksin Clostridial', 'Jl. Peternakan No. 5, Garut', 'Ditolak', '2025-03-01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `survey_kepuasan`
--

CREATE TABLE `survey_kepuasan` (
  `id_survey` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tgl_pengisian` date NOT NULL,
  `skor_penilaian` varchar(50) NOT NULL,
  `komentar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `survey_kepuasan`
--

INSERT INTO `survey_kepuasan` (`id_survey`, `id_user`, `tgl_pengisian`, `skor_penilaian`, `komentar`) VALUES
(1, 1, '2025-02-14', '3', 'h'),
(2, 2, '2025-02-15', '5', 'mantapppp'),
(3, 3, '2025-02-15', '5', 'sngt  membantu'),
(4, 4, '2025-02-15', '4', 'pelayanan baik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `tlp` varchar(15) NOT NULL,
  `level` enum('warga','admin','petugas','kepala_dinas') NOT NULL,
  `nip` varchar(18) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Dumping data untuk tabel `user`
--
-- Password default untuk semua user: 12345678
-- Hash dihasilkan dari: password_hash("12345678", PASSWORD_DEFAULT)
-- Kamu bisa pakai hash ini secara langsung:

-- $2y$10$e0WzZkF9Z6nXG4XNT3OnAu3NnkKJqVCHNgX7ZnUM3IhI/FiEoKlWa

INSERT INTO `user` 
(`id_user`, `username`, `password`, `nama`, `email`, `alamat`, `tlp`, `level`, `nip`)
VALUES
(1, 'wargates', '$2y$10$e0WzZkF9Z6nXG4XNT3OnAu3NnkKJqVCHNgX7ZnUM3IhI/FiEoKlWa', 'Andi Warga', 'andi@example.com', 'Jl. Warga Sejahtera No.1', '081234567890', 'warga', NULL),
(2, 'petugastes', '$2y$10$e0WzZkF9Z6nXG4XNT3OnAu3NnkKJqVCHNgX7ZnUM3IhI/FiEoKlWa', 'Budi Petugas', 'budi@example.com', 'Kantor Pertanian Kab. A', '082345678901', 'petugas', '198765432109876543'),
(3, 'kepaladinastes', '$2y$10$e0WzZkF9Z6nXG4XNT3OnAu3NnkKJqVCHNgX7ZnUM3IhI/FiEoKlWa', 'Citra Kadis', 'citra@example.com', 'Dinas Pertanian Provinsi', '083456789012', 'kepala_dinas', '123456789012345678'),
(4, 'admintes', '$2y$10$e0WzZkF9Z6nXG4XNT3OnAu3NnkKJqVCHNgX7ZnUM3IhI/FiEoKlWa', 'Dewi Admin', 'dewi@example.com', 'Komplek Pemerintahan', '084567890123', 'admin', 'F9J8H7K');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pengaduan_alat_ketahananpangan`
--
ALTER TABLE `pengaduan_alat_ketahananpangan`
  ADD PRIMARY KEY (`id_pengaduan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pengaduan_alat_perikanan`
--
ALTER TABLE `pengaduan_alat_perikanan`
  ADD PRIMARY KEY (`id_pengaduan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pengaduan_alat_pertanian`
--
ALTER TABLE `pengaduan_alat_pertanian`
  ADD PRIMARY KEY (`id_pengaduan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pengaduan_hama_penyakit_tanaman`
--
ALTER TABLE `pengaduan_hama_penyakit_tanaman`
  ADD PRIMARY KEY (`id_pengaduan`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pengaduan_kesehatan_ternak`
--
ALTER TABLE `pengaduan_kesehatan_ternak`
  ADD PRIMARY KEY (`id_pengaduan`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `permohonan_bantuan`
--
ALTER TABLE `permohonan_bantuan`
  ADD PRIMARY KEY (`id_bantuan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `permohonan_uji_tanaman`
--
ALTER TABLE `permohonan_uji_tanaman`
  ADD PRIMARY KEY (`id_permohonan`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `permohonan_vaksinasi_hewan`
--
ALTER TABLE `permohonan_vaksinasi_hewan`
  ADD PRIMARY KEY (`id_permohonan`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `survey_kepuasan`
--
ALTER TABLE `survey_kepuasan`
  ADD PRIMARY KEY (`id_survey`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `user` -> sudah ada didalam bagian create tablenya
--
-- ALTER TABLE `user`
--   ADD PRIMARY KEY (`id_user`);
ALTER TABLE `user` AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pengaduan_alat_ketahananpangan`
--
ALTER TABLE `pengaduan_alat_ketahananpangan`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `pengaduan_alat_perikanan`
--
ALTER TABLE `pengaduan_alat_perikanan`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `pengaduan_alat_pertanian`
--
ALTER TABLE `pengaduan_alat_pertanian`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pengaduan_hama_penyakit_tanaman`
--
ALTER TABLE `pengaduan_hama_penyakit_tanaman`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengaduan_kesehatan_ternak`
--
ALTER TABLE `pengaduan_kesehatan_ternak`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `permohonan_bantuan`
--
ALTER TABLE `permohonan_bantuan`
  MODIFY `id_bantuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `permohonan_uji_tanaman`
--
ALTER TABLE `permohonan_uji_tanaman`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `permohonan_vaksinasi_hewan`
--
ALTER TABLE `permohonan_vaksinasi_hewan`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `survey_kepuasan`
--
ALTER TABLE `survey_kepuasan`
  MODIFY `id_survey` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pengaduan_alat_ketahananpangan`
--
ALTER TABLE `pengaduan_alat_ketahananpangan`
  ADD CONSTRAINT `pengaduan_alat_ketahananpangan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengaduan_alat_perikanan`
--
ALTER TABLE `pengaduan_alat_perikanan`
  ADD CONSTRAINT `pengaduan_alat_perikanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengaduan_alat_pertanian`
--
ALTER TABLE `pengaduan_alat_pertanian`
  ADD CONSTRAINT `pengaduan_alat_pertanian_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengaduan_hama_penyakit_tanaman`
--
ALTER TABLE `pengaduan_hama_penyakit_tanaman`
  ADD CONSTRAINT `pengaduan_hama_penyakit_tanaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `pengaduan_kesehatan_ternak`
--
ALTER TABLE `pengaduan_kesehatan_ternak`
  ADD CONSTRAINT `pengaduan_kesehatan_ternak_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD CONSTRAINT `pengajuan_izin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `permohonan_bantuan`
--
ALTER TABLE `permohonan_bantuan`
  ADD CONSTRAINT `permohonan_bantuan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `permohonan_uji_tanaman`
--
ALTER TABLE `permohonan_uji_tanaman`
  ADD CONSTRAINT `permohonan_uji_tanaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `permohonan_vaksinasi_hewan`
--
ALTER TABLE `permohonan_vaksinasi_hewan`
  ADD CONSTRAINT `permohonan_vaksinasi_hewan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `survey_kepuasan`
--
ALTER TABLE `survey_kepuasan`
  ADD CONSTRAINT `survey_kepuasan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
