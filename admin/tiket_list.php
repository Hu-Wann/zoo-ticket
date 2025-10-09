<?php
session_start();
include "../database/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $id = intval($_POST['booking_id']);

    if (isset($_POST['acc'])) {
        $conn->query("UPDATE booking SET status = 'acc' WHERE id = $id");
    } elseif (isset($_POST['dec'])) {
        $conn->query("UPDATE booking SET status = 'dec' WHERE id = $id");
    } elseif (isset($_POST['delete'])) {
        $conn->query("DELETE FROM booking WHERE id = $id");
    }

    // Refresh halaman agar data terbaru langsung muncul
    header("Location: laporan_tiket.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST"&& isset($_POST['delete'])) {
    $id = intval($_POST['booking_id']);
    $conn->query("DELETE FROM booking WHERE id = $id");
    header("Location: laporan_tiket.php");
    exit;
}

// Pastikan hanya admin yang bisa akses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

$query = "SELECT * FROM booking ORDER BY tanggal_booking DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Tiket - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8fff8;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        table th {
            background-color: #198754;
            color: #fff;
        }
        .btn-action {
            margin: 2px;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="text-center py-3 bg-white shadow-sm mb-4">
    <h1 class="text-success"><i class="bi bi-ticket-perforated"></i> Kelola Tiket Booking</h1>
    <p class="lead">Halaman Admin - Kebun Binatang Indah</p>
</div>

<div class="container mb-5">
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-success"><i class="bi bi-list-check"></i> Daftar Tiket</h3>
            <a href="laporan_tiket.php" class="btn btn-outline-success"><i class="bi bi-bar-chart"></i> Lihat Laporan</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jumlah Dewasa</th>
                        <th>Jumlah Anak</th>
                        <th>Jumlah Remaja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="text-center"><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['nama_pengunjung'] ?? $row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_kunjungan'])); ?></td>
                    <td class="text-center"><?= $row['jumlah_dewasa']; ?></td>
                    <td class="text-center"><?= $row['jumlah_anak']; ?></td>
                    <td class="text-center"><?= $row['jumlah_remaja']; ?></td>
                    <td class="text-center">
                        <?php
                        if ($row['status'] === 'dibooking') {
                            echo "<span class='badge bg-warning text-dark'>Dibooking</span>";
                        } elseif ($row['status'] === 'acc') {
                            echo "<span class='badge bg-success'>Disetujui</span>";
                        } elseif ($row['status'] === 'dec') {
                            echo "<span class='badge bg-danger'>Ditolak</span>";
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <?php if ($row['status'] === 'dibooking'): ?>
                            <a href="proses_tiket.php?id=<?= $row['id']; ?>&aksi=acc" class="btn btn-success btn-sm btn-action">
                                <i class="bi bi-check-circle"></i> Setujui
                            </a>
                            <a href="proses_tiket.php?id=<?= $row['id']; ?>&aksi=dec" class="btn btn-danger btn-sm btn-action">
                                <i class="bi bi-x-circle"></i> Tolak
                            </a>
                        <?php else: ?>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-outline-danger btn-sm btn-action">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9" class="text-center py-3">Belum ada data tiket.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
        </div>
    </div>
    
    <div class="mt-4 text-center">
        <a href="dashboard.php" class="btn btn-outline-success">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
