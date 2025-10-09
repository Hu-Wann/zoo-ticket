<?php
session_start();
include '../database/conn.php'; 


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/beranda.php');
    exit;
}

// Proses ACC
if (isset($_GET['acc'])) {
    $id = (int)$_GET['acc'];
    $conn->query("UPDATE booking SET status = 'acc' WHERE id = $id");
    header('Location: manage_booking.php');
    exit;
}

// Proses DEC
if (isset($_GET['dec'])) {
    $id = (int)$_GET['dec'];
    $conn->query("UPDATE booking SET status = 'dec' WHERE id = $id");
    header('Location: manage_booking.php');
    exit;
}

// Ambil semua data booking
$result = $conn->query("SELECT * FROM booking ORDER BY tanggal_kunjungan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Booking - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1 class="mb-4"><i class="bi bi-card-checklist me-2"></i> Kelola Booking Tiket</h1>
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead class="table-success">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Tanggal Kunjungan</th>
            <th>Dewasa</th>
            <th>Remaja</th>
            <th>Anak</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0):
          $no = 1;
          while ($row = $result->fetch_assoc()):
        ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama_pengunjung']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['tanggal_kunjungan']); ?></td>
            <td><?= $row['jumlah_dewasa']; ?></td>
            <td><?= $row['jumlah_remaja']; ?></td>
            <td><?= $row['jumlah_anak']; ?></td>
            <td>
              <?php if ($row['status'] === 'dibooking'): ?>
                <span class="badge bg-warning text-dark">Dibooking</span>
              <?php elseif ($row['status'] === 'acc'): ?>
                <span class="badge bg-success">Disetujui</span>
              <?php elseif ($row['status'] === 'dec'): ?>
                <span class="badge bg-danger">Ditolak</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($row['status'] === 'dibooking'): ?>
                <a href="?acc=<?= $row['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Setujui booking ini?')">
                  <i class="bi bi-check-circle"></i>
                </a>
                <a href="?dec=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tolak booking ini?')">
                  <i class="bi bi-x-circle"></i>
                </a>
              <?php else: ?>
                <em>-</em>
              <?php endif; ?>
            </td>
          </tr>
        <?php
          endwhile;
        else:
        ?>
          <tr>
            <td colspan="9" class="text-center">Belum ada data booking.</td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
