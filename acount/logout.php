<?php
session_start();
session_unset();   
session_destroy(); 


header("Location: ../pages/beranda.php"); 

header("Location: ../pages/index.php"); 
exit;
?>
