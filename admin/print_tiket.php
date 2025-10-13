<?php
include "../database/conn.php";

// Ambil ID dari URL dan pastikan angka
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tiket tidak valid.");
}

$id = intval($_GET['id']);

// Ambil data tiket
$result = $conn->query("SELECT * FROM booking WHERE id = $id");
$data = $result->fetch_assoc();

// Jika data tidak ditemukan
if (!$data) {
    die("Tiket tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cetak Tiket #<?= $data['id'] ?></title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .tiket { border: 2px dashed #000; padding: 20px; width: 400px; margin: 0 auto; }
    .tiket-header { text-align: center; margin-bottom: 15px; }
    .tiket-title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
    .tiket-info p { margin: 3px 0; }
  </style>
</head>
<body onload="window.print()">
  <div class="tiket">
    <div class="tiket-header">
      <div class="tiket-title">🎟️ Tiket Kebun Binatang Indah</div>
      <div>ID: <?= $data['id'] ?></div>
    </div>
    <div class="tiket-info">
      <p><strong>Nama:</strong> <?= htmlspecialchars($data['nama_pengunjung']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
      <p><strong>Tanggal Kunjungan:</strong> <?= date('d-m-Y', strtotime($data['tanggal_kunjungan'])) ?></p>
      <p>
        <strong>Dewasa:</strong> <?= $data['jumlah_dewasa'] ?> | 
        <strong>Remaja:</strong> <?= $data['jumlah_remaja'] ?> | 
        <strong>Anak:</strong> <?= $data['jumlah_anak'] ?> </p>
        <p><strong>Catatan:</strong> <?= htmlspecialchars($data['catatan']) ?></p>
        
      
      <p><strong>Status:</strong> <?= strtoupper($data['status']) ?></p>
    </div>
  </div>
</body>
</html>
