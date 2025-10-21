<?php
session_start();
session_unset();   // Hapus semua data session
session_destroy(); // Hancurkan session

header("Location: ../pages/index.php"); // Arahkan ke halaman utama
exit;
?>
