<?php
include "../database/conn.php";

// Validasi ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tiket tidak valid.");
}

$id = intval($_GET['id']);

// Ambil data tiket
$result = $conn->query("SELECT * FROM booking WHERE id = $id");
$data = $result->fetch_assoc();

if (!$data) {
    die("Tiket tidak ditemukan.");
}

// Siapkan data tampilan agar sama dengan halaman tiket user
$nomor = 'TKB' . date('Ymd', strtotime($data['tanggal_booking'])) . '-' . str_pad($data['id'], 3, '0', STR_PAD_LEFT);
$tanggal_kunjungan = date('d F Y', strtotime($data['tanggal_kunjungan']));
$kode_redeem = htmlspecialchars($data['kode_redeem'] ?? 'BELUM ADA');

$status = strtolower(trim($data['status']));
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
        $statusText = 'Ditolak';
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Tiket - Zoo Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px 0;
        }
        .ticket-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            border: none;
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
        @media print {
            body { padding: 0; }
            .container { width: 700px; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card ticket-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-ticket-perforated-fill me-2"></i>Nomor: <?= htmlspecialchars($nomor) ?></span>
                        <span class="status-badge <?= $statusClass ?>"><i class="bi <?= $statusIcon ?> me-1"></i><?= htmlspecialchars($statusText) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="ticket-info"><i class="bi bi-person-fill"></i>
                            <span>Nama: <strong><?= htmlspecialchars($data['nama_pengunjung']) ?></strong></span>
                        </div>
                        <div class="ticket-info"><i class="bi bi-envelope-fill"></i>
                            <span>Email: <strong><?= htmlspecialchars($data['email']) ?></strong></span>
                        </div>
                        <div class="ticket-info"><i class="bi bi-calendar-event"></i>
                            <span>Tanggal Kunjungan: <strong><?= htmlspecialchars($tanggal_kunjungan) ?></strong></span>
                        </div>
                        <?php if ($data['jumlah_dewasa'] > 0): ?>
                            <div class="ticket-info"><i class="bi bi-person-fill"></i><span>Dewasa: <strong><?= $data['jumlah_dewasa'] ?> orang</strong></span></div>
                        <?php endif; ?>
                        <?php if ($data['jumlah_remaja'] > 0): ?>
                            <div class="ticket-info"><i class="bi bi-person"></i><span>Remaja: <strong><?= $data['jumlah_remaja'] ?> orang</strong></span></div>
                        <?php endif; ?>
                        <?php if ($data['jumlah_anak'] > 0): ?>
                            <div class="ticket-info"><i class="bi bi-person-heart"></i><span>Anak-anak: <strong><?= $data['jumlah_anak'] ?> orang</strong></span></div>
                        <?php endif; ?>

                        <?php if ($status === 'dec'): ?>
                            <div class="alert alert-danger mt-3 mb-0">Alasan: <?= htmlspecialchars($data['alasan'] ?? 'Tidak diketahui') ?></div>
                        <?php endif; ?>

                        <div class="mt-3 text-center">
                            <p class="mb-1">Kode Redeem:</p>
                            <div class="ticket-code"><?= $kode_redeem ?></div>
                            <p class="text-muted mt-2 small">Tunjukkan kode ini saat memasuki kebun binatang</p>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-light">
                        <small class="text-muted">Dipesan pada: <?= date('d F Y H:i', strtotime($data['tanggal_booking'])) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
