<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['email'])) {
    header("Location: ../acount/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pengunjung = mysqli_real_escape_string($conn, $_POST['nama_pengunjung']);
    $email           = $_SESSION['email'];
    $tanggal         = mysqli_real_escape_string($conn, $_POST['tanggal_kunjungan']);
    $dewasa          = (int)$_POST['dewasa'];
    $remaja          = (int)$_POST['remaja'];
    $anak            = (int)$_POST['anak'];
    $catatan         = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

    // 🕓 Validasi tanggal kunjungan
    $today = date('Y-m-d');
    $deadline = date('Y-m-d', strtotime('+30 days')); // contoh: booking maksimal 30 hari ke depan

    if ($tanggal < $today) {
        $_SESSION['error'] = "Tanggal kunjungan tidak boleh tanggal yang sudah lewat.";
        header("Location: ../pages/booking.php");
        exit;
    }

    if ($tanggal > $deadline) {
        $_SESSION['error'] = "Pemesanan hanya bisa dilakukan maksimal 30 hari ke depan.";
        header("Location: ../pages/booking.php");
        exit;
    }

    // Harga tiket
    $harga_dewasa = 40000;
    $harga_remaja = 35000;
    $harga_anak   = 25000;

    $total_harga = ($dewasa * $harga_dewasa) + ($remaja * $harga_remaja) + ($anak * $harga_anak);

    if ($dewasa + $remaja + $anak === 0) {
        $_SESSION['error'] = "Minimal pesan 1 tiket.";
        header("Location: ../pages/booking.php");
        exit;
    }

    $query = "INSERT INTO booking 
        (nama_pengunjung, email, tanggal_kunjungan, jumlah_dewasa, jumlah_remaja, jumlah_anak, total_harga, tanggal_booking, catatan) 
        VALUES 
        ('$nama_pengunjung', '$email', '$tanggal', $dewasa, $remaja, $anak, $total_harga, NOW(), '$catatan')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Booking berhasil!";
        header("Location: ../pages/tiket.php");
        exit;
    } else {
        die("Gagal booking: " . mysqli_error($conn));
    }
}
?>
