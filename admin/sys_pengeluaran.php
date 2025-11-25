<?php

function pengeluaran_kategori_list(): array
{
    return [
        "Gaji Karyawan" => "bi-person-badge",
        "Pangan" => "bi-egg-fried",
        "Perawatan Hewan" => "bi-heart-pulse",
        "Pemeliharaan Kandang" => "bi-house-gear",
    ];
}

function rupiah_to_int(?string $nilai): int
{
    if ($nilai === null || $nilai === '') return 0;
    return (int) preg_replace('/\D/', '', $nilai);
}

function pengeluaran_fetch_by_date(mysqli $conn, string $tanggal): array
{
    $sql = "SELECT id, kategori, deskripsi, jumlah FROM pengeluaran WHERE tanggal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['kategori']] = [
            'id' => (int)$row['id'],
            'deskripsi' => $row['deskripsi'] ?? '',
            'jumlah' => (int)$row['jumlah'],
        ];
    }
    $stmt->close();
    return $data;
}

function pengeluaran_insert_batch(mysqli $conn, string $tanggal, array $jumlah_arr, array $deskripsi_arr): array
{
    $sql = "INSERT INTO pengeluaran (tanggal, kategori, deskripsi, jumlah) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $added = 0;
    $errors = [];
    foreach ($jumlah_arr as $kategori => $jumlah_raw) {
        $jumlah_val = rupiah_to_int($jumlah_raw);
        if ($jumlah_val > 0) {
            $deskripsi_val = $deskripsi_arr[$kategori] ?? '';
            $stmt->bind_param('sssi', $tanggal, $kategori, $deskripsi_val, $jumlah_val);
            if (!$stmt->execute()) {
                $errors[] = "Gagal menyimpan kategori $kategori";
            } else {
                $added++;
            }
        }
    }
    $stmt->close();
    return ['added' => $added, 'errors' => $errors];
}

function pengeluaran_replace_for_date(mysqli $conn, string $tanggal, array $jumlah_arr, array $deskripsi_arr): array
{
    $del = $conn->prepare('DELETE FROM pengeluaran WHERE tanggal = ?');
    $del->bind_param('s', $tanggal);
    $del->execute();
    $del->close();
    return pengeluaran_insert_batch($conn, $tanggal, $jumlah_arr, $deskripsi_arr);
}

function pengeluaran_delete_by_date(mysqli $conn, string $tanggal): bool
{
    $stmt = $conn->prepare('DELETE FROM pengeluaran WHERE tanggal = ?');
    $stmt->bind_param('s', $tanggal);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function pengeluaran_delete_by_id(mysqli $conn, int $id): bool
{
    $stmt = $conn->prepare('DELETE FROM pengeluaran WHERE id = ?');
    $stmt->bind_param('i', $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function pengeluaran_update_by_id(mysqli $conn, int $id, string $kategori, string $deskripsi, int $jumlah): bool
{
    $sql = "UPDATE pengeluaran SET kategori = ?, deskripsi = ?, jumlah = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $kategori, $deskripsi, $jumlah, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function pengeluaran_fetch_by_id(mysqli $conn, int $id): ?array
{
    $sql = "SELECT id, tanggal, kategori, deskripsi, jumlah FROM pengeluaran WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}


if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    session_start();
    include "../database/conn.php";

    if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../acount/login.php");
        exit();
    }

    $action = $_REQUEST['action'] ?? '';
    switch ($action) {
        case 'delete_by_date':
            $tanggal = $_GET['tanggal'] ?? '';
            if (!$tanggal) {
                header("Location: pengeluaran_list.php");
                exit;
            }
            if (pengeluaran_delete_by_date($conn, $tanggal)) {
                $_SESSION['success'] = "Semua data pengeluaran pada tanggal $tanggal berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus data pengeluaran.";
            }
            header("Location: pengeluaran_list.php");
            exit;
        case 'delete_by_id':
            $id = (int)($_GET['id'] ?? 0);
            if ($id && pengeluaran_delete_by_id($conn, $id)) {
                $_SESSION['success'] = "Pengeluaran berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus pengeluaran.";
            }
            header("Location: pengeluaran_list.php");
            exit;
        case 'insert_batch':
            $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
            $jumlah = $_POST['jumlah'] ?? [];
            $deskripsi = $_POST['deskripsi'] ?? [];
            $res = pengeluaran_insert_batch($conn, $tanggal, $jumlah, $deskripsi);
            $_SESSION['success'] = $res['added'] > 0 ? ($res['added'] . ' data pengeluaran berhasil ditambahkan.') : 'Tidak ada data valid untuk ditambahkan.';
            header("Location: pengeluaran_list.php");
            exit;
        case 'replace_for_date':
            $tanggal = $_POST['tanggal'] ?? '';
            if (!$tanggal) {
                header("Location: pengeluaran_list.php");
                exit;
            }
            $jumlah = $_POST['jumlah'] ?? [];
            $deskripsi = $_POST['deskripsi'] ?? [];
            $res = pengeluaran_replace_for_date($conn, $tanggal, $jumlah, $deskripsi);
            $_SESSION['success'] = $res['added'] > 0 ? ("$res[added] data pengeluaran pada tanggal $tanggal berhasil diperbarui.") : ("Semua data pengeluaran pada tanggal $tanggal telah dihapus.");
            header("Location: pengeluaran_list.php");
            exit;
        case 'update_by_id':
            $id = (int)($_POST['id'] ?? 0);
            $kategori = $_POST['kategori'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            $jumlah_raw = $_POST['jumlah'] ?? '';
            $jumlah_val = rupiah_to_int($jumlah_raw);
            if ($id && $kategori && $jumlah_val > 0) {
                if (pengeluaran_update_by_id($conn, $id, $kategori, $deskripsi, $jumlah_val)) {
                    $_SESSION['success'] = "Pengeluaran berhasil diperbarui.";
                } else {
                    $_SESSION['error'] = "Gagal memperbarui pengeluaran.";
                }
            } else {
                $_SESSION['error'] = "Data tidak lengkap atau tidak valid.";
            }
            header("Location: pengeluaran_list.php");
            exit;
        default:
            header("Location: pengeluaran_list.php");
            exit;
    }
}
