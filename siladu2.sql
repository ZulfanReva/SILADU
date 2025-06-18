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
  `waktu_kerusakan` date NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Pending','Disetujui','Ditolak') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `keterangan_petugas` varchar(200) NOT NULL,
  `catatan_admin` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_alat_ketahananpangan`
--

INSERT INTO `pengaduan_alat_ketahananpangan` (`id_pengaduan`, `id_user`, `jenis_alat`, `waktu_kerusakan`, `penyebab_kerusakan`, `permintaan`, `path_gambar`, `stts_pengaduan`, `tgl_pengaduan`, `tgl_selesai`, `keterangan_petugas`, `catatan_admin`) VALUES
(9, 4, 'Cold Storage', '2025-02-14', 'ero', 'Ganti Baru', '1739623109_foto cangkul patah.jpg', 'Disetujui', '2025-02-15', '2025-02-15', '1739623276_petugas_foto cangkul patah.jpg', 'foto alat sudah diperbaiki'),
(10, 4, 'Alat Penggiling Padi', '2025-02-12', 'taktau', 'Perbaikan', '1739623146_alat penggiling padi.jpg', 'Ditolak', '2025-02-15', '2025-02-15', '1739623336_petugas_alat penggiling padi.jpg', 'ditolak'),
(11, 4, 'Alat Penggiling Padi', '2025-02-12', 'tau', 'Ganti Baru', '1739623170_alat penggiling padi.jpg', 'Disetujui', '2025-02-15', '0000-00-00', '1739623600_petugas_alat penggiling padi.jpg', 'disetujui');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_alat_perikanan`
--

