<?php
session_start();
include "../database/conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_pengunjung = $_POST['nama_pengunjung'];
    $email = $_POST['email'];
    $tanggal_kunjungan = $_POST['tanggal_kunjungan'];
    $jumlah_dewasa = (int)$_POST['jumlah_dewasa'];
    $jumlah_remaja = (int)$_POST['jumlah_remaja'];
    $jumlah_anak = (int)$_POST['jumlah_anak'];
    $catatan = $_POST['catatan'] ?? '';

    // Total tiket dipesan
    $total_tiket = $jumlah_dewasa + $jumlah_remaja + $jumlah_anak;

    // Cek stok tiket global
    $cek_stok = $conn->query("SELECT jumlah FROM stok_tiket WHERE id = 1");
    $stok = $cek_stok->fetch_assoc()['jumlah'] ?? 0;

    if ($stok < $total_tiket) {
        echo "<script>alert('Stok tiket tidak mencukupi! Sisa stok: $stok'); window.location='booking.php';</script>";
        exit;
    }

    // Simpan data booking
    $query = "INSERT INTO booking (nama_pengunjung, email, tanggal_kunjungan, jumlah_dewasa, jumlah_remaja, jumlah_anak, catatan, tanggal_booking, status)
              VALUES ('$nama_pengunjung', '$email', '$tanggal_kunjungan', '$jumlah_dewasa', '$jumlah_remaja', '$jumlah_anak', '$catatan', NOW(), 'dibooking')";
    
    if ($conn->query($query)) {
        // Kurangi stok sesuai total tiket
        $conn->query("UPDATE stok_tiket SET jumlah = jumlah - $total_tiket WHERE id = 1");
        echo "<script>alert('Tiket berhasil dipesan!'); window.location='tiket.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memesan tiket.'); window.location='booking.php';</script>";
    }
}
?>
