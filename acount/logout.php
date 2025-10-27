<?php
session_start();
session_unset();   
session_destroy(); 

<<<<<<< HEAD
header("Location: ../pages/beranda.php"); 
=======
header("Location: ../pages/index.php"); // Arahkan ke halaman utama
>>>>>>> fe5caa6787f979ef370fab3cd40cb25a7f2ce130
exit;
?>
