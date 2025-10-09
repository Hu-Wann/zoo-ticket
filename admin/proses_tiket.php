<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id = intval($_GET['id']);
    $aksi = $_GET['aksi'];

    if ($aksi === 'acc') {
        $status = 'acc';
    } elseif ($aksi === 'dec') {
        $status = 'dec';
    } else {
        header("Location: tiket_list.php");
        exit;
    }

    $query = "UPDATE booking SET status = '$status' WHERE id = $id";
    if ($conn->query($query)) {
        header("Location: tiket_list.php");
        exit;
    } else {
        echo "Gagal memperbarui status tiket.";
    }
} else {
    header("Location: tiket_list.php");
    exit;
}
