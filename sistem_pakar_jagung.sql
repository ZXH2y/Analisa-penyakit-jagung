-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2025 at 10:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_pakar_jagung`
--

-- --------------------------------------------------------

--
-- Table structure for table `aturan`
--

CREATE TABLE `aturan` (
  `id_aturan` int(11) NOT NULL,
  `id_penyakit` varchar(10) DEFAULT NULL,
  `id_gejala` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ini di ambil berdasarka tabel III, rule di jurnal

INSERT INTO `aturan` (`id_aturan`, `id_penyakit`, `id_gejala`) VALUES
(1, 'P0001', 'G0001'),
(2, 'P0001', 'G0002'),
(3, 'P0001', 'G0003'),
(4, 'P0001', 'G0004'),
(5, 'P0001', 'G0005'),
(6, 'P0002', 'G0006'),
(7, 'P0002', 'G0007'),
(8, 'P0002', 'G0008'),
(9, 'P0002', 'G0009'),
(10, 'P0003', 'G0010'),
(11, 'P0003', 'G0011'),
(12, 'P0003', 'G0012'),
(13, 'P0004', 'G0013'),
(14, 'P0004', 'G0014'),
(15, 'P0004', 'G0015'),
(16, 'P0005', 'G0012'),
(17, 'P0005', 'G0016'),
(18, 'P0005', 'G0017'),
(19, 'P0006', 'G0004'),
(20, 'P0006', 'G0018');

-- --------------------------------------------------------

--
-- Table structure for table `gejala`
--

CREATE TABLE `gejala` (
  `id_gejala` varchar(10) NOT NULL,
  `nama_gejala` varchar(100) DEFAULT NULL,
  `nilai_pakar` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- di ambil dari tabel pakar untuk bobot nilainya
--

INSERT INTO `gejala` (`id_gejala`, `nama_gejala`, `nilai_pakar`) VALUES
('G0001', 'Daun berwarna kuning keputihan bergaris sejajar dengan urat daun', 1),
('G0002', 'Mengalami hambatan pertumbuhan', 0.6),
('G0003', 'Bagian bawah daun muncul konidia berwarna putih seperti butiran tepung', 0.6),
('G0004', 'Tanaman terlihat kerdil', 1),
('G0005', 'Pembentukan tongkol terganggu', 0.6),
('G0006', 'Bercak memanjang berbentuk elips', 0.8),
('G0007', 'Bercak-bercak kecil bersatu membentuk bercak yang lebih besar', 0.2),
('G0008', 'Hawar berwarna abu-abu seperti terbakar atau mengering', 1),
('G0009', 'Bercak kecil berbentuk oval pada daun', 0.8),
('G0010', 'Timbul bintik kecil pada permukaan atas dan bawah daun berwarna cokelat kemerahan', 0.2),
('G0011', 'Terdapat tepung berwarna cokelat kekuning-kuningan pada permukaan daun', 0.4),
('G0012', 'Daun layu dan kering', 0.6),
('G0013', 'Pembengkakan pada biji jagung', 0.4),
('G0014', 'Terdapat cendawan putih hingga kehitaman pada biji', 0.4),
('G0015', 'Bagian dalam biji berwarna gelap dan menjadi massa tepung berwarna cokelat gelap sampai hitam', 1),
('G0016', 'Bagian dalam batang busuk dan mudah rebah', 1),
('G0017', 'Bagian kulit luar tipis', 0.4),
('G0018', 'Daun berwarna mosaik atau hijau', 0.6);

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id_konsultasi` int(11) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `hasil_diagnosa` varchar(100) DEFAULT NULL,
  `tingkat_keyakinan` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konsultasi`
--

INSERT INTO `konsultasi` (`id_konsultasi`, `tanggal`, `hasil_diagnosa`, `tingkat_keyakinan`) VALUES
(1, '2025-01-21 10:00:00', 'Hawar Daun', 95.02),
(2, '2025-01-21 10:30:00', 'Penyakit Bulai', 80.5),
(3, '2025-01-21 12:06:01', 'P0001', 99.4376),
(4, '2025-01-21 14:11:44', 'P0001', 97.4441),
(5, '2025-01-21 14:13:46', 'P0001', 97.4441),
(6, '2025-01-21 14:13:56', 'P0001', 97.4441),
(7, '2025-01-21 14:15:00', 'P0001', 99.4376),
(8, '2025-01-21 14:22:02', 'P0002', 95.0246),
(9, '2025-01-21 14:33:17', 'P0001', 89.6),
(10, '2025-02-01 01:04:17', 'P0001', 99.4376),
(11, '2025-02-01 01:12:19', 'P0001', 99.4376),
(12, '2025-02-01 01:12:23', 'P0001', 99.4376),
(13, '2025-02-01 01:19:16', 'P0001', 99.4376),
(14, '2025-02-01 01:20:19', 'P0001', 99.4376),
(15, '2025-02-01 01:33:04', 'P0001', 99.4376),
(16, '2025-02-01 01:34:22', 'P0001', 99.4376),
(17, '2025-02-01 01:34:34', 'P0001', 99.4376),
(18, '2025-02-01 01:34:47', 'P0001', 99.4376),
(19, '2025-02-01 03:33:42', 'P0001', 99.4376),
(20, '2025-02-01 03:33:52', 'P0001', 99.4376),
(21, '2025-02-01 03:34:45', 'P0001', 99.4376),
(22, '2025-02-01 03:35:06', 'P0002', 97.8227),
(23, '2025-02-01 03:37:20', 'P0002', 97.8227),
(24, '2025-02-04 04:34:03', 'P0001', 99.4376),
(25, '2025-02-04 04:35:22', 'P0001', 99.4376),
(26, '2025-02-04 04:35:54', 'P0001', 99.4376),
(27, '2025-02-04 04:46:31', 'P0001', 99.4376),
(28, '2025-02-04 04:52:11', 'P0001', 99.4376),
(29, '2025-02-04 04:53:25', '', 0),
(30, '2025-02-04 04:59:14', '', 0),
(31, '2025-02-04 04:59:18', '', 0),
(32, '2025-02-04 04:59:24', '', 0),
(33, '2025-02-04 05:00:14', 'P0001', 48),
(34, '2025-02-04 05:00:34', '', 0),
(35, '2025-02-04 05:01:47', '', 0),
(36, '2025-02-04 05:01:51', '', 0),
(37, '2025-02-04 05:01:57', '', 0),
(38, '2025-02-04 05:02:48', '', 0),
(39, '2025-02-04 05:02:52', '', 0),
(40, '2025-02-04 05:13:02', '', 0),
(41, '2025-02-04 05:17:27', 'P0001', 84.1969),
(42, '2025-02-04 05:18:51', 'P0001', 95.8057),
(43, '2025-02-04 05:19:44', 'P0001', 95.8057);

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi_gejala`
--

