<?php
session_start();
include "../database/conn.php";

// cek login & role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/index.php");
    exit;
}

// ambil jumlah data
$totalHewan = $conn->query("SELECT COUNT(*) as jml FROM animals")->fetch_assoc()['jml'];
$totalTiket = $conn->query("SELECT COUNT(*) as jml FROM booking")->fetch_assoc()['jml'];
$totalUser  = $conn->query("SELECT COUNT(*) as jml FROM users")->fetch_assoc()['jml'];

// Ambil data stok tiket untuk hari ini
$today = date('Y-m-d');
$stokHariIni = $conn->query("SELECT sisa_stok FROM stok_tiket WHERE tanggal = '$today'")->fetch_assoc();
$sisaStok = $stokHariIni ? $stokHariIni['sisa_stok'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    
    .sidebar {
      width: var(--sidebar-width);
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: var(--primary-color);
      color: white;
      padding: 20px 0;
      transition: all 0.3s;
      z-index: 1000;
    }
    
    .sidebar .logo {
      padding: 15px 25px;
      font-size: 22px;
      font-weight: 700;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      margin-bottom: 20px;
    }
    
    .sidebar .nav-link {
      color: rgba(255,255,255,0.8);
      padding: 12px 25px;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
      color: white;
      background: rgba(255,255,255,0.1);
      border-left: 4px solid var(--accent-color);
    }
    
    .sidebar .nav-link i {
      width: 20px;
      text-align: center;
    }
    
    .main-content {
      margin-left: var(--sidebar-width);
      padding: 20px;
      transition: all 0.3s;
    }
    
    .header {
      background: white;
      padding: 15px 25px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .stat-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      padding: 25px;
      transition: all 0.3s;
      height: 100%;
      border-left: 4px solid var(--primary-color);
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
      box-shadow: 0 5px 10px rgba(0,0,0,0.1);
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
    
    @media (max-width: 992px) {
      .sidebar {
        width: 70px;
      }
      
      .sidebar .logo {
        padding: 15px;
        font-size: 18px;
        text-align: center;
      }
      
      .sidebar .nav-link span {
        display: none;
      }
      
      .sidebar .nav-link {
        padding: 12px;
        justify-content: center;
      }
      
      .main-content {
        margin-left: 70px;
      }
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">
        <i class="fas fa-paw me-2"></i> Zoo Admin
      </div>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="dashboard.php">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="hewan.php">
            <i class="fas fa-hippo"></i>
            <span>Kelola Hewan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="laporan_tiket.php">
            <i class="fas fa-ticket-alt"></i>
            <span>Kelola Tiket</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="users.php">
            <i class="fas fa-users"></i>
            <span>Kelola Pengguna</span>
          </a>
        </li>
        <li class="nav-item mt-4">
          <a class="nav-link" href="../pages/index.php">
            <i class="fas fa-home"></i>
            <span>Halaman Utama</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="../acount/logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </div>
    
    <!-- Main Content -->
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
      
      <div class="row g-4">
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
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
