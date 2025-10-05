<?php
// Konfigurasi koneksi
include '../database/conn.php';

// Ambil data dari form
$nama    = $_POST['nama'] ?? '';
$email   = $_POST['email'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$dewasa  = (int) ($_POST['dewasa'] ?? 0);
$anak    = (int) ($_POST['anak'] ?? 0);
$remaja  = (int) ($_POST['remaja'] ?? 0);
$catatan = $_POST['catatan'] ?? '';

// Hitung total harga
$total = ($dewasa * 40000) + ($anak * 25000) + ($remaja * 35000);

// Simpan ke tabel transaksi
$sql = "INSERT INTO transaksi 
(nama_pengunjung, email, tanggal_kunjungan, jumlah_dewasa, jumlah_anak, jumlah_remaja, catatan, total_harga, tanggal_transaksi) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("sssiiisi", $nama, $email, $tanggal, $dewasa, $anak, $remaja, $catatan, $total);

if ($stmt->execute()) {
    echo "<script>
            alert('Booking berhasil! Total harga: Rp " . number_format($total, 0, ',', '.') . "');
            window.location='booking.html';
          </script>";
} else {
    echo "Terjadi kesalahan: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>
