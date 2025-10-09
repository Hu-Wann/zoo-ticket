<?php
session_start();
include "../database/conn.php"; // pastikan file ini mendefinisikan $koneksi (mysqli)
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya - Zoo Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .ticket-header {
            background-color: #198754;
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ticket-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            border: none;
            transition: transform .3s;
        }

        .ticket-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #198754;
            color: white;
            font-weight: bold;
            padding: 1rem;
        }

        .status-badge {
            padding: .25rem .75rem;
            border-radius: 1rem;
            font-size: .9rem;
            font-weight: 600;
        }

        .ticket-code {
            font-family: monospace;
            font-size: 1.2rem;
            font-weight: bold;
            color: #198754;
            background-color: #e9f7ef;
            padding: .5rem;
            border-radius: .5rem;
            display: inline-block;
        }

        .ticket-info {
            display: flex;
            align-items: center;
            margin-bottom: .5rem;
        }

        .ticket-info i {
            margin-right: .5rem;
            color: #198754;
        }

        .no-tickets {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: #198754;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="beranda.php">
                <i class="bi bi-tree-fill me-2"></i>Kebun Binatang Indah
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="beranda.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="animal.php">Hewan</a></li>
                    <li class="nav-item"><a class="nav-link" href="booking.php">Booking</a></li>
                    <li class="nav-item"><a class="nav-link active" href="tiket.php">Tiket Saya</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link text-primary fw-bold" href="../admin/dashboard.php">
                                <i class="bi bi-speedometer2"></i> Admin Panel
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['email'])): ?>
                        <li class="nav-item">
                            <span class="nav-link text-success">👋 <?php echo htmlspecialchars($_SESSION['nama'] ?? ''); ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="../acount/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-success" href="../acount/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="ticket-header">
        <div class="container text-center">
            <h1><i class="bi bi-ticket-perforated-fill me-2"></i>Tiket Saya</h1>
            <p class="lead">Lihat dan kelola tiket yang telah Anda pesan</p>
        </div>
    </div>

    <!-- Ticket Content -->
    <div class="container mb-5">
        <?php
        // jika belum login tunjukkan pesan (tanpa warning)
        if (!isset($_SESSION['email'])) {
            echo '<div class="no-tickets">
            <i class="bi bi-ticket-perforated-fill text-muted" style="font-size:4rem"></i>
            <h3 class="mt-3">Silakan login untuk melihat tiket Anda</h3>
            <p class="text-muted">Masuk untuk melihat detail tiket yang sudah dipesan.</p>
            <a href="../acount/login.php" class="btn btn-success mt-2"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
          </div>';
        } else {
            // ambil booking user dari DB
            $email = mysqli_real_escape_string($conn, $_SESSION['email']);
            $query = "SELECT * FROM booking WHERE email = '$email' ORDER BY tanggal_booking DESC";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                echo '<div class="row">';
                while ($row = mysqli_fetch_assoc($result)) {

                    // jika ingin HIDE tiket yg ditolak, uncomment baris berikut:
                    // if (isset($row['status']) && $row['status'] === 'dec') continue;

                    $nomor = 'TKB' . date('Ymd', strtotime($row['tanggal_booking'])) . '-' . str_pad($row['id'], 3, '0', STR_PAD_LEFT);
                    $kode_redeem = strtoupper(substr(md5($row['id'] . $row['email'] . $row['tanggal_booking']), 0, 4)) . '-' . rand(1000, 9999);
                    $tanggal_kunjungan = date('d F Y', strtotime($row['tanggal_kunjungan']));

                    // Tentukan status tampilannya
                    $statusText = 'Dibooking';
                    $statusClass = 'bg-warning text-dark';
                    $statusIcon = 'bi-clock-history';
                    if (isset($row['status'])) {
                        if ($row['status'] === 'acc') {
                            $statusText = 'Disetujui';
                            $statusClass = 'bg-success text-white';
                            $statusIcon = 'bi-check-circle-fill';
                        } elseif ($row['status'] === 'dec') {
                            $statusText = 'Ditolak';
                            $statusClass = 'bg-danger text-white';
                            $statusIcon = 'bi-x-circle-fill';
                        } elseif ($row['status'] === 'dibooking') {
                            $statusText = 'Dibooking';
                            $statusClass = 'bg-warning text-dark';
                            $statusIcon = 'bi-clock-history';
                        }
                    }

                    // output card
                    echo '<div class="col-md-6 mb-4">
                    <div class="card ticket-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-ticket-perforated-fill me-2"></i>Nomor: ' . htmlspecialchars($nomor) . '</span>
                            <span class="status-badge ' . $statusClass . '"><i class="bi ' . $statusIcon . ' me-1"></i>' . htmlspecialchars($statusText) . '</span>
                        </div>
                        <div class="card-body">
                            <div class="ticket-info"><i class="bi bi-calendar-event"></i>
                                <span>Tanggal Kunjungan: <strong>' . htmlspecialchars($tanggal_kunjungan) . '</strong></span>
                            </div>';

                    if (!empty($row['jumlah_dewasa']) && $row['jumlah_dewasa'] > 0) {
                        echo '<div class="ticket-info"><i class="bi bi-person-fill"></i><span>Dewasa: <strong>' . (int)$row['jumlah_dewasa'] . ' tiket</strong></span></div>';
                    }
                    if (!empty($row['jumlah_remaja']) && $row['jumlah_remaja'] > 0) {
                        echo '<div class="ticket-info"><i class="bi bi-person"></i><span>Remaja: <strong>' . (int)$row['jumlah_remaja'] . ' tiket</strong></span></div>';
                    }
                    if (!empty($row['jumlah_anak']) && $row['jumlah_anak'] > 0) {
                        echo '<div class="ticket-info"><i class="bi bi-person-heart"></i><span>Anak-anak: <strong>' . (int)$row['jumlah_anak'] . ' tiket</strong></span></div>';
                    }

                    // jika tiket ditolak tambahkan keterangan (jika ada kolom alasan di DB)
                    if (isset($row['status']) && $row['status'] === 'dec') {
                        if (!empty($row['alasan'])) {
                            echo '<div class="alert alert-danger mt-3 mb-0">Alasan penolakan: ' . htmlspecialchars($row['alasan']) . '</div>';
                        } else {
                            echo '<div class="alert alert-danger mt-3 mb-0">Tiket ini ditolak oleh admin.</div>';
                        }
                    }

                    echo '<div class="mt-3 text-center">
                    <p class="mb-1">Kode Redeem:</p>
                    <div class="ticket-code">' . htmlspecialchars($kode_redeem) . '</div>
                    <p class="text-muted mt-2 small">Tunjukkan kode ini saat memasuki kebun binatang</p>
                  </div>
                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">Dipesan pada: ' . date('d F Y H:i', strtotime($row['tanggal_booking'])) . '</small>
                </div>
            </div>
        </div>';
                } // end while

                echo '</div>'; // end row
            } else {
                echo '<div class="no-tickets">
                <i class="bi bi-ticket-perforated-fill text-muted" style="font-size:4rem"></i>
                <h3 class="mt-3">Belum ada tiket yang dipesan</h3>
                <p class="text-muted">Anda belum memesan tiket kebun binatang</p>
                <a href="booking.php" class="btn btn-success mt-2"><i class="bi bi-plus-circle me-2"></i>Pesan Tiket Sekarang</a>
              </div>';
            }
        }
        ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-tree-fill me-2"></i>Zoo Ticket</h5>
                    <p>Sistem pemesanan tiket kebun binatang online yang mudah dan cepat.</p>
                </div>
                <div class="col-md-3">
                    <h5>Menu</h5>
                    <ul class="list-unstyled">
                        <li><a href="beranda.php" class="text-white">Beranda</a></li>
                        <li><a href="animal.php" class="text-white">Hewan</a></li>
                        <li><a href="booking.php" class="text-white">Booking</a></li>
                        <li><a href="tiket.php" class="text-white">Tiket Saya</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt-fill me-2"></i>Jl. Kebun Binatang No. 1</li>
                        <li><i class="bi bi-telephone-fill me-2"></i>(021) 1234-5678</li>
                        <li><i class="bi bi-envelope-fill me-2"></i>info@zooticket.com</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Zoo Ticket. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>