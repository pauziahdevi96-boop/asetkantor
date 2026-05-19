asetkantorasetkantor-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2026 at 10:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asetkantor`
--

-- --------------------------------------------------------

--
-- Table structure for table `aset`
--

CREATE TABLE `aset` (
  `id_aset` int(11) NOT NULL,
  `kode_aset` varchar(50) NOT NULL,
  `nama_aset` varchar(150) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `merk` varchar(100) DEFAULT NULL,
  `tahun_perolehan` year(4) DEFAULT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') DEFAULT 'Baik',
  `nilai_aset` decimal(15,2) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `id_ruangan` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aset`
--

INSERT INTO `aset` (`id_aset`, `kode_aset`, `nama_aset`, `kategori`, `merk`, `tahun_perolehan`, `kondisi`, `nilai_aset`, `barcode`, `id_ruangan`, `created_at`, `updated_at`) VALUES
(1, 'AST001', 'Komputer Desktop', 'Elektronik', 'Dell', '2022', 'Rusak Berat', 8500000.00, NULL, 1, '2026-04-26 13:11:09', '2026-04-27 08:35:20'),
(2, 'AST002', 'Laptop', 'Elektronik', 'HP', '2023', 'Baik', 12000000.00, NULL, 1, '2026-04-26 13:11:09', '2026-04-26 19:47:30'),
(3, 'AST003', 'Meja Kerja', 'Furniture', 'Olympic', '2021', 'Baik', 2500000.00, NULL, 2, '2026-04-26 13:11:09', '2026-04-26 19:47:32'),
(4, 'AST004', 'Kursi Kantor', 'Furniture', 'Olympic', '2021', 'Rusak Ringan', 1500000.00, NULL, 2, '2026-04-26 13:11:09', '2026-04-26 19:47:34'),
(5, 'AST005', 'Printer', 'Elektronik', 'Canon', '2022', 'Baik', 3500000.00, NULL, 3, '2026-04-26 13:11:09', '2026-04-26 19:47:35'),
(6, 'AST006', 'AC Split', 'Elektronik', 'Panasonic', '2020', 'Rusak Berat', 4500000.00, NULL, 5, '2026-04-26 13:11:09', '2026-04-26 19:47:37'),
(7, 'AST007', 'Lemari Arsip', 'Furniture', 'Nices', '2021', 'Baik', 3200000.00, NULL, 4, '2026-04-26 13:11:09', '2026-04-26 19:47:39'),
(8, 'AST008', 'Proyektor', 'Elektronik', 'Epson', '2023', 'Baik', 7500000.00, NULL, 5, '2026-04-26 13:11:09', '2026-04-26 19:47:40'),
(9, 'AST03233', 'Tes', 'Elektronik', 'Tes', '2026', 'Baik', 1000000.00, NULL, 5, '2026-04-26 13:40:41', '2026-04-26 19:47:42');

-- --------------------------------------------------------

--
-- Table structure for table `detail_kir`
--

