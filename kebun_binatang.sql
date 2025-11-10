-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 10 Nov 2025 pada 04.16
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

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
-- Struktur dari tabel `animals`
--

CREATE TABLE `animals` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `habitat` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `makanan` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `status_konservasi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `animals`
--

INSERT INTO `animals` (`id`, `nama`, `habitat`, `makanan`, `deskripsi`, `status_konservasi`, `gambar`) VALUES
(12, 'Panda', 'Hutan', 'Bambu', 'Panda raksasa atau hanya disebut panda, adalah seekor mamalia yang diklasifikasikan ke dalam famili beruang, Ursidae, yang merupakan hewan asli Tiongkok Tengah. Panda raksasa tinggal di wilayah pegunungan, seperti Sichuan dan Tibet.', 'Dilindungi', '1760183821_panda.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `id` int NOT NULL,
  `nama_pengunjung` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `jumlah_dewasa` int DEFAULT '0',
  `jumlah_anak` int DEFAULT '0',
  `jumlah_remaja` int DEFAULT '0',
  `total_harga` int NOT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `tanggal_booking` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'dibooking',
  `kode_redeem` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`id`, `nama_pengunjung`, `email`, `tanggal_kunjungan`, `jumlah_dewasa`, `jumlah_anak`, `jumlah_remaja`, `total_harga`, `catatan`, `tanggal_booking`, `status`, `kode_redeem`) VALUES
(3, 'zahwan', 'zahwanfth@gmail.com', '2025-10-27', 1, 0, 0, 40000, '', '2025-10-06 04:06:13', 'kadaluwarsa', 'B5F2-3626'),
(7, 'awan', 'zahwanfth@gmail.com', '2025-10-28', 1, 0, 0, 40000, '', '2025-10-11 03:39:45', 'kadaluwarsa', 'EFB9-2816'),
(8, 'awan', 'zahwanfth@gmail.com', '2025-10-12', 3, 2, 2, 240000, '', '2025-10-11 04:41:45', 'kadaluwarsa', 'F433-2872'),
(9, 'awan', 'zahwanfth@gmail.com', '2025-10-12', 5, 0, 0, 200000, '', '2025-10-11 04:45:15', 'kadaluwarsa', '4EB5-2228'),
(11, 'wan', 'zahwanfth@gmail.com', '2025-11-10', 1, 0, 1, 75000, '', '2025-11-10 04:10:49', 'acc', '7CC0-5735');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_tiket`
--

CREATE TABLE `stok_tiket` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `sisa_stok` int NOT NULL DEFAULT '500'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stok_tiket`
--

INSERT INTO `stok_tiket` (`id`, `tanggal`, `sisa_stok`) VALUES
(5, '2025-11-10', 498);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_general_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(19, 'farhani', 'paan@gmail.com', 'bfe04a104d81808959ef2c327c4d866b', 'user'),
(22, 'wan', 'zahwanfth@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `tanggal_kunjungan` (`tanggal_kunjungan`);

--
-- Indeks untuk tabel `stok_tiket`
--
ALTER TABLE `stok_tiket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tanggal` (`tanggal`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `stok_tiket`
--
ALTER TABLE `stok_tiket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
