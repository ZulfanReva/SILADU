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
-- Struktur dari tabel `pengaduan_hama_penyakit_tanaman`
--

CREATE TABLE `pengaduan_hama_penyakit_tanaman` (
  `id_pengaduan` INT(11) NOT NULL AUTO_INCREMENT,
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
  `status` ENUM('Diproses','Direview','Diterima','Ditolak') NOT NULL,
  `catatan` TEXT NULL,
  PRIMARY KEY (`id_pengaduan`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Dumping data untuk tabel `pengaduan_hama_penyakit_tanaman`
--

-- Dummy data pengaduan_hama_penyakit_tanaman
INSERT INTO `pengaduan_hama_penyakit_tanaman` 
(`id_user`, `jenis_tanaman`, `jenis_hama_penyakit`, `alamat_pengadu`, `tgl_pengaduan`, `status`) VALUES
(1, 'Padi', 'Hama Wereng Hijau & Penyakit Tungro', 'Jl. Sawah Raya No. 12', '2025-02-15', 'Diproses');


-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_alat_perikanan`
--

CREATE TABLE `permohonan_alat_perikanan` (
  `id_permohonan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `tgl_permohonan` date NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `status` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL,
  `catatan` TEXT NULL,
  PRIMARY KEY (`id_permohonan`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan_alat_perikanan`
--

-- Dummy data permohonan_alat_perikanan
INSERT INTO `permohonan_alat_perikanan` 
(`id_user`, `jenis_alat`, `penyebab_kerusakan`, `permintaan`, `tgl_permohonan`, `path_gambar`, `status`) VALUES
(2, 'Jaring Pukat (Trawl)', 'robek', 'Ganti Baru', '2025-02-15', 'foto1.jpg', 'Diterima');


-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_alat_pertanian`
--

CREATE TABLE `permohonan_alat_pertanian` (
  `id_permohonan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `tgl_permohonan` date NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `status` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL,
  `catatan` TEXT NULL,
  PRIMARY KEY (`id_permohonan`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan_alat_pertanian`
--

-- Dummy data permohonan_alat_pertanian
INSERT INTO `permohonan_alat_pertanian` 
(`id_user`, `jenis_alat`, `penyebab_kerusakan`, `permintaan`, `tgl_permohonan`, `path_gambar`, `status`) VALUES
(3, 'Cangkul', 'patah', 'Perbaikan', '2025-02-15', 'cangkul1.jpg', 'Diterima');


-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_alat_ketahananpangan`
--

CREATE TABLE `permohonan_alat_ketahananpangan` (
  `id_permohonan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `jenis_alat` varchar(100) NOT NULL,
  `penyebab_kerusakan` varchar(150) NOT NULL,
  `permintaan` enum('Perbaikan','Ganti Baru') NOT NULL,
  `path_gambar` varchar(255) NOT NULL,
  `status` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL,
  `tgl_permohonan` date NOT NULL,
  `catatan` TEXT NULL,
  PRIMARY KEY (`id_permohonan`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan_alat_ketahananpangan`
--

-- Dummy data permohonan_alat_ketahananpangan
INSERT INTO `permohonan_alat_ketahananpangan` 
(`id_user`, `jenis_alat`, `penyebab_kerusakan`, `permintaan`, `path_gambar`, `status`, `tgl_permohonan`) VALUES
(4, 'Cold Storage', 'tidak dingin', 'Ganti Baru', 'cold1.jpg', 'Diterima', '2025-02-15');


-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_bantuan`
--
-- Dummy data permohonan_bantuan
CREATE TABLE IF NOT EXISTS `jenis_bantuan` (
  `id_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Buat tabel permohonan_bantuan
CREATE TABLE IF NOT EXISTS `permohonan_bantuan` (
  `id_bantuan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `kelurahan` varchar(255) NOT NULL,
  `kecamatan` varchar(255) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `id_jenis` int(11) NOT NULL,
  `status_pemohon` enum('Diproses','Direview','Diterima','Ditolak') NOT NULL,
  `tgl_permohonan` date NOT NULL,
  `catatan` TEXT NULL,
  PRIMARY KEY (`id_bantuan`),
  UNIQUE KEY `id_user` (`id_user`),
  FOREIGN KEY (`id_jenis`) REFERENCES `jenis_bantuan` (`id_jenis`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Isi data ke tabel jenis_bantuan
INSERT INTO `jenis_bantuan` (`nama_jenis`, `deskripsi`) VALUES
('Sandang', 'Bantuan untuk kebutuhan pakaian masyarakat kurang mampu.'),
('Pangan', 'Bantuan sembako seperti beras, minyak, dan gula untuk keluarga membutuhkan.'),
('Pertanian', 'Bantuan bibit, pupuk, dan alat untuk petani.'),
('UMKM', 'Bantuan modal, pelatihan, dan pendampingan untuk usaha kecil.'),
('Perikanan', 'Bantuan peralatan dan bibit ikan untuk nelayan.'),
('Bibit Ikan', 'Bantuan bibit ikan unggul untuk budidaya.');

-- Isi data ke tabel permohonan_bantuan
INSERT INTO `permohonan_bantuan` (`id_user`, `alamat`, `kelurahan`, `kecamatan`, `no_telp`, `id_jenis`, `status_pemohon`, `tgl_permohonan`, `catatan`) VALUES 
(1, 'Jl. Merdeka No. 123', 'Sukamaju', 'Cibaduyut', '081234567890', 2, 'Diproses', '2025-06-21', 'Menunggu verifikasi'),
(2, 'Jl. Sudirman No. 45', 'Cipete', 'Cilandak', '082345678901', 3, 'Direview', '2025-06-20', 'Dokumen diperiksa'),
(3, 'Jl. Gatot Subroto No. 78', 'Kebon Jeruk', 'Kebon Jeruk', '083456789012', 4, 'Diterima', '2025-06-19', 'Bantuan UMKM disetujui');

-- 7. Cek hasil
SELECT 
    pb.id_bantuan, u.nama, jb.nama_jenis, pb.status_pemohon, pb.tgl_permohonan
FROM 
    `permohonan_bantuan` pb
JOIN 
    `jenis_bantuan` jb ON pb.id_jenis = jb.id_jenis
JOIN 
    `user` u ON pb.id_user = u.id_user;


-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_uji_tanaman`
--

CREATE TABLE `permohonan_uji_tanaman` (
  `id_permohonan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `jenis_tanaman` varchar(255) NOT NULL,
  `alamat_pemohon` varchar(255) NOT NULL,
  `tgl_permohonan` date NOT NULL,
  `status` enum('Diproses','Direview','Diterima','Ditolak') NOT NULL,
  `catatan` TEXT NULL,
  PRIMARY KEY (`id_permohonan`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan_uji_tanaman`
--

-- Dummy data permohonan_uji_tanaman
INSERT INTO `permohonan_uji_tanaman` 
(`id_user`, `jenis_tanaman`, `alamat_pemohon`, `tgl_permohonan`, `status`) VALUES
(2, 'Padi', 'Jl. Padi No.1', '2025-02-15', 'Diproses');


-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_vaksinasi_hewan`
--

CREATE TABLE `permohonan_vaksinasi_hewan` (
  `id_permohonan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `jenis_ternak` enum('Sapi','Kambing','Ikan','Ayam','Burung') NOT NULL,
  `jumlah_ternak` int(11) NOT NULL,
  `jenis_vaksin` varchar(100) NOT NULL,
  `gejala` varchar(255) NOT NULL,
  `alamat_ternak` varchar(255) NOT NULL,
  `tgl_permohonan` date NOT NULL,
  `status` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL,
  `catatan` TEXT NULL,
  PRIMARY KEY (`id_permohonan`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `permohonan_vaksinasi_hewan` 
(`id_user`, `jenis_ternak`, `jumlah_ternak`, `jenis_vaksin`, `gejala`, `alamat_ternak`, `tgl_permohonan`, `status`) 
VALUES
(3, 'Sapi', 5, 'Anthrax', 'Demam tinggi, tidak mau makan', 'Desa Sukamaju, Kec. Tani Maju', '2025-02-15', 'Diproses');


-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `jenis_izin` varchar(100) NOT NULL,
  `dokumen` text NOT NULL,
  `kontak` varchar(100) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `status` enum('Diproses','Direview','Diterima', 'Ditolak') NOT NULL,
  `catatan` TEXT NULL,
  PRIMARY KEY (`id_pengajuan`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_izin`
--

-- Dummy data pengajuan_izin
INSERT INTO `pengajuan_izin` 
(`id_user`, `jenis_izin`, `dokumen`, `kontak`, `tgl_pengajuan`, `status`) VALUES
(1, 'Izin Usaha Tani', 'dok1.pdf', '081111111111', '2025-02-15', 'Diproses'),
(2, 'Izin Usaha Perikanan', 'dok2.pdf', '082222222222', '2025-02-16', 'Direview'),
(3, 'Izin Usaha Perkebunan', 'dok3.pdf', '083333333333', '2025-02-17', 'Diterima'),
(4, 'Izin Usaha UMKM', 'dok4.pdf', '084444444444', '2025-02-18', 'Ditolak');


-- --------------------------------------------------------

--
-- Struktur dari tabel `survei_kepuasan`
--

CREATE TABLE `survei_kepuasan` (
  `id_survei` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `tgl_survei` date NOT NULL,
  `skor` TINYINT NOT NULL,
  `pesan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_survei`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `survei_kepuasan`
--

INSERT INTO `survei_kepuasan` (`id_user`, `tgl_survei`, `skor`, `pesan`) VALUES
(1, '2025-02-14', 3, 'h'),
(2, '2025-02-15', 5, 'mantapppp'),
(3, '2025-02-15', 5, 'sngt  membantu'),
(4, '2025-02-15', 4, 'pelayanan baik');

-- --------------------------------------------------------

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `permohonan_alat_ketahananpangan`
--
ALTER TABLE `permohonan_alat_ketahananpangan`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `permohonan_alat_perikanan`
--
ALTER TABLE `permohonan_alat_perikanan`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `permohonan_alat_pertanian`
--
ALTER TABLE `permohonan_alat_pertanian`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pengaduan_hama_penyakit_tanaman`
--
ALTER TABLE `pengaduan_hama_penyakit_tanaman`
  MODIFY `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `permohonan_vaksinasi_hewan`
--
ALTER TABLE `permohonan_vaksinasi_hewan`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `permohonan_bantuan`
--
-- ALTER TABLE `permohonan_bantuan`
--   MODIFY `id_bantuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `permohonan_uji_tanaman`
--
ALTER TABLE `permohonan_uji_tanaman`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `permohonan_vaksinasi_hewan`
--
ALTER TABLE `permohonan_vaksinasi_hewan`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `survei_kepuasan`
--
ALTER TABLE `survei_kepuasan`
  MODIFY `id_survei` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `permohonan_alat_ketahananpangan`
--
ALTER TABLE `permohonan_alat_ketahananpangan`
  ADD CONSTRAINT `permohonan_alat_ketahananpangan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `permohonan_alat_perikanan`
--
ALTER TABLE `permohonan_alat_perikanan`
  ADD CONSTRAINT `permohonan_alat_perikanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `permohonan_alat_pertanian`
--
ALTER TABLE `permohonan_alat_pertanian`
  ADD CONSTRAINT `permohonan_alat_pertanian_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengaduan_hama_penyakit_tanaman`
--
ALTER TABLE `pengaduan_hama_penyakit_tanaman`
  ADD CONSTRAINT `pengaduan_hama_penyakit_tanaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `permohonan_vaksinasi_hewan`
--
-- ALTER TABLE `permohonan_vaksinasi_hewan`
--   ADD CONSTRAINT `permohonan_vaksinasi_hewan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD CONSTRAINT `pengajuan_izin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `permohonan_bantuan`
--
-- ALTER TABLE `permohonan_bantuan`
--   ADD CONSTRAINT `permohonan_bantuan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Ketidakleluasaan untuk tabel `survei_kepuasan`
--
ALTER TABLE `survei_kepuasan`
  ADD CONSTRAINT `survei_kepuasan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
