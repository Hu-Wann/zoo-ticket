-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 07, 2025 at 08:35 AM
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
  `nama` varchar(100) NOT NULL,
  `habitat` varchar(150) NOT NULL,
  `makanan` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `status_konservasi` varchar(100) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`id`, `nama`, `habitat`, `makanan`, `deskripsi`, `status_konservasi`, `gambar`) VALUES
(1, 'singa', 'afrika', 'daging', 'Singa adalah spesies hewan dari famili Felidae atau famili kucing. Singa berada di benua Afrika dan sebagian di wilayah India. Singa merupakan hewan yang hidup berkelompok. Biasanya terdiri dari seekor jantan dan banyak betina. Kelompok ini menjaga daerah kekuasaannya.', 'tidak terancam punah', '../uploads/1759731125_download (2).jpg'),
(2, 'koala', 'australia', 'daun', 'Koala adalah salah satu binatang berkantung khas dari Australia dan merupakan wakil satu-satunya dari famili Phascolarctidae', 'tidak terancam punah', '../uploads/1759731262_koala.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int NOT NULL,
  `nama_pengunjung` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `jumlah_dewasa` int DEFAULT '0',
  `jumlah_anak` int DEFAULT '0',
  `jumlah_remaja` int DEFAULT '0',
  `total_harga` int NOT NULL,
  `catatan` text,
  `tanggal_booking` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `nama_pengunjung`, `email`, `tanggal_kunjungan`, `jumlah_dewasa`, `jumlah_anak`, `jumlah_remaja`, `total_harga`, `catatan`, `tanggal_booking`) VALUES
(3, 'zahwan', 'zahwanfth@gmail.com', '2025-10-27', 1, 0, 0, 40000, '', '2025-10-06 04:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `nama_pengunjung` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `jumlah_dewasa` int DEFAULT '0',
  `jumlah_anak` int DEFAULT '0',
  `jumlah_remaja` int DEFAULT '0',
  `catatan` text,
  `total_harga` int NOT NULL,
  `tanggal_transaksi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(8, 'zahwan', 'zahwanfth@gmail.com', 'cbbbaec93fc2198df1c1d3fd9f30307f', 'admin'),
(12, 'azqya', 'azqya@gmail.com', '82bb82446c362087c5d814e7136e865a', 'user');

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