CREATE TABLE `konsultasi_gejala` (
  `id_konsultasi` int(11) DEFAULT NULL,
  `id_gejala` varchar(10) DEFAULT NULL,
  `nilai_user` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- di ambil dari tabel cf user di jurnal
--

INSERT INTO `konsultasi_gejala` (`id_konsultasi`, `id_gejala`, `nilai_user`) VALUES
(1, 'G0006', 0.6),
(1, 'G0007', 0.4),
(1, 'G0008', 0.8),
(1, 'G0009', 0.6),
(2, 'G0001', 0.8),
(2, 'G0002', 0),
(2, 'G0003', 0);

-- --------------------------------------------------------

--
-- Table structure for table `penyakit`
--

CREATE TABLE `penyakit` (
  `id_penyakit` varchar(10) NOT NULL,
  `nama_penyakit` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `solusi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penyakit`
--

INSERT INTO `penyakit` (`id_penyakit`, `nama_penyakit`, `deskripsi`, `solusi`) VALUES
('P0001', 'Penyakit Bulai', 'Penyakit yang disebabkan oleh jamur yang menyerang daun.', 'Untuk mengatasi penyakit bulai pada jagung, gunakan benih tahan bulai yang telah diberi fungisida, lakukan rotasi tanaman dengan non-inang patogen, hindari penanaman di musim hujan lembap, aplikasikan fungisida jika diperlukan, dan segera cabut serta musnahkan tanaman yang terinfeksi untuk mencegah penyebaran.\r\n\r\n'),
('P0002', 'Hawar Daun', 'Penyakit yang menyebabkan daun mengering seperti terbakar.', 'Untuk mengatasi hawar daun pada tanaman, gunakan benih yang tahan penyakit, lakukan rotasi tanaman dengan jenis yang berbeda, hindari kelembapan berlebihan dengan mengatur jarak tanam dan drainase yang baik, aplikasikan fungisida sesuai rekomendasi jika gejala muncul, serta segera cabut dan musnahkan tanaman yang terinfeksi untuk mencegah penyebaran patogen.'),
('P0003', 'Karat Daun', 'Penyakit yang menyebabkan bintik-bintik karat pada daun.', 'Untuk mengatasi penyakit karat daun, gunakan varietas tanaman yang tahan terhadap karat, lakukan rotasi tanaman untuk memutus siklus hidup patogen, hindari kelembapan berlebihan dengan mengatur jarak tanam dan drainase yang baik, aplikasikan fungisida sesuai anjuran jika gejala muncul, serta segera cabut dan musnahkan bagian tanaman yang terinfeksi untuk mencegah penyebaran spora.'),
('P0004', 'Penyakit Gosong', 'Penyakit yang menyebabkan biji menjadi gelap dan massa tepung.', 'Untuk mengatasi penyakit gosong (smut) pada tanaman, gunakan benih yang sehat dan bebas patogen, lakukan perlakuan benih dengan fungisida sebelum ditanam, hindari penanaman di area yang sebelumnya terinfeksi, lakukan rotasi tanaman dengan jenis yang bukan inang patogen, serta segera cabut dan musnahkan tanaman atau bagian tanaman yang terinfeksi untuk mencegah penyebaran spora.'),
('P0005', 'Bakteri Busuk Batang', 'Penyakit yang menyebabkan batang membusuk dan mudah roboh.', 'Untuk mengatasi penyakit busuk batang yang disebabkan oleh bakteri, gunakan benih atau bibit yang sehat dan bebas patogen, hindari luka mekanis pada tanaman yang bisa menjadi pintu masuk bakteri, pastikan drainase lahan baik untuk menghindari genangan air, aplikasikan bakterisida jika diperlukan, serta segera cabut dan musnahkan tanaman yang terinfeksi untuk mencegah penyebaran bakteri ke tanaman sehat.\r\n\r\n'),
('P0006', 'Penyakit Virus Mosaik', 'Penyakit yang menyebabkan daun berwarna mosaik.', 'Untuk mengatasi penyakit virus mosaik, gunakan benih yang sehat dan bebas virus, kendalikan serangga vektor (seperti kutu daun) menggunakan insektisida atau cara organik, hindari penularan dengan membersihkan alat pertanian, cabut dan musnahkan tanaman yang terinfeksi, serta tanam varietas yang tahan virus untuk mengurangi risiko serangan.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aturan`
--
ALTER TABLE `aturan`
  ADD PRIMARY KEY (`id_aturan`),
  ADD KEY `id_penyakit` (`id_penyakit`),
  ADD KEY `id_gejala` (`id_gejala`);

--
-- Indexes for table `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id_gejala`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id_konsultasi`);

--
-- Indexes for table `konsultasi_gejala`
--
ALTER TABLE `konsultasi_gejala`
  ADD KEY `id_konsultasi` (`id_konsultasi`),
  ADD KEY `id_gejala` (`id_gejala`);

--
-- Indexes for table `penyakit`
--
ALTER TABLE `penyakit`
  ADD PRIMARY KEY (`id_penyakit`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aturan`
--
ALTER TABLE `aturan`
  MODIFY `id_aturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id_konsultasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aturan`
--
ALTER TABLE `aturan`
  ADD CONSTRAINT `aturan_ibfk_1` FOREIGN KEY (`id_penyakit`) REFERENCES `penyakit` (`id_penyakit`),
  ADD CONSTRAINT `aturan_ibfk_2` FOREIGN KEY (`id_gejala`) REFERENCES `gejala` (`id_gejala`);

--
-- Constraints for table `konsultasi_gejala`
--
ALTER TABLE `konsultasi_gejala`
  ADD CONSTRAINT `konsultasi_gejala_ibfk_1` FOREIGN KEY (`id_konsultasi`) REFERENCES `konsultasi` (`id_konsultasi`),
  ADD CONSTRAINT `konsultasi_gejala_ibfk_2` FOREIGN KEY (`id_gejala`) REFERENCES `gejala` (`id_gejala`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
