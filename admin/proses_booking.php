<?php
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

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiiisi", $nama, $email, $tanggal, $dewasa, $anak, $remaja, $catatan, $total);

if ($stmt->execute()) {
    echo "<script>
            alert('Booking berhasil! Total harga: Rp " . number_format($total, 0, ',', '.') . "');
            window.location='booking.html';
          </script>";
} else {
    echo "Terjadi kesalahan: " . $stmt->error;
}


// stok 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email             = $_SESSION['email'];
    $tanggal_kunjungan = $_POST['tanggal_kunjungan'];
    $dewasa            = (int)$_POST['jumlah_dewasa'];
    $anak              = (int)$_POST['jumlah_anak'];
    $remaja            = (int)$_POST['jumlah_remaja'];

    $total_tiket = $dewasa + $anak + $remaja;

    // 1️⃣ Cek apakah stok untuk tanggal ini sudah ada
    $stokQuery = "SELECT * FROM stok_tiket WHERE tanggal = '$tanggal_kunjungan'";
    $stokResult = $conn->query($stokQuery);

    if ($stokResult->num_rows == 0) {
        // Buat stok baru (500) untuk tanggal tersebut jika belum ada
        $conn->query("INSERT INTO stok_tiket (tanggal, sisa_stok) VALUES ('$tanggal_kunjungan', 500)");
        $sisa_stok = 500;
    } else {
        $stokData = $stokResult->fetch_assoc();
        $sisa_stok = (int)$stokData['sisa_stok'];
    }

    // 2️⃣ Validasi stok
    if ($total_tiket > $sisa_stok) {
        echo "<script>alert('❌ Stok tiket untuk tanggal tersebut tidak mencukupi! Sisa: $sisa_stok'); window.location.href='../pages/booking.php';</script>";
        exit;
    }

    // 3️⃣ Lanjutkan booking jika stok cukup
    $insertBooking = "INSERT INTO booking (email, tanggal_kunjungan, jumlah_dewasa, jumlah_anak, jumlah_remaja, status, tanggal_booking)
                      VALUES ('$email', '$tanggal_kunjungan', '$dewasa', '$anak', '$remaja', 'dibooking', NOW())";

    if ($conn->query($insertBooking) === TRUE) {
        // 4️⃣ Kurangi stok
       // Hitung total tiket yang benar-benar sudah dibooking untuk tanggal tersebut
$totalBookingQuery = $conn->query("
    SELECT 
      SUM(jumlah_dewasa + jumlah_anak + jumlah_remaja) AS total_terbooking
    FROM booking
    WHERE tanggal_kunjungan = '$tanggal_kunjungan'
");
$totalBookingData = $totalBookingQuery->fetch_assoc();
$totalTerbooking = (int)$totalBookingData['total_terbooking'];

// Stok awal per tanggal (misalnya default 500 jika belum ada)
$stokAwalQuery = $conn->query("SELECT sisa_stok FROM stok_tiket WHERE tanggal = '$tanggal_kunjungan'");
if ($stokAwalQuery->num_rows > 0) {
    $stokAwalData = $stokAwalQuery->fetch_assoc();
    $stokAwal = (int)$stokAwalData['sisa_stok'];
} else {
    $stokAwal = 500;
    $conn->query("INSERT INTO stok_tiket (tanggal, sisa_stok) VALUES ('$tanggal_kunjungan', $stokAwal)");
}

// Hitung sisa stok berdasarkan total booking aktual
$sisa_stok_baru = $stokAwal - $totalTerbooking;
if ($sisa_stok_baru < 0) $sisa_stok_baru = 0;

$conn->query("UPDATE stok_tiket SET sisa_stok = $sisa_stok_baru WHERE tanggal = '$tanggal_kunjungan'");


        echo "<script>alert('✅ Tiket berhasil dibooking!'); window.location.href='../pages/tiket.php';</script>";
    } else {
        echo "<script>alert('❌ Terjadi kesalahan saat booking: " . $conn->error . "');</script>";
    }
}

$stmt->close();
$conn->close();
?>