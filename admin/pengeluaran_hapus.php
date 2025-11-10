<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit();
}

if (!isset($_GET['tanggal'])) {
    header("Location: pengeluaran_list.php");
    exit();
}

$tanggal = $_GET['tanggal'];

// Cek apakah ada data untuk tanggal tersebut
$check_query = "SELECT COUNT(*) as count FROM pengeluaran WHERE tanggal = '$tanggal'";
$check_result = mysqli_query($conn, $check_query);
$row = mysqli_fetch_assoc($check_result);

if ($row['count'] > 0) {
    // Hapus semua data pengeluaran pada tanggal yang ditentukan
    $delete_query = "DELETE FROM pengeluaran WHERE tanggal = '$tanggal'";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success'] = "Semua data pengeluaran pada tanggal $tanggal berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus data pengeluaran.";
    }
} else {
    $_SESSION['error'] = "Tidak ada data pengeluaran yang ditemukan untuk tanggal $tanggal.";
}

header("Location: pengeluaran_list.php");
exit();
?>