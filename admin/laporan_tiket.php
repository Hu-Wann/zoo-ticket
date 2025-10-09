<?php
session_start();
include "../database/conn.php";

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit();
}

// Ambil total tiket terjual per kategori
$queryTotal = "
    SELECT 
        SUM(jumlah_dewasa) AS total_dewasa,
        SUM(jumlah_remaja) AS total_remaja,
        SUM(jumlah_anak)   AS total_anak
    FROM booking
";
$totalResult = mysqli_query($conn, $queryTotal);
$totalData   = mysqli_fetch_assoc($totalResult);

$total_dewasa = $totalData['total_dewasa'] ?? 0;
$total_remaja = $totalData['total_remaja'] ?? 0;
$total_anak   = $totalData['total_anak'] ?? 0;
$total_semua  = $total_dewasa + $total_remaja + $total_anak;

// Ambil semua detail booking
$queryDetail = "SELECT * FROM booking ORDER BY tanggal_booking DESC";
$detailResult = mysqli_query($conn, $queryDetail);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Penjualan Tiket - Admin</title>
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
    .stat-title {
      font-size: 1.1rem;
      font-weight: 600;
    }
    .stat-number {
      font-size: 1.8rem;
      font-weight: bold;
      color: #198754;
    }
    table th {
      background-color: #198754;
      color: #fff;
    }
  </style>
</head>
<body>

<!-- Header -->
<div class="text-center py-3 bg-white shadow-sm mb-4">
  <h1 class="text-success"><i class="bi bi-clipboard-data"></i> Laporan Penjualan Tiket</h1>
  <p class="lead">Halaman Admin - Kebun Binatang Indah</p>
</div>

<div class="container mb-5">

  <!-- Statistik -->
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card text-center p-4">
        <div class="stat-title">Dewasa</div>
        <div class="stat-number"><?php echo $total_dewasa; ?></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center p-4">
        <div class="stat-title">Remaja</div>
        <div class="stat-number"><?php echo $total_remaja; ?></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center p-4">
        <div class="stat-title">Anak-anak</div>
        <div class="stat-number"><?php echo $total_anak; ?></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center p-4 bg-success text-white">
        <div class="stat-title">Total Tiket Terjual</div>
        <div class="stat-number text-white"><?php echo $total_semua; ?></div>
      </div>
    </div>
  </div>

  <!-- Tabel Detail -->
  <div class="card p-4">
    <h3 class="mb-3 text-success"><i class="bi bi-list-ul"></i> Detail Tiket Terjual</h3>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Pengunjung</th>
            <th>Email</th>
            <th>Tanggal Kunjungan</th>
            <th>Dewasa</th>
            <th>Remaja</th>
            <th>Anak-anak</th>
            <th>Total Harga</th>
            <th>Tanggal Booking</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($detailResult) > 0) {
            $no = 1;
            while ($row = mysqli_fetch_assoc($detailResult)) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>".htmlspecialchars($row['nama_pengunjung'])."</td>
                        <td>".htmlspecialchars($row['email'])."</td>
                        <td>".date('d-m-Y', strtotime($row['tanggal_kunjungan']))."</td>
                        <td>{$row['jumlah_dewasa']}</td>
                        <td>{$row['jumlah_remaja']}</td>
                        <td>{$row['jumlah_anak']}</td>
                        <td>Rp ".number_format($row['total_harga'], 0, ',', '.')."</td>
                        <td>".date('d-m-Y H:i', strtotime($row['tanggal_booking']))."</td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='9' class='text-center text-muted'>Belum ada data booking</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-4 text-center">
    <a href="dashboard.php" class="btn btn-outline-success"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    <a href="tiket_list.php" class="btn btn-success ms-2"><i class="bi bi-ticket-perforated"></i> Kelola Tiket</a>
  </div>

</div>

</body>
</html>
