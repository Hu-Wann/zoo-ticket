<?php
session_start();
include "../database/conn.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['email'])) {
        $_SESSION['error'] = "Session email tidak ditemukan. Silakan login kembali.";
        header("Location: ../pages/booking.php");
        exit;
    }
    
    $nama_pengunjung = mysqli_real_escape_string($conn, $_POST['nama_pengunjung']);
    $email           = $_SESSION['email'];
    $tanggal_kunjungan = mysqli_real_escape_string($conn, $_POST['tanggal_kunjungan']);
    $dewasa          = (int)$_POST['dewasa'];
    $remaja          = (int)$_POST['remaja'];
    $anak            = (int)$_POST['anak'];
    $catatan         = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

    $today = date('Y-m-d');
    $deadline = date('Y-m-d', strtotime('+30 days'));
    if ($tanggal_kunjungan < $today) {
        $_SESSION['error'] = "Tanggal kunjungan tidak boleh lewat.";
        header("Location: ../pages/booking.php");
        exit;
    }
    if ($tanggal_kunjungan > $deadline) {
        $_SESSION['error'] = "Pemesanan hanya bisa maksimal 30 hari ke depan.";
        header("Location: ../pages/booking.php");
        exit;
    }

    $harga_dewasa = 40000;
    $harga_remaja = 35000;
    $harga_anak   = 25000;
    $total_tiket = $dewasa + $remaja + $anak;

    if ($total_tiket === 0) {
        $_SESSION['error'] = "Minimal pesan 1 tiket.";
        header("Location: ../pages/booking.php");
        exit;
    }

    $total_harga = ($dewasa * $harga_dewasa) + ($remaja * $harga_remaja) + ($anak * $harga_anak);

    $stok = $conn->query("SELECT sisa_stok FROM stok_tiket WHERE tanggal = '$tanggal_kunjungan'");
    if ($stok->num_rows === 0) {
        $_SESSION['error'] = "Stok tiket tidak tersedia untuk tanggal " . date('d-m-Y', strtotime($tanggal_kunjungan)) . ".";
        header("Location: ../pages/booking.php");
        exit;
    } else {
        $stok_data = $stok->fetch_assoc();
        $sisa_stok = (int)$stok_data['sisa_stok'];
    }

    if ($sisa_stok < $total_tiket) {
        $_SESSION['error'] = "Stok tiket untuk tanggal " . date('d-m-Y', strtotime($tanggal_kunjungan)) . " tidak mencukupi. Sisa stok: $sisa_stok.";
        header("Location: ../pages/booking.php");
        exit;
    }

    $conn->begin_transaction();

    try {
        // Generate unique redeem code
        $kode_redeem = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4)) . '-' . rand(1000, 9999);

        // Simpan data booking - Sesuaikan urutan kolom dengan struktur tabel
        $query = "INSERT INTO booking 
            (nama_pengunjung, email, tanggal_kunjungan, jumlah_dewasa, jumlah_remaja, jumlah_anak, total_harga, catatan, status, kode_redeem, tanggal_booking)
            VALUES 
            ('{$nama_pengunjung}', '{$email}', '{$tanggal_kunjungan}', {$dewasa}, {$remaja}, {$anak}, {$total_harga}, '{$catatan}', 'dibooking', '{$kode_redeem}', NOW())";

        $result = $conn->query($query);
        if (!$result) {
            throw new Exception("Gagal menyimpan booking: " . $conn->error);
        }

        // Kurangi stok sesuai total tiket
        $update_result = $conn->query("UPDATE stok_tiket SET sisa_stok = sisa_stok - $total_tiket WHERE tanggal = '$tanggal_kunjungan'");
        if (!$update_result) {
            throw new Exception("Gagal mengupdate stok tiket: " . $conn->error);
        }

        // Commit transaksi
        $conn->commit();

        $_SESSION['success'] = "Booking berhasil! Anda memesan $total_tiket tiket untuk tanggal " . date('d-m-Y', strtotime($tanggal_kunjungan));
        header("Location: ../pages/tiket.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Terjadi kesalahan saat booking: " . $e->getMessage();
        header("Location: ../pages/booking.php");
        exit;
    }
}
?>
