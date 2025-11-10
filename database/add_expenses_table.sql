-- Create expenses table for tracking operational costs
CREATE TABLE `pengeluaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tanggal` (`tanggal`),
  KEY `kategori` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample expense data
INSERT INTO `pengeluaran` (`tanggal`, `kategori`, `deskripsi`, `jumlah`) VALUES
('2025-10-01', 'Makanan Hewan', 'Pembelian makanan untuk panda dan hewan lainnya', 2500000),
('2025-10-05', 'Perawatan Kandang', 'Perawatan dan pembersihan kandang hewan', 1500000),
('2025-10-10', 'Gaji Karyawan', 'Gaji karyawan bulan Oktober 2025', 15000000),
('2025-10-15', 'Listrik & Air', 'Tagihan listrik dan air bulan Oktober', 3500000),
('2025-10-20', 'Maintenance', 'Perbaikan fasilitas dan peralatan', 2000000),
('2025-11-01', 'Makanan Hewan', 'Pembelian makanan untuk bulan November', 2800000),
('2025-11-05', 'Perawatan Kandang', 'Perawatan kandang dan area taman', 1800000),
('2025-11-10', 'Gaji Karyawan', 'Gaji karyawan bulan November 2025', 15000000),
('2025-11-15', 'Listrik & Air', 'Tagihan listrik dan air bulan November', 3200000);