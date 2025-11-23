<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit();
}

// Ambil semua data pengeluaran dan kelompokkan berdasarkan tanggal
$query = "SELECT tanggal, GROUP_CONCAT(kategori SEPARATOR ', ') as kategori, 
                 GROUP_CONCAT(deskripsi SEPARATOR '; ') as deskripsi, 
                 SUM(jumlah) as total_jumlah
          FROM pengeluaran
          GROUP BY tanggal
          ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);

$pengeluaran_per_tanggal = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pengeluaran_per_tanggal[] = $row;
}

// Hitung total semua pengeluaran
$total_semua_pengeluaran = 0;
foreach ($pengeluaran_per_tanggal as $data) {
    $total_semua_pengeluaran += $data['total_jumlah'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Pengeluaran - Admin</title>
    <?php include '../bootstrap.php'; ?>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .header-section {
            background: linear-gradient(to right, #28a745, #218838);
            color: white;
            padding: 2rem 0;
            border-bottom: 5px solid #1e7e34;
        }

        .card {
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table-responsive {
            border-radius: 0.5rem;
        }

        .table thead th {
            background-color: #343a40;
            color: white;
            border-bottom: 0;
        }

        .btn-action {
            margin-right: 5px;
        }

        .total-card {
            background-color: #28a745;
            color: white;
            border-radius: 1rem;
        }

        .main-content {
            margin-left: var(--sidebar-width, 250px);
            padding: 20px 20px 40px 20px;
            transition: all 0.3s;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 100px;
            }
        }
    </style>
</head>

<body>
    <main class="flex-grow-1 p-4">
        <?php include 'sidebar.php'; ?>
        <!-- Header -->
        <div class="main-content">
            <div class="header-section text-center mb-4">
                <div class="container">
                    <h1 class="display-5"><i class="bi bi-wallet2"></i> Daftar Pengeluaran</h1>
                    <p class="lead">Manajemen Keuangan Operasional - Kebun Binatang Indah</p>
                </div>
            </div>

            <div class="container mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="laporan_pendapatan.php" class="btn btn-outline-dark"><i class="bi bi-arrow-left"></i>
                            Kembali ke Laporan</a>
                        <a href="pengeluaran_tambah.php" class="btn btn-success"><i class="bi bi-plus-circle"></i>
                            Tambah Pengeluaran</a>
                    </div>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Total Pengeluaran Card -->
                <div class="card total-card p-4 mb-4 text-center">
                    <h4 class="mb-0">Total Seluruh Pengeluaran</h4>
                    <h2 class="display-4 fw-bold">Rp <?= number_format($total_semua_pengeluaran, 0, ',', '.') ?></h2>
                </div>

                <div class="card p-4">
                    <h4 class="mb-3"><i class="bi bi-list-ul"></i> Rincian Pengeluaran per Tanggal</h4>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Total Harian</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pengeluaran_per_tanggal)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada data pengeluaran.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pengeluaran_per_tanggal as $data): ?>
                                        <tr>
                                            <td class="fw-bold"><?= date("d F Y", strtotime($data['tanggal'])) ?></td>
                                            <td><?= htmlspecialchars($data['kategori']) ?></td>
                                            <td><?= htmlspecialchars($data['deskripsi']) ?></td>
                                            <td class="text-end fw-bold">Rp
                                                <?= number_format($data['total_jumlah'], 0, ',', '.') ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="pengeluaran_edit.php?tanggal=<?= $data['tanggal'] ?>"
                                                    class="btn btn-sm btn-outline-primary btn-action" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="sys_pengeluaran.php?action=delete_by_date&tanggal=<?= $data['tanggal'] ?>"
                                                    class="btn btn-sm btn-outline-danger btn-action" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus semua data pengeluaran pada tanggal ini?');">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>