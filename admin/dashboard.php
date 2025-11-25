<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ./index.php");
  exit;
}

$totalHewan = $conn->query("SELECT COUNT(*) as jml FROM animals")->fetch_assoc()['jml'];
$totalTiket = $conn->query("SELECT COUNT(*) as jml FROM booking")->fetch_assoc()['jml'];
$totalUser = $conn->query("SELECT COUNT(*) as jml FROM users")->fetch_assoc()['jml'];

$today = date('Y-m-d');
$stokHariIni = $conn->query("SELECT sisa_stok FROM stok_tiket WHERE tanggal = '$today'")->fetch_assoc();
$sisaStok = $stokHariIni ? $stokHariIni['sisa_stok'] : 0;

$bulanIni = date('Y-m');
$laporanBulanan = [];

$queryPendapatan = "SELECT 
                    DATE_FORMAT(tanggal_booking, '%Y-%m') as bulan,
                    SUM(total_harga) as total_pendapatan,
                    COUNT(*) as jumlah_tiket
                FROM booking
                WHERE DATE_FORMAT(tanggal_booking, '%Y-%m') = '$bulanIni'
                  AND status = 'dibayar'
                GROUP BY DATE_FORMAT(tanggal_booking, '%Y-%m')";

$resultPendapatan = $conn->query($queryPendapatan);

if ($resultPendapatan->num_rows > 0) {
  $laporanBulanan = $resultPendapatan->fetch_assoc();
} else {
  $laporanBulanan = [
    'bulan' => $bulanIni,
    'total_pendapatan' => 0,
    'jumlah_tiket' => 0
  ];
}

$conn->query("CREATE TABLE IF NOT EXISTS admin_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_name VARCHAR(100),
  sender_email VARCHAR(150),
  content TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  is_read TINYINT(1) DEFAULT 0
) ENGINE=InnoDB");

if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
  $mid = (int) $_GET['mark_read'];
  $conn->query("UPDATE admin_messages SET is_read=1 WHERE id=$mid");
  header("Location: dashboard.php");
  exit;
}

$messages = $conn->query("SELECT * FROM admin_messages ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <?php include '../bootstrap.php'; ?>
  <style>
    :root {
      --primary-color: #198754;
      --secondary-color: #0d6efd;
      --accent-color: #ffc107;
      --sidebar-width: 250px;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      overflow-x: hidden;
    }

    .main-content {
      margin-left: var(--sidebar-width);
      padding: 20px 20px 40px 20px;
      transition: all 0.3s;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .header {
      background: white;
      padding: 15px 25px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .stat-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 25px;
      transition: all 0.3s;
      height: 100%;
      border-left: 4px solid var(--primary-color);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-card.animals {
      border-left-color: var(--primary-color);
    }

    .stat-card.tickets {
      border-left-color: var(--secondary-color);
    }

    .stat-card.users {
      border-left-color: var(--accent-color);
    }

    .stat-card .icon {
      font-size: 40px;
      margin-bottom: 15px;
      color: var(--primary-color);
    }

    .stat-card.animals .icon {
      color: var(--primary-color);
    }

    .stat-card.tickets .icon {
      color: var(--secondary-color);
    }

    .stat-card.users .icon {
      color: var(--accent-color);
    }

    .stat-card .count {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .stat-card .title {
      font-size: 18px;
      color: #6c757d;
      margin-bottom: 15px;
    }

    .btn-manage {
      border-radius: 50px;
      padding: 8px 20px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .btn-manage:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-info .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--primary-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
    }
  </style>
</head>

<body>
  <div class="d-flex">
    <main class="flex-grow-1 p-4">
      <?php include 'sidebar.php'; ?>

      <div class="main-content">
        <div class="header">
          <h4 class="mb-0">Dashboard</h4>
          <div class="user-info">
            <div class="avatar">
              <?= substr($_SESSION['nama'], 0, 1) ?>
            </div>
            <div>
              <p class="mb-0 fw-bold"><?= $_SESSION['nama'] ?></p>
              <small class="text-muted">Administrator</small>
            </div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-3">
            <div class="stat-card animals">
              <div class="icon">
                <i class="fas fa-hippo"></i>
              </div>
              <div class="count"><?= $totalHewan ?></div>
              <div class="title">Total Hewan</div>
              <a href="hewan.php" class="btn btn-success btn-manage">
                <i class="fas fa-cog me-1"></i> Kelola Hewan
              </a>
            </div>
          </div>

          <div class="col-md-3">
            <div class="stat-card tickets">
              <div class="icon">
                <i class="fas fa-ticket-alt"></i>
              </div>
              <div class="count"><?= $totalTiket ?></div>
              <div class="title">Tiket Terjual</div>
              <a href="laporan_tiket.php" class="btn btn-primary btn-manage">
                <i class="fas fa-cog me-1"></i> Kelola Tiket
              </a>
            </div>
          </div>

          <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #dc3545;">
              <div class="icon" style="color: #dc3545;">
                <i class="fas fa-calendar-day"></i>
              </div>
              <div class="count"><?= $sisaStok ?></div>
              <div class="title">Stok Tiket Hari Ini</div>
              <a href="stok.php" class="btn btn-danger btn-manage">
                <i class="fas fa-cog me-1"></i> Kelola Stok
              </a>
            </div>
          </div>

          <div class="col-md-3">
            <div class="stat-card users">
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <div class="count"><?= $totalUser ?></div>
              <div class="title">Total Pengguna</div>
              <a href="users.php" class="btn btn-warning btn-manage">
                <i class="fas fa-cog me-1"></i> Kelola Pengguna
              </a>
            </div>
          </div>
        </div>

        <div class="row mt-3 flex-grow-1">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Laporan Bulanan (<?= date('F Y') ?>)</h5>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="card mb-3">
                      <div class="card-body">
                        <h5 class="card-title text-primary">Total Pendapatan (Dibayar)</h5>
                        <h2 class="display-6 fw-bold">Rp
                          <?= number_format($laporanBulanan['total_pendapatan'], 0, ',', '.') ?></h2>
                        <p class="text-muted">Periode: <?= date('F Y') ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title text-primary">Jumlah Tiket Dibayar Bulan Ini</h5>
                        <h2 class="display-6 fw-bold"><?= $laporanBulanan['jumlah_tiket'] ?> Tiket</h2>
                        <p class="text-muted">Periode: <?= date('F Y') ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="text-end mt-3">
                  <a href="laporan_pendapatan.php" class="btn btn-primary">
                    <i class="fas fa-file-alt me-1"></i> Lihat Laporan Lengkap
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>