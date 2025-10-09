<?php
session_start();
include "../database/conn.php";

// cek login & role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/beranda.php");
    exit;
}

// ambil jumlah data
$totalHewan = $conn->query("SELECT COUNT(*) as jml FROM animals")->fetch_assoc()['jml'];
$totalTiket = $conn->query("SELECT COUNT(*) as jml FROM booking")->fetch_assoc()['jml'];
$totalUser  = $conn->query("SELECT COUNT(*) as jml FROM users")->fetch_assoc()['jml'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin Panel</a>
    <div class="d-flex">
      <a href="../acount/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
    <div class="d-flex">
      <a href="../pages/beranda.php" class="btn btn-danger btn-sm">Home</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h3>Selamat datang, <?= $_SESSION['nama'] ?>!</h3>
  <div class="row mt-4 text-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5>🦁 Total Hewan</h5>
          <p class="fs-4 fw-bold"><?= $totalHewan ?></p>
          <a href="hewan.php" class="btn btn-success btn-sm">Kelola Hewan</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5>🎟️ Tiket Terjual</h5>
          <p class="fs-4 fw-bold"><?= $totalTiket ?></p>
          <a href="laporan_tiket.php" class="btn btn-success btn-sm">Kelola Tiket</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5>👤 Pengguna</h5>
          <p class="fs-4 fw-bold"><?= $totalUser ?></p>
          <a href="pengguna.php" class="btn btn-success btn-sm">Kelola Pengguna</a>
        </div>
      </div>
    </div>
  </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
