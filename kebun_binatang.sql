-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 12, 2025 at 12:30 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kebun_binatang`
--

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

CREATE TABLE `animals` (
  `id` int NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `habitat` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `makanan` enum('Herbivora','Karnivora','Omnivora','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status_konservasi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int NOT NULL,
  `nama_pengunjung` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `jumlah_dewasa` int DEFAULT '0',
  `jumlah_anak` int DEFAULT '0',
  `jumlah_remaja` int DEFAULT '0',
  `total_harga` int NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `tanggal_booking` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'dibooking',
  `kode_redeem` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `nama_pengunjung`, `email`, `tanggal_kunjungan`, `jumlah_dewasa`, `jumlah_anak`, `jumlah_remaja`, `total_harga`, `catatan`, `tanggal_booking`, `status`, `kode_redeem`) VALUES
(3, 'zahwan', 'zahwanfth@gmail.com', '2025-10-27', 1, 0, 0, 40000, '', '2025-10-06 04:06:13', 'kadaluwarsa', 'B5F2-3626'),
(7, 'awan', 'zahwanfth@gmail.com', '2025-10-28', 1, 0, 0, 40000, '', '2025-10-11 03:39:45', 'kadaluwarsa', 'EFB9-2816'),
(8, 'awan', 'zahwanfth@gmail.com', '2025-10-12', 3, 2, 2, 240000, '', '2025-10-11 04:41:45', 'kadaluwarsa', 'F433-2872'),
(9, 'awan', 'zahwanfth@gmail.com', '2025-10-12', 5, 0, 0, 200000, '', '2025-10-11 04:45:15', 'kadaluwarsa', '4EB5-2228'),
(11, 'wan', 'zahwanfth@gmail.com', '2025-11-10', 1, 0, 1, 75000, '', '2025-11-10 04:10:49', 'acc', '7CC0-5735');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `deskripsi` text,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `tanggal`, `kategori`, `deskripsi`, `jumlah`, `created_at`) VALUES
(2, '2025-11-10', 'Gaji Karyawan', '', 2000000, '2025-11-10 12:22:06'),
(3, '2025-11-10', 'Pangan', '', 300000, '2025-11-10 12:22:06'),
(4, '2025-11-10', 'Perawatan Hewan', '', 150000, '2025-11-10 12:22:06'),
(5, '2025-11-10', 'Pemeliharaan Kandang', '', 50000, '2025-11-10 12:22:06');

-- --------------------------------------------------------

--
-- Table structure for table `stok_tiket`
--

CREATE TABLE `stok_tiket` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `sisa_stok` int NOT NULL DEFAULT '500'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok_tiket`
--

INSERT INTO `stok_tiket` (`id`, `tanggal`, `sisa_stok`) VALUES
(5, '2025-11-10', 500);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(19, 'farhani', 'paan@gmail.com', 'bfe04a104d81808959ef2c327c4d866b', 'user'),
(22, 'wan', 'zahwanfth@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `tanggal_kunjungan` (`tanggal_kunjungan`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_tiket`
--
ALTER TABLE `stok_tiket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tanggal` (`tanggal`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stok_tiket`
--
ALTER TABLE `stok_tiket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
