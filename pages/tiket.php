<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['email'])) {
    header("Location: ../acount/login.php");
    exit;
}

$email = $_SESSION['email'];
$today = date('Y-m-d');

// ✅ Update otomatis semua tiket kadaluwarsa (AMAN)
$conn->query("
    UPDATE booking
    SET status = 'kadaluwarsa'
    WHERE email = '$email'
      AND tanggal_kunjungan < '$today'
      AND status NOT IN ('kadaluwarsa')
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya - Zoo Ticket</title>
    <?php include '../bootstrap.php'; ?>
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
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 1rem;
            border: none;
            transition: transform .3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .ticket-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #198754;
            color: white;
            font-weight: 600;
            padding: 0.75rem;
            font-size: 0.95rem;
        }

        .ticket-card .card-body {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }

        .ticket-card .card-footer {
            margin-top: auto;
        }

        .status-badge {
            padding: .2rem .6rem;
            border-radius: 0.9rem;
            font-size: .8rem;
            font-weight: 600;
        }

        .ticket-code {
            font-family: monospace;
            font-size: 1rem;
            font-weight: 600;
            color: #198754;
            background-color: #e9f7ef;
            padding: .4rem .5rem;
            border-radius: .4rem;
            display: inline-block;
        }

        .ticket-info {
            display: flex;
            align-items: center;
            margin-bottom: .4rem;
            font-size: 0.95rem;
        }

        .ticket-info i {
            margin-right: .45rem;
            color: #198754;
            font-size: 0.95rem;
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

        /* Sidebar (match index.php) */
        .sidebar {
            min-height: 100vh;
            background-color: #d4f8d4;
            padding-top: 20px;
            border-right: 2px solid #b2f7b2;
        }

        .sidebar-link {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            transition: background 0.2s, color 0.2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: linear-gradient(90deg, #e8fbe8 60%, #b2f7b2 100%);
            color: #157347 !important;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="bi bi-tree-fill me-2"></i>Kebun Binatang Indah
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link text-primary fw-bold" href="../admin/dashboard.php">
                                <i class="bi bi-speedometer2"></i> Admin Panel
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['email'])): ?>
                        <li class="nav-item">
                            <span class="nav-link text-success">👋 <?= htmlspecialchars($_SESSION['nama'] ?? ''); ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="../acount/logout.php"><i
                                    class="bi bi-box-arrow-right"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-success" href="../acount/login.php"><i
                                    class="bi bi-box-arrow-in-right"></i> Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="ticket-header text-center">
        <div class="container">
            <h1><i class="bi bi-ticket-perforated-fill me-2"></i>Tiket Saya</h1>
            <p class="lead">Lihat dan kelola tiket yang telah Anda pesan</p>
        </div>
    </div>

    <div class="container-fluid mb-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar position-sticky" style="top: 80px; height: calc(100vh - 80px); z-index: 2;">
                <?php $page = basename($_SERVER['SCRIPT_NAME']); ?>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link <?php echo $page === 'index.php' ? 'active' : ''; ?>"
                            href="../index.php">
                            <i class="bi bi-house-door me-2"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link <?php echo $page === 'animal.php' ? 'active' : ''; ?>"
                            href="animal.php">
                            <i class="bi bi-paw me-2"></i> Animal
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link <?php echo $page === 'booking.php' ? 'active' : ''; ?>"
                            href="booking.php">
                            <i class="bi bi-ticket-perforated me-2"></i> Booking Tiket
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10 p-4">
                <?php
                // Debug: Tampilkan email yang digunakan untuk query
                error_log("Debug: Query booking untuk email: " . $email);

                $query = "SELECT * FROM booking WHERE email = '$email' ORDER BY tanggal_booking DESC";
                $result = mysqli_query($conn, $query);

                // Debug: Tampilkan jumlah rows
                if ($result) {
                    error_log("Debug: Jumlah rows ditemukan: " . mysqli_num_rows($result));
                } else {
                    error_log("Debug: Query error: " . mysqli_error($conn));
                }

                if ($result && mysqli_num_rows($result) > 0) {
                    echo '<div class="row">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $nomor = 'TKB' . date('Ymd', strtotime($row['tanggal_booking'])) . '-' . str_pad($row['id'], 3, '0', STR_PAD_LEFT);
                        $tanggal_kunjungan = date('d F Y', strtotime($row['tanggal_kunjungan']));

                        // Gunakan kode redeem dari database
                        $kode_redeem = htmlspecialchars($row['kode_redeem'] ?? 'BELUM ADA');

                        // Tentukan status tampilan
                        $status = strtolower(trim($row['status']));
                        switch ($status) {
                            case 'acc':
                            case 'accepted':
                            case 'disetujui':
                                $statusText = 'Disetujui';
                                $statusClass = 'bg-success text-white';
                                $statusIcon = 'bi-check-circle-fill';
                                break;
                            case 'dec':
                            case 'declined':
                            case 'ditolak':
                            case 'dibatalkan':
                                $statusText = 'Dibatalkan';
                                $statusClass = 'bg-danger text-white';
                                $statusIcon = 'bi-x-circle-fill';
                                break;
                            case 'dibayar':
                            case 'dibayar':
                            case 'dibayar':
                            case 'dibayar':
                                $statusText = 'Dibayar';
                                $statusClass = 'bg-primary text-white';
                                $statusIcon = 'bi-check-circle-fill';
                                break;
                            case 'kadaluwarsa':
                                $statusText = 'Kadaluwarsa';
                                $statusClass = 'bg-secondary text-white';
                                $statusIcon = 'bi-hourglass-split';
                                break;
                            case 'dibooking':
                            case 'pending':
                            default:
                                $statusText = 'Dibooking';
                                $statusClass = 'bg-warning text-dark';
                                $statusIcon = 'bi-clock-history';
                                break;
                        }

                        // Tentukan apakah tiket bisa dibatalkan oleh user
                        $canCancel = true;
                        $blockedStatuses = ['dibayar', 'kadaluwarsa', 'dec', 'declined', 'ditolak', 'dibatalkan'];
                        if (in_array($status, $blockedStatuses, true)) {
                            $canCancel = false;
                        }
                        if (strtotime($row['tanggal_kunjungan']) < strtotime(date('Y-m-d'))) {
                            $canCancel = false; // tidak bisa batal jika tanggal sudah lewat
                        }

                echo '
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                    <div class="card ticket-card h-100 w-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-ticket-perforated-fill me-2"></i>Nomor: ' . htmlspecialchars($nomor) . '</span>
                            <span class="status-badge ' . $statusClass . '"><i class="bi ' . $statusIcon . ' me-1"></i>' . htmlspecialchars($statusText) . '</span>
                        </div>
                        <div class="card-body">
                            <div class="ticket-info"><i class="bi bi-calendar-event"></i>
                                <span>Tanggal Kunjungan: <strong>' . htmlspecialchars($tanggal_kunjungan) . '</strong></span>
                            </div>';

                        if ($row['jumlah_dewasa'] > 0)
                            echo '<div class="ticket-info"><i class="bi bi-person-fill"></i><span>Dewasa: <strong>' . $row['jumlah_dewasa'] . ' orang</strong></span></div>';
                        if ($row['jumlah_remaja'] > 0)
                            echo '<div class="ticket-info"><i class="bi bi-person"></i><span>Remaja: <strong>' . $row['jumlah_remaja'] . ' orang</strong></span></div>';
                        if ($row['jumlah_anak'] > 0)
                            echo '<div class="ticket-info"><i class="bi bi-person-heart"></i><span>Anak-anak: <strong>' . $row['jumlah_anak'] . ' orang</strong></span></div>';

                        if ($row['status'] === 'dec') {
                            echo '<div class="alert alert-danger mt-3 mb-0">Alasan: ' . htmlspecialchars($row['alasan'] ?? 'Tidak diketahui') . '</div>';
                        }

                        echo '
                    <div class="mt-3 text-center">
                        <p class="mb-1">Kode Redeem:</p>
                        <div class="ticket-code">' . $kode_redeem . '</div>
                        <p class="text-muted mt-2 small">Tunjukkan bukti ini pada karcis di kebun binatang</p>';
                        if ($status !== 'kadaluwarsa') {
                            // Tampilkan tombol Batalkan jika memenuhi syarat (tanpa karakter \n)
                            if ($canCancel) {
                                echo '<form method="POST" action="cancel_tiket.php" class="mt-2" onsubmit="return confirm(\'Yakin ingin membatalkan tiket ini?\')">';
                                echo '<input type="hidden" name="booking_id" value="' . intval($row['id']) . '">';
                                echo '<button type="submit" class="btn btn-outline-danger btn-sm">';
                                echo '<i class="bi bi-x-circle"></i> Batalkan';
                                echo '</button>';
                                echo '</form>';
                            }
                        } else {
                            echo '<button class="btn btn-outline-secondary btn-sm mt-3" disabled>
                        <i class="bi bi-x-circle"></i> Tidak Dapat Dicetak
                    </button>';
                        }

                        echo '
                    </div>
                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">Dipesan pada: ' . date('d F Y H:i', strtotime($row['tanggal_booking'])) . '</small>
                </div>
            </div>
        </div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="no-tickets">
                <i class="bi bi-ticket-perforated-fill text-muted" style="font-size:4rem"></i>
                <h3 class="mt-3">Belum ada tiket yang dipesan</h3>
                <p class="text-muted">Anda belum memesan tiket kebun binatang</p>
                <a href="booking.php" class="btn btn-success mt-2"><i class="bi bi-plus-circle me-2"></i>Pesan Tiket Sekarang</a>
            </div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>