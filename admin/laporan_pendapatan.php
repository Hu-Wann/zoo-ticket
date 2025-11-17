<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../acount/login.php");
  exit();
}

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

$queryHarian = "
    SELECT 
        DATE(tanggal_booking) AS tanggal,
        COUNT(*) AS jumlah_transaksi,
        SUM(total_harga) AS total_harian
    FROM booking
    WHERE DATE(tanggal_booking) BETWEEN '$start_date' AND '$end_date'
      AND status = 'dibayar'
    GROUP BY DATE(tanggal_booking)
    ORDER BY tanggal DESC
";
$resultHarian = mysqli_query($conn, $queryHarian);

$queryBulanan = "
    SELECT 
        DATE_FORMAT(tanggal_booking, '%Y-%m') AS bulan,
        COUNT(*) AS jumlah_transaksi,
        SUM(total_harga) AS total_bulanan
    FROM booking
    WHERE status = 'dibayar'
    GROUP BY DATE_FORMAT(tanggal_booking, '%Y-%m')
    ORDER BY bulan DESC
";
$resultBulanan = mysqli_query($conn, $queryBulanan);

$queryTahunan = "
    SELECT 
        YEAR(tanggal_booking) AS tahun,
        COUNT(*) AS jumlah_transaksi,
        SUM(total_harga) AS total_tahunan
    FROM booking
    WHERE status = 'dibayar'
    GROUP BY YEAR(tanggal_booking)
    ORDER BY tahun DESC
";
$resultTahunan = mysqli_query($conn, $queryTahunan);

$queryPengeluaran = "
    SELECT 
        tanggal,
        kategori,
        deskripsi,
        jumlah
    FROM pengeluaran
    WHERE tanggal BETWEEN '$start_date' AND '$end_date'
    ORDER BY tanggal DESC
";
$resultPengeluaran = mysqli_query($conn, $queryPengeluaran);

// total pendapatan
$queryTotalPendapatan = "
    SELECT SUM(total_harga) AS total_pendapatan
    FROM booking
    WHERE DATE(tanggal_booking) BETWEEN '$start_date' AND '$end_date'
      AND status = 'dibayar'
";
$totalPendapatanResult = mysqli_query($conn, $queryTotalPendapatan);
$totalPendapatan = mysqli_fetch_assoc($totalPendapatanResult)['total_pendapatan'] ?? 0;

$queryTotalPengeluaran = "
    SELECT SUM(jumlah) AS total_pengeluaran
    FROM pengeluaran
    WHERE tanggal BETWEEN '$start_date' AND '$end_date'
";
$totalPengeluaranResult = mysqli_query($conn, $queryTotalPengeluaran);
$totalPengeluaran = mysqli_fetch_assoc($totalPengeluaranResult)['total_pengeluaran'] ?? 0;

// Calculate profit/loss
$laba_rugi = $totalPendapatan - $totalPengeluaran;
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Keuangan - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f8fff8;
    }

    .card {
      border-radius: 1rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    table th {
      background-color: #198754;
      color: #fff;
    }

    .section-title {
      color: #198754;
      font-weight: 700;
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

  <!-- Header -->
  <div class="text-center py-3 bg-white shadow-sm mb-4 no-print">
    <h1 class="text-success"><i class="bi bi-clipboard-data"></i> Laporan Keuangan Tiket</h1>
    <p class="lead">Laporan Bulanan dan Tahunan - Kebun Binatang Indah</p>
  </div>
  <div class="d-flex">
    <main class="flex-grow-1 p-4">
      <?php include 'sidebar.php'; ?>
      <div class="main-content">
        <div class="container mb-5">
          <div class="card p-4 mb-4 no-print">
            <h4 class="section-title mb-3"><i class="bi bi-funnel"></i> Filter Laporan</h4>
            <form method="GET" class="row g-3">
              <div class="col-md-4">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                  value="<?php echo htmlspecialchars($start_date); ?>">
              </div>
              <div class="col-md-4">
                <label for="end_date" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                  value="<?php echo htmlspecialchars($end_date); ?>">
              </div>
              <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success me-2"><i class="bi bi-search"></i> Cari</button>
                <a href="laporan_pendapatan.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i>
                  Reset</a>
              </div>
            </form>
          </div>

          <!-- Financial Summary Cards -->
          <div class="row mb-4">
            <div class="col-md-4">
              <div class="card bg-success text-white">
                <div class="card-body">
                  <h5 class="card-title"><i class="bi bi-arrow-down-circle"></i> Total Pendapatan</h5>
                  <h3>Rp <?php echo number_format($totalPendapatan, 0, ',', '.'); ?></h3>
                  <small>Periode:
                    <?php echo date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date)); ?></small>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card bg-danger text-white">
                <div class="card-body">
                  <h5 class="card-title"><i class="bi bi-arrow-up-circle"></i> Total Pengeluaran</h5>
                  <h3>Rp <?php echo number_format($totalPengeluaran, 0, ',', '.'); ?></h3>
                  <small>Periode:
                    <?php echo date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date)); ?></small>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card <?php echo $laba_rugi >= 0 ? 'bg-primary' : 'bg-danger'; ?> text-white">
                <div class="card-body">
                  <h5 class="card-title"><i class="bi bi-cash-stack"></i> Laba/Rugi</h5>
                  <h3>Rp <?php echo number_format($laba_rugi, 0, ',', '.'); ?></h3>
                  <small><?php echo $laba_rugi >= 0 ? 'Laba' : 'Rugi'; ?> Bersih</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Daily Report -->
          <div class="card p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h3 class="section-title"><i class="bi bi-calendar-day"></i> Laporan Harian</h3>
              <button class="btn btn-outline-success no-print" onclick="window.print()"><i class="bi bi-printer"></i>
                Print</button>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Pendapatan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (mysqli_num_rows($resultHarian) > 0) {
                    while ($row = mysqli_fetch_assoc($resultHarian)) {
                      $tanggal = date('d F Y', strtotime($row['tanggal']));
                      $jumlah_transaksi = $row['jumlah_transaksi'];
                      $total = number_format($row['total_harian'], 0, ',', '.');
                      echo "<tr>
                          <td>{$tanggal}</td>
                          <td class='text-center'>{$jumlah_transaksi}</td>
                          <td>Rp {$total}</td>
                        </tr>";
                    }
                  } else {
                    echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada data untuk periode yang dipilih</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Laporan Bulanan -->
          <div class="card p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h3 class="section-title"><i class="bi bi-calendar-month"></i> Laporan Penghasilan Bulanan</h3>
              <button class="btn btn-outline-success no-print" onclick="window.print()"><i class="bi bi-printer"></i>
                Print</button>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle">
                <thead>
                  <tr>
                    <th>Bulan</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Penghasilan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (mysqli_num_rows($resultBulanan) > 0) {
                    while ($row = mysqli_fetch_assoc($resultBulanan)) {
                      $bulan = date('F Y', strtotime($row['bulan'] . "-01"));
                      $jumlah_transaksi = $row['jumlah_transaksi'];
                      $total = number_format($row['total_bulanan'], 0, ',', '.');
                      echo "<tr>
                          <td>{$bulan}</td>
                          <td class='text-center'>{$jumlah_transaksi}</td>
                          <td>Rp {$total}</td>
                        </tr>";
                    }
                  } else {
                    echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada data penghasilan bulanan</td></tr>";
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
                    <th>Jumlah Transaksi</th>
                    <th>Total Penghasilan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (mysqli_num_rows($resultTahunan) > 0) {
                    while ($row = mysqli_fetch_assoc($resultTahunan)) {
                      $tahun = $row['tahun'];
                      $jumlah_transaksi = $row['jumlah_transaksi'];
                      $total = number_format($row['total_tahunan'], 0, ',', '.');
                      echo "<tr>
                          <td>{$tahun}</td>
                          <td class='text-center'>{$jumlah_transaksi}</td>
                          <td>Rp {$total}</td>
                        </tr>";
                    }
                  } else {
                    echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada data penghasilan tahunan</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- laporan pengeluaran -->
          <div class="card p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h3 class="section-title"><i class="bi bi-receipt"></i> Laporan Pengeluaran</h3>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (mysqli_num_rows($resultPengeluaran) > 0) {
                    while ($row = mysqli_fetch_assoc($resultPengeluaran)) {
                      $tanggal = date('d F Y', strtotime($row['tanggal']));
                      $kategori = htmlspecialchars($row['kategori']);
                      $deskripsi = htmlspecialchars($row['deskripsi']);
                      $jumlah = number_format($row['jumlah'], 0, ',', '.');
                      echo "<tr>
                          <td>{$tanggal}</td>
                          <td>{$kategori}</td>
                          <td>{$deskripsi}</td>
                          <td>Rp {$jumlah}</td>
                        </tr>";
                    }
                  } else {
                    echo "<tr><td colspan='4' class='text-center text-muted'>Belum ada data pengeluaran untuk periode yang dipilih</td></tr>";
                  }
                  ?>
                </tbody>
                <tfoot class="table-light">
                  <tr>
                    <th colspan="3" class="text-end">Total Pengeluaran:</th>
                    <th>Rp <?php echo number_format($totalPengeluaran, 0, ',', '.'); ?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <div class="mt-4 text-center no-print">
            <a href="dashboard.php" class="btn btn-outline-success"><i class="bi bi-arrow-left"></i> Kembali ke
              Dashboard</a>
            <a href="laporan_tiket.php" class="btn btn-success ms-2"><i class="bi bi-ticket-perforated"></i> Laporan
              Tiket</a>
          </div>
        </div>
      </div>

</body>

</html>