<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['email'])) {
    $_SESSION['error'] = "Silakan login untuk membatalkan tiket.";
    header("Location: ../acount/login.php");
    exit;
}

$email = $_SESSION['email'];
$today = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['booking_id'])) {
    $_SESSION['error'] = "Permintaan tidak valid.";
    header("Location: tiket.php");
    exit;
}

$booking_id = intval($_POST['booking_id']);

// Ambil data booking milik user
$stmt = $conn->prepare("SELECT id, email, tanggal_kunjungan, jumlah_dewasa, jumlah_remaja, jumlah_anak, status FROM booking WHERE id = ? AND email = ?");
$stmt->bind_param("is", $booking_id, $email);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    $_SESSION['error'] = "Tiket tidak ditemukan atau bukan milik Anda.";
    header("Location: tiket.php");
    exit;
}
$row = $res->fetch_assoc();

$tanggal_kunjungan = $row['tanggal_kunjungan'];
$status = strtolower(trim($row['status']));
$total_tiket = intval($row['jumlah_dewasa']) + intval($row['jumlah_remaja']) + intval($row['jumlah_anak']);

// Validasi status: hanya dapat batal jika belum dibayar/dibatalkan/kadaluwarsa/ditolak
$tidak_bisa_batal = [ 'dibayar', 'kadaluwarsa', 'dec', 'declined', 'ditolak', 'dibatalkan' ];
if (in_array($status, $tidak_bisa_batal, true)) {
    $_SESSION['error'] = "Tiket dengan status tersebut tidak dapat dibatalkan.";
    header("Location: tiket.php");
    exit;
}

// Validasi tanggal: tidak boleh batal setelah lewat tanggal kunjungan
if (strtotime($tanggal_kunjungan) < strtotime($today)) {
    $_SESSION['error'] = "Tiket sudah melewati tanggal kunjungan dan tidak dapat dibatalkan.";
    header("Location: tiket.php");
    exit;
}

if ($total_tiket <= 0) {
    $_SESSION['error'] = "Data jumlah tiket tidak valid.";
    header("Location: tiket.php");
    exit;
}

$conn->begin_transaction();
try {
    // Update status booking menjadi dibatalkan
    $upd = $conn->prepare("UPDATE booking SET status = 'dibatalkan' WHERE id = ?");
    $upd->bind_param("i", $booking_id);
    $upd->execute();

    // Pulihkan stok per tanggal
    $cek = $conn->prepare("SELECT sisa_stok FROM stok_tiket WHERE tanggal = ?");
    $cek->bind_param("s", $tanggal_kunjungan);
    $cek->execute();
    $stok_res = $cek->get_result();

    if ($stok_res->num_rows > 0) {
        $upd_stok = $conn->prepare("UPDATE stok_tiket SET sisa_stok = sisa_stok + ? WHERE tanggal = ?");
        $upd_stok->bind_param("is", $total_tiket, $tanggal_kunjungan);
        $upd_stok->execute();
    } else {
        // Jika belum ada stok untuk tanggal tersebut, buat baris baru
        $ins_stok = $conn->prepare("INSERT INTO stok_tiket (tanggal, sisa_stok) VALUES (?, ?)");
        $ins_stok->bind_param("si", $tanggal_kunjungan, $total_tiket);
        $ins_stok->execute();
    }

    $conn->commit();
    $_SESSION['success'] = "Tiket berhasil dibatalkan dan stok telah dikembalikan.";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Gagal membatalkan tiket: " . $e->getMessage();
}

header("Location: tiket.php");
exit;
?>