-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Okt 2025 pada 14.00
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `habitat` varchar(150) NOT NULL,
  `makanan` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `status_konservasi` varchar(100) NOT NULL,
  `gambar` varchar(255) NOT NULL
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
  `id` int(11) NOT NULL,
  `nama_pengunjung` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `jumlah_dewasa` int(11) DEFAULT 0,
  `jumlah_anak` int(11) DEFAULT 0,
  `jumlah_remaja` int(11) DEFAULT 0,
  `total_harga` int(11) NOT NULL,
  `catatan` text DEFAULT NULL,
  `tanggal_booking` timestamp NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'dibooking'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`id`, `nama_pengunjung`, `email`, `tanggal_kunjungan`, `jumlah_dewasa`, `jumlah_anak`, `jumlah_remaja`, `total_harga`, `catatan`, `tanggal_booking`, `status`) VALUES
(3, 'zahwan', 'zahwanfth@gmail.com', '2025-10-27', 1, 0, 0, 40000, '', '2025-10-06 04:06:13', 'acc'),
(7, 'awan', 'zahwanfth@gmail.com', '2025-10-28', 1, 0, 0, 40000, '', '2025-10-11 03:39:45', 'acc'),
(8, 'awan', 'zahwanfth@gmail.com', '2025-10-12', 3, 2, 2, 240000, '', '2025-10-11 04:41:45', 'acc'),
(9, 'awan', 'zahwanfth@gmail.com', '2025-10-12', 5, 0, 0, 200000, '', '2025-10-11 04:45:15', 'acc');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_tiket`
--

CREATE TABLE `stok_tiket` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `sisa_stok` int(11) NOT NULL DEFAULT 500
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stok_tiket`
--

INSERT INTO `stok_tiket` (`id`, `tanggal`, `sisa_stok`) VALUES
(2, '2025-10-12', 488),
(3, '2025-10-11', 500);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `nama_pengunjung` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `jumlah_dewasa` int(11) DEFAULT 0,
  `jumlah_anak` int(11) DEFAULT 0,
  `jumlah_remaja` int(11) DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `total_harga` int(11) NOT NULL,
  `tanggal_transaksi` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`) VALUES
(18, 'wan', 'zahwanfth@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin'),
(19, 'farhani', 'paan@gmail.com', 'bfe04a104d81808959ef2c327c4d866b', 'user');

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
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `stok_tiket`
--
ALTER TABLE `stok_tiket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tanggal` (`tanggal`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `stok_tiket`
--
ALTER TABLE `stok_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