CREATE TABLE `detail_kir` (
  `id_detail` int(11) NOT NULL,
  `id_kir` int(11) NOT NULL,
  `id_aset` int(11) NOT NULL,
  `kondisi_saat_cetak` enum('Baik','Rusak Ringan','Rusak Berat') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_kir`
--

INSERT INTO `detail_kir` (`id_detail`, `id_kir`, `id_aset`, `kondisi_saat_cetak`) VALUES
(1, 1, 3, 'Baik'),
(2, 1, 4, 'Rusak Ringan'),
(3, 1, 9, 'Baik');

-- --------------------------------------------------------

--
-- Table structure for table `kir`
--

CREATE TABLE `kir` (
  `id_kir` int(11) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `tanggal_cetak` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kir`
--

INSERT INTO `kir` (`id_kir`, `id_ruangan`, `tanggal_cetak`, `created_at`) VALUES
(1, 2, '2026-04-26', '2026-04-26 13:52:40');

-- --------------------------------------------------------

--
-- Table structure for table `mutasi_aset`
--

CREATE TABLE `mutasi_aset` (
  `id_mutasi` int(11) NOT NULL,
  `id_aset` int(11) NOT NULL,
  `id_ruangan_asal` int(11) DEFAULT NULL,
  `id_ruangan_tujuan` int(11) NOT NULL,
  `tanggal_mutasi` date NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mutasi_aset`
--

INSERT INTO `mutasi_aset` (`id_mutasi`, `id_aset`, `id_ruangan_asal`, `id_ruangan_tujuan`, `tanggal_mutasi`, `keterangan`) VALUES
(1, 9, 2, 4, '2026-04-27', 'Mutasi oleh petugas'),
(2, 9, 4, 5, '2026-04-27', 'Mutasi oleh petugas');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` int(11) NOT NULL,
  `kode_ruangan` varchar(20) NOT NULL,
  `nama_ruangan` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `kode_ruangan`, `nama_ruangan`, `lokasi`, `created_at`) VALUES
(1, 'R001', 'Ruang Kepala Biro Umum', 'Lantai 2 Gedung Utama', '2026-04-26 13:11:09'),
(2, 'R002', 'Ruang Administrasi Aset', 'Lantai 1 Gedung Utama', '2026-04-26 13:11:09'),
(3, 'R003', 'Ruang Perlengkapan', 'Lantai 1 Sayap Timur', '2026-04-26 13:11:09'),
(4, 'R004', 'Ruang Arsip', 'Lantai 3 Gedung Utama', '2026-04-26 13:11:09'),
(5, 'R005', 'Ruang Rapat Utama', 'Lantai 2 Gedung Utama', '2026-04-26 13:11:09');

-- --------------------------------------------------------

--
-- Table structure for table `scan_log`
--

CREATE TABLE `scan_log` (
  `id_log` int(11) NOT NULL,
  `kode_aset` varchar(50) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `lokasi_scan` varchar(100) DEFAULT NULL,
  `waktu_scan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scan_log`
--

INSERT INTO `scan_log` (`id_log`, `kode_aset`, `id_user`, `lokasi_scan`, `waktu_scan`) VALUES
(1, 'AST03233', 2, 'Mobile Scan', '2026-04-26 19:31:13'),
(2, 'AST03233', 2, 'Mutasi Ruangan', '2026-04-26 19:31:35'),
(3, 'AST03233', 2, 'Mutasi Ruangan', '2026-04-26 19:31:52'),
(4, 'AST03233', 2, 'Update Kondisi: Baik', '2026-04-26 19:31:56'),
(5, 'AST03233', 2, 'Update Kondisi: Baik', '2026-04-26 19:31:57'),
(6, 'AST03233', 2, 'Update Kondisi: Rusak Ringan', '2026-04-26 19:32:00'),
(7, 'AST03233', 2, 'Update Kondisi: Baik', '2026-04-26 19:32:04'),
(8, 'AST03233', 2, 'Mobile Scan', '2026-04-26 19:32:55'),
(9, 'AST03233', 2, 'Mobile Scan', '2026-04-26 19:33:39'),
(10, 'AST03233', 2, 'Mobile Scan', '2026-04-26 19:34:09'),
(11, 'AST03233', 2, 'Mobile Scan', '2026-04-26 19:40:25'),
(12, 'AST001', 2, 'Mobile Scan', '2026-04-27 08:35:09'),
(13, 'AST001', 2, 'Update Kondisi: Rusak Berat', '2026-04-27 08:35:20');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin Aset','Petugas Inventaris') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 'Admin Aset', '2026-04-26 13:11:09'),
(2, 'Petugas Lapangan', 'petugas', '827ccb0eea8a706c4c34a16891f84e7b', 'Petugas Inventaris', '2026-04-26 13:11:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`id_aset`),
  ADD UNIQUE KEY `kode_aset` (`kode_aset`),
  ADD KEY `idx_aset_kode` (`kode_aset`),
  ADD KEY `idx_aset_ruangan` (`id_ruangan`);

--
-- Indexes for table `detail_kir`
--
ALTER TABLE `detail_kir`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_kir` (`id_kir`),
  ADD KEY `id_aset` (`id_aset`);

--
-- Indexes for table `kir`
--
ALTER TABLE `kir`
  ADD PRIMARY KEY (`id_kir`),
  ADD KEY `id_ruangan` (`id_ruangan`);

--
-- Indexes for table `mutasi_aset`
--
ALTER TABLE `mutasi_aset`
  ADD PRIMARY KEY (`id_mutasi`),
  ADD KEY `id_aset` (`id_aset`),
  ADD KEY `id_ruangan_asal` (`id_ruangan_asal`),
  ADD KEY `id_ruangan_tujuan` (`id_ruangan_tujuan`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`),
  ADD UNIQUE KEY `kode_ruangan` (`kode_ruangan`),
  ADD KEY `idx_ruangan_kode` (`kode_ruangan`);

--
-- Indexes for table `scan_log`
--
ALTER TABLE `scan_log`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aset`
--
ALTER TABLE `aset`
  MODIFY `id_aset` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `detail_kir`
--
ALTER TABLE `detail_kir`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kir`
--
ALTER TABLE `kir`
  MODIFY `id_kir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mutasi_aset`
--
ALTER TABLE `mutasi_aset`
  MODIFY `id_mutasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id_ruangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `scan_log`
--
ALTER TABLE `scan_log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aset`
--
ALTER TABLE `aset`
  ADD CONSTRAINT `aset_ibfk_1` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE SET NULL;

--
-- Constraints for table `detail_kir`
--
ALTER TABLE `detail_kir`
  ADD CONSTRAINT `detail_kir_ibfk_1` FOREIGN KEY (`id_kir`) REFERENCES `kir` (`id_kir`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_kir_ibfk_2` FOREIGN KEY (`id_aset`) REFERENCES `aset` (`id_aset`) ON DELETE CASCADE;

--
-- Constraints for table `kir`
--
ALTER TABLE `kir`
  ADD CONSTRAINT `kir_ibfk_1` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE CASCADE;

--
-- Constraints for table `mutasi_aset`
--
ALTER TABLE `mutasi_aset`
  ADD CONSTRAINT `mutasi_aset_ibfk_1` FOREIGN KEY (`id_aset`) REFERENCES `aset` (`id_aset`) ON DELETE CASCADE,
  ADD CONSTRAINT `mutasi_aset_ibfk_2` FOREIGN KEY (`id_ruangan_asal`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE SET NULL,
  ADD CONSTRAINT `mutasi_aset_ibfk_3` FOREIGN KEY (`id_ruangan_tujuan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE CASCADE;

--
-- Constraints for table `scan_log`
--
ALTER TABLE `scan_log`
  ADD CONSTRAINT `scan_log_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
