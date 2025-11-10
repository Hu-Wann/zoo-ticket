<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit();
}

// laporan bulanan
$queryBulanan = "
    SELECT 
        DATE_FORMAT(tanggal_booking, '%Y-%m') AS bulan,
        SUM(total_harga) AS total_bulanan
    FROM booking
    GROUP BY DATE_FORMAT(tanggal_booking, '%Y-%m')
    ORDER BY bulan DESC
";
$resultBulanan = mysqli_query($conn, $queryBulanan);

// laporan tahunan
$queryTahunan = "
    SELECT 
        YEAR(tanggal_booking) AS tahun,
        SUM(total_harga) AS total_tahunan
    FROM booking
    GROUP BY YEAR(tanggal_booking)
    ORDER BY tahun DESC
";
$resultTahunan = mysqli_query($conn, $queryTahunan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Keuangan - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body { background-color: #f8fff8; }
    .card { border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    table th { background-color: #198754; color: #fff; }
    .section-title { color: #198754; font-weight: 700; }
    @media print { .no-print { display:none; } }
  </style>
</head>
<body>

<!-- Header -->
<div class="text-center py-3 bg-white shadow-sm mb-4 no-print">
  <h1 class="text-success"><i class="bi bi-clipboard-data"></i> Laporan Keuangan Tiket</h1>
  <p class="lead">Laporan Bulanan dan Tahunan - Kebun Binatang Indah</p>
</div>

<div class="container mb-5">

  <!-- Laporan Bulanan -->
  <div class="card p-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="section-title"><i class="bi bi-calendar-month"></i> Laporan Penghasilan Bulanan</h3>
      <button class="btn btn-outline-success no-print" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th>Bulan</th>
            <th>Total Penghasilan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($resultBulanan) > 0) {
              while ($row = mysqli_fetch_assoc($resultBulanan)) {
                  $bulan = date('F Y', strtotime($row['bulan']."-01"));
                  $total = number_format($row['total_bulanan'], 0, ',', '.');
                  echo "<tr><td>{$bulan}</td><td>Rp {$total}</td></tr>";
              }
          } else {
              echo "<tr><td colspan='2' class='text-center text-muted'>Belum ada data penghasilan bulanan</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Laporan Tahunan -->
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="section-title"><i class="bi bi-calendar-check"></i> Laporan Penghasilan Tahunan</h3>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th>Tahun</th>
            <th>Total Penghasilan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($resultTahunan) > 0) {
              while ($row = mysqli_fetch_assoc($resultTahunan)) {
                  $tahun = $row['tahun'];
                  $total = number_format($row['total_tahunan'], 0, ',', '.');
                  echo "<tr><td>{$tahun}</td><td>Rp {$total}</td></tr>";
              }
          } else {
              echo "<tr><td colspan='2' class='text-center text-muted'>Belum ada data penghasilan tahunan</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-4 text-center no-print">
    <a href="dashboard.php" class="btn btn-outline-success"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    <a href="laporan_tiket.php" class="btn btn-success ms-2"><i class="bi bi-ticket-perforated"></i> Laporan Tiket</a>
  </div>
</div>

</body>
</html>
