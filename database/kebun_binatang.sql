-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2025 at 08:13 AM
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
-- Table structure for table `admin_messages`
--

CREATE TABLE `admin_messages` (
  `id` int NOT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `sender_email` varchar(150) DEFAULT NULL,
  `content` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_messages`
--

INSERT INTO `admin_messages` (`id`, `sender_name`, `sender_email`, `content`, `created_at`, `is_read`) VALUES
(1, 'farhani', 'farhan@gmail.com', 'min aku lupa password tolong reset dong', '2025-11-17 20:33:23', 1);

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

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`id`, `nama`, `habitat`, `makanan`, `deskripsi`, `status_konservasi`, `gambar`) VALUES
(13, 'singa', 'afrika', 'Karnivora', 'Singa adalah spesies hewan dari famili Felidae atau famili kucing. Singa berada di benua Afrika dan sebagian di wilayah India. Singa merupakan hewan yang hidup berkelompok. Biasanya terdiri dari seekor jantan dan banyak betina. Kelompok ini menjaga daerah kekuasaannya', 'rentan', 'download (2).jpg'),
(14, 'koala', 'australia', 'Herbivora', 'Koala adalah salah satu binatang berkantung khas dari Australia dan merupakan wakil satu-satunya dari famili Phascolarctidae.', 'rentan', 'koala.jpg'),
(15, 'panda', 'tiongkok', 'Herbivora', 'Panda raksasa atau hanya disebut panda, adalah seekor mamalia yang diklasifikasikan ke dalam famili beruang, Ursidae, yang merupakan hewan asli Tiongkok Tengah. Panda raksasa tinggal di wilayah pegunungan, seperti Sichuan dan Tibet.', 'dilindungi', 'panda.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int NOT NULL,
  `nama_pengunjung` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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
(11, 'wan', 'zahwanfth@gmail.com', '2025-11-10', 1, 0, 1, 75000, '', '2025-11-10 04:10:49', 'kadaluwarsa', '7CC0-5735'),
(12, 'awan', NULL, '2025-11-18', 1, 0, 0, 40000, '', '2025-11-18 01:33:36', 'dibayar', 'AFD2-5202'),
(13, 'awan', NULL, '2025-11-18', 1, 0, 1, 75000, '', '2025-11-18 01:34:24', 'dibayar', 'F83F-2133'),
(14, 'wan', 'zahwanfth@gmail.com', '2025-11-20', 1, 0, 0, 40000, '', '2025-11-20 16:05:31', 'kadaluwarsa', 'EC00-5482'),
(15, 'user', NULL, '2025-11-21', 0, 0, 1, 35000, '', '2025-11-20 16:28:26', 'dibayar', '18F0-2606'),
(16, 'wan', 'zahwanfth@gmail.com', '2025-11-23', 2, 0, 0, 80000, '', '2025-11-23 08:11:21', 'dibayar', '7E9D-1522');

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
(22, '2025-11-20', 'Gaji Karyawan', '', 2000000, '2025-11-20 16:31:36'),
(23, '2025-11-20', 'Pangan', '', 1000000, '2025-11-20 16:31:36'),
(24, '2025-11-20', 'Perawatan Hewan', '', 1000000, '2025-11-20 16:31:36'),
(25, '2025-11-20', 'Pemeliharaan Kandang', '', 1000000, '2025-11-20 16:31:36');

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
(14, '2025-11-23', 998);

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
(22, 'wan', 'zahwanfth@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_messages`
--
ALTER TABLE `admin_messages`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `tanggal_kunjungan` (`tanggal_kunjungan`),
  ADD KEY `booking_ibfk_1` (`email`);

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
-- AUTO_INCREMENT for table `admin_messages`
--
ALTER TABLE `admin_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stok_tiket`
--
ALTER TABLE `stok_tiket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