CREATE TABLE `pengaduan_alat_perikanan` (
  `id_pengaduan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `waktu_kerusakan` date NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Pending','Disetujui','Ditolak') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `keterangan_petugas` varchar(200) NOT NULL,
  `catatan_admin` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_alat_perikanan`
--

INSERT INTO `pengaduan_alat_perikanan` (`id_pengaduan`, `id_user`, `jenis_alat`, `waktu_kerusakan`, `penyebab_kerusakan`, `permintaan`, `path_gambar`, `stts_pengaduan`, `tgl_pengaduan`, `tgl_selesai`, `keterangan_petugas`, `catatan_admin`) VALUES
(6, 4, 'Jaring Pukat (Trawl)', '2025-02-14', 'teshy', 'Ganti Baru', '1739619552_foto kerusakan jaring gill net.jpg', 'Disetujui', '2025-02-15', '2025-02-27', '1739615439_petugas_download.jpg', 'foto alat sudah diperbaiki'),
(9, 4, 'Kincir Air', '2025-02-13', 'gatau', 'Perbaikan', '1739619588_kincir air.jpeg', 'Ditolak', '2025-02-15', '2025-02-15', '1739620873_petugas_kincir air.jpeg', 'alat tidak bisa diperbaiki'),
(10, 4, 'Jaring Pukat (Trawl)', '2025-02-13', 'sobek', 'Perbaikan', '1739619617_9c0b7e8fa298bb3ff5879e263a3e9606.png', 'Ditolak', '2025-02-15', '2025-02-15', '1739620905_petugas_foto kerusakan jaring gill net.jpg', 'gabisa diganti'),
(11, 4, 'pH Meter dan DO Meter', '2025-02-13', 'entah', 'Ganti Baru', '1739619649_timbangan digital pangan rusak.jpg', 'Disetujui', '2025-02-15', '2025-02-15', '1739620931_petugas_timbangan digital pangan rusak.jpg', 'timbangan sudah diganti baru'),
(12, 4, 'Kincir Air', '2025-02-13', 'rusak', 'Ganti Baru', '1739677037_kincir air.jpeg', 'Disetujui', '2025-02-16', '2025-02-16', '1739677222_petugas_kincir air.jpeg', 'okeu'),
(13, 4, 'Jaring Gill Net', '2025-02-17', 'rusakk', 'Perbaikan', '1739762660_foto kerusakan jaring gill net.jpg', 'Disetujui', '2025-02-17', '2025-02-17', '1739762711_petugas_foto kerusakan jaring gill net.jpg', 'sipp');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_alat_pertanian`
--

CREATE TABLE `pengaduan_alat_pertanian` (
  `id_pengaduan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `waktu_kerusakan` date NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `stts_pengaduan` enum('Pending','Disetujui','Ditolak') NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `keterangan_petugas` varchar(200) NOT NULL,
  `catatan_admin` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_alat_pertanian`
--

INSERT INTO `pengaduan_alat_pertanian` (`id_pengaduan`, `id_user`, `jenis_alat`, `waktu_kerusakan`, `penyebab_kerusakan`, `permintaan`, `path_gambar`, `stts_pengaduan`, `tgl_pengaduan`, `tgl_selesai`, `keterangan_petugas`, `catatan_admin`) VALUES
(7, 4, 'Cangkul', '2025-02-15', 'ek', 'Perbaikan', '1739613655_download.jpg', 'Disetujui', '2025-02-16', '2025-02-15', '1739615572_petugas_download.jpg', 'cangkul sudah diperbaiki'),
(8, 4, 'Cangkul', '2025-02-13', 'patah', 'Ganti Baru', '1739619718_foto cangkul patah.jpg', 'Disetujui', '2025-02-15', '2025-02-15', '1739620724_petugas_foto cangkul patah.jpg', 'sudah diganti baru'),
(9, 4, 'Bajak Singkal', '2025-02-11', 'kelepas', 'Perbaikan', '1739619746_bajak singkal.jpg', 'Ditolak', '2025-02-15', '2025-02-14', '1739620747_petugas_bajak singkal.jpg', 'tolak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaduan_hama_penyakit_tanaman`
--

CREATE TABLE `pengaduan_hama_penyakit_tanaman` (
  `id_pengaduan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_tanaman` enum('Padi','Cabai','Tomat','Terong','Bayam','Kangkung') NOT NULL,
  `jenis_hama_penyakit` enum('Hama Wereng Hijau,Penyakit Tungro','Hama Kutu Daun, Penyakit Antraknosa','Hama Lalat Buah, Penyakit Layu Bakteri','Hama Trips, Penyakit Kutu Kebul','Hama Ulat Grayak, Penyakit Bercak Daun','Hama Belalang, Penyakit Layu Fusarium') NOT NULL,
  `alamat_pengadu` varchar(255) NOT NULL,
  `tgl_pengaduan` date NOT NULL,
  `status` enum('Menunggu','Diproses','Selesai') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaduan_hama_penyakit_tanaman`
--

INSERT INTO `pengaduan_hama_penyakit_tanaman` (`id_pengaduan`, `id_user`, `jenis_tanaman`, `jenis_hama_penyakit`, `alamat_pengadu`, `tgl_pengaduan`, `status`) VALUES
(1, 4, 'Cabai', '', 'banjar raya', '2025-05-29', 'Menunggu');

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
  `status` enum('Menunggu','Diproses','Selesai') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `status_pemohon` enum('Menunggu','Diproses','Disetujui','Ditolak') NOT NULL,
  `catatan_admin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` (`id_pengajuan`, `id_user`, `jenis_izin`, `dokumen`, `kontak`, `tgl_pengajuan`, `status_pemohon`, `catatan_admin`) VALUES
(7, 4, 'Izin Usaha Budidaya Tanaman Pangan dan Hortikultura', '1739620147_1739605476_1739392335_kosong.pdf', '1213dfd', '2025-02-15', 'Disetujui', 'diterima'),
(8, 4, 'Izin Usaha Budidaya Tanaman Pangan dan Hortikultura', '1739620070_1739605476_1739392335_kosong.pdf', 'wwdce', '2025-02-15', 'Disetujui', 'okeh'),
(9, 4, 'Izin Usaha Budidaya Tanaman Pangan dan Hortikultura', '1739620093_1739605476_1739392335_kosong.pdf', '32', '2025-02-15', 'Ditolak', 'tolak'),
(10, 4, 'Izin Usaha Perikanan', '1739620116_1739605476_1739392335_kosong.pdf', 's', '2025-02-15', 'Ditolak', 'shapp'),
(11, 4, 'Izin Usaha Perkebunan', '1739676615_1739605476_1739392335_kosong.pdf', '123', '2025-02-16', 'Disetujui', 'okey');

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

INSERT INTO `permohonan_bantuan` (`id_bantuan`, `id_user`, `nik`, `alamat`, `no_telp`, `jenis_bantuan`, `deskripsi_bantuan`, `status_pemohon`, `tgl_pengajuan`, `tgl_persetujuan`, `catatan_admin`) VALUES
(7, 4, '123', 'qw', 'qw', 'Pangan', 'qwft', 'Disetujui', '2025-02-15', '2025-02-15', 'setuju'),
(8, 4, '123', 'ss', '123', 'Pangan', 'ss', 'Ditolak', '2025-02-15', '2025-02-15', 'tolak'),
(9, 4, '123', 'jl semangat', '082345719812', 'Pertanian', 'minta pupuk', 'Ditolak', '2025-02-15', '2025-02-15', 'habis'),
(10, 4, '123', 'jl tau', '082345719812', 'Perikanan', 'bibit ikan', 'Disetujui', '2025-02-15', '2025-02-15', 'shapp'),
(11, 4, '123', 'jlder', '082345719812', 'Perikanan', 'bibit ikan nila', 'Disetujui', '2025-02-15', '2025-02-15', 'setuju'),
(12, 4, '123', 'bgh', '123', 'Perikanan', 'barang ja', 'Ditolak', '2025-02-16', '2025-02-16', 'tolak');

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
  `status` enum('Diajukan','Diverifikasi','Dijadwalkan','Selesai') NOT NULL,
  `tgl_uji` date NOT NULL,
  `hasil_uji` varchar(255) NOT NULL,
  `keterangan_petugas` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan_uji_tanaman`
--

INSERT INTO `permohonan_uji_tanaman` (`id_permohonan`, `id_user`, `jenis_tanaman`, `alamat_pemohon`, `tgl_permohonan`, `status`, `tgl_uji`, `hasil_uji`, `keterangan_petugas`) VALUES
(1, 4, 'cabai', 'gambut', '2025-05-29', 'Diajukan', '0000-00-00', '', '');

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
  `status` enum('Diajukan','Diverifikasi','Dijadwalkan','Selesai') NOT NULL,
  `tgl_permohonan` date NOT NULL,
  `tgl_selesai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan_vaksinasi_hewan`
--

INSERT INTO `permohonan_vaksinasi_hewan` (`id_permohonan`, `id_user`, `jenis_ternak`, `jumlah_ternak`, `jenis_vaksin`, `alamat_pemohon`, `status`, `tgl_permohonan`, `tgl_selesai`) VALUES
(1, 4, 'Sapi', '5', 'vaksin PMK', 'gambut', 'Diajukan', '2025-05-29', '0000-00-00');

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
(1, 4, '2025-02-14', '3', 'h'),
(2, 4, '2025-02-15', '5', 'mantapppp'),
(3, 4, '2025-02-15', '5', 'sngt  membantu'),
(4, 4, '2025-02-15', '4', 'pelayanan baik'),
(5, 4, '2025-02-17', '5', 'sangat bagus'),
(6, 9, '2025-02-17', '5', 'okeh');

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
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

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
