<?php
session_start();
session_unset();   
session_destroy(); 

<<<<<<< HEAD
<<<<<<< HEAD
header("Location: ../pages/beranda.php"); 
=======
header("Location: ../pages/index.php"); // Arahkan ke halaman utama
>>>>>>> fe5caa6787f979ef370fab3cd40cb25a7f2ce130
=======

header("Location: ../pages/beranda.php"); 

header("Location: ../pages/index.php"); 
>>>>>>> f7f9847ab7cc248c7216a26b616e873095d4cb17
exit;
?>
