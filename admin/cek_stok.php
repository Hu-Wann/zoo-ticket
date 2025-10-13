<?php
header('Content-Type: application/json');
include "../database/conn.php";

if (!isset($_GET['tanggal'])) {
    echo json_encode(["status" => "error", "message" => "Tanggal tidak ada"]);
    exit;
}

$tanggal = mysqli_real_escape_string($conn, $_GET['tanggal']);
$query = $conn->query("SELECT sisa_stok FROM stok_tiket WHERE tanggal = '$tanggal'");

if ($query->num_rows > 0) {
    $row = $query->fetch_assoc();
    echo json_encode([
        "status" => "ok",
        "stok" => (int)$row['sisa_stok']
    ]);
} else {
    echo json_encode([
        "status" => "ok",
        "stok" => 0  
    ]);
}
?>
