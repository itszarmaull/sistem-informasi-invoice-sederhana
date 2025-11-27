-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3310
-- Generation Time: Nov 25, 2025 at 06:52 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_surat_alumunium`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_barang`
--

CREATE TABLE `detail_barang` (
  `id` int(11) NOT NULL,
  `id_surat` int(11) DEFAULT NULL,
  `jenis_barang` varchar(255) DEFAULT NULL,
  `ukuran` varchar(100) DEFAULT NULL,
  `jumlah_barang` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `harga_total` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_barang`
--

INSERT INTO `detail_barang` (`id`, `id_surat`, `jenis_barang`, `ukuran`, `jumlah_barang`, `harga_satuan`, `harga_total`) VALUES
(18, 11, 'Pintu Dapur ', '1x2m', '1 Unit', 5000000.00, 5000000.00),
(19, 11, 'Jendela rumah ', '2x3', '2 Daun', 350000.00, 700000.00),
(20, 12, 'Pintu Dapur ', '1x2m', '5 Unit', 500000.00, 2500000.00),
(21, 12, 'Kaca Mati', '1x5m', '5 Unit', 2500000.00, 12500000.00);

-- --------------------------------------------------------

--
-- Table structure for table `surat_pengajuan`
--

CREATE TABLE `surat_pengajuan` (
  `id` int(11) NOT NULL,
  `nomor_surat` varchar(50) DEFAULT NULL,
  `lampiran` varchar(100) DEFAULT NULL,
  `kepada` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `grand_total` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surat_pengajuan`
--

INSERT INTO `surat_pengajuan` (`id`, `nomor_surat`, `lampiran`, `kepada`, `tanggal`, `grand_total`, `created_at`) VALUES
(11, '002/PJ/XI/2025', 'Pengajuan Harga', 'PT ANGIN RIBUT ', '2025-11-25', 5700000.00, '2025-11-25 13:33:26'),
(12, '003/PJ/XI/2025', 'Pengajuan Harga', 'PT Lengkong ', '2025-11-25', 15000000.00, '2025-11-25 13:34:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'Owner'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(2, 'admin', '$2y$10$PfXjTs.sO1.7.1.7.1.7.e.X.X.X.X', 'Administrator', 'Super Admin'),
(3, 'yasir', '$2y$10$.vcGBIG9/tRc8fbwnT25luIkPSUCutlzCwGfqYImLU4.HFT8JTMJ6', 'Yasir', 'Owner');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_barang`
--
ALTER TABLE `detail_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_surat` (`id_surat`);

--
-- Indexes for table `surat_pengajuan`
--
ALTER TABLE `surat_pengajuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_barang`
--
ALTER TABLE `detail_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `surat_pengajuan`
--
ALTER TABLE `surat_pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_barang`
--
ALTER TABLE `detail_barang`
  ADD CONSTRAINT `detail_barang_ibfk_1` FOREIGN KEY (`id_surat`) REFERENCES `surat_pengajuan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
