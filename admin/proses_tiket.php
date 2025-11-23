<?php
session_start();
include "../database/conn.php";

// aktifkan error reporting untuk debugging (hapus atau matikan di production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

// Helper: redirect ke halaman laporan_tiket.php dengan pesan
function go($msg = '', $type = 'ok') {
    $loc = "laporan_tiket.php";
    if ($msg !== '') $loc .= '?msg=' . urlencode($msg) . '&type=' . $type;
    header("Location: $loc");
    exit;
}

// Dapatkan aksi dari POST (prioritas) atau GET
$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? null;

// Fungsi bantu: restore stok saat delete / decline jika perlu
function restore_stok_if_needed($conn, $booking_id) {
    // ambil data booking (jumlah tiket total dan tanggal)
    $q = $conn->prepare("SELECT jumlah_dewasa, jumlah_remaja, jumlah_anak, tanggal_kunjungan, status FROM booking WHERE id = ?");
    $q->bind_param("i", $booking_id);
    $q->execute();
    $res = $q->get_result();
    if ($res->num_rows === 0) return false;
    $r = $res->fetch_assoc();
    $total = intval($r['jumlah_dewasa']) + intval($r['jumlah_remaja']) + intval($r['jumlah_anak']);
    if ($total <= 0) return false;

    // Jika booking masih dikurangkan stok sebelumnya (biasanya status 'dibooking' atau 'acc'),
    // kita tambahkan kembali stok ketika tiket dihapus atau ditolak (sesuai logika kamu).
    // Pastikan ada baris stok_tiket untuk tanggal itu.
    $tgl = $r['tanggal_kunjungan'];
    $stmt = $conn->prepare("UPDATE stok_tiket SET sisa_stok = sisa_stok + ? WHERE tanggal = ?");
    $stmt->bind_param("is", $total, $tgl);
    $stmt->execute();
    // jika tidak ada baris, kita tidak buat baru secara otomatis saat restore (karena ini restore)
    return true;
}

// PROSES AKSI
try {
    if ($aksi === null) {
        go("No action specified", 'err');
    }

    // 1) single actions via GET links (proses cepat)
    if (isset($_GET['id']) && ($aksi === 'acc' || $aksi === 'dec' || $aksi === 'paid')) {
        $id = intval($_GET['id']);

        if ($aksi === 'acc') {
            $stmt = $conn->prepare("UPDATE booking SET status = 'acc' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            go("Tiket #$id disetujui.");
        } else if ($aksi === 'paid') {
            // Tandai tiket sebagai dibayar (tidak mengubah stok)
            $stmt = $conn->prepare("UPDATE booking SET status = 'dibayar' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            go("Tiket #$id ditandai sebagai dibayar.");
        } else { // dec
            // saat ditolak, kembalikan stok
            $conn->begin_transaction();
            try {
                restore_stok_if_needed($conn, $id);
                $stmt = $conn->prepare("UPDATE booking SET status = 'dec' WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $conn->commit();
                go("Tiket #$id ditolak dan stok dikembalikan.");
            } catch (Exception $e) {
                $conn->rollback();
                go("Gagal menolak tiket: " . $e->getMessage(), 'err');
            }
        }
    }

    // 2) POST actions (delete single, mass actions)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // tombol massal: acc_all / dec_all (legacy)
        if ($aksi === 'acc_all') {
            // set semua yang masih 'dibooking' => 'acc'
            $conn->query("UPDATE booking SET status = 'acc' WHERE status = 'dibooking'");
            go("Semua tiket dibooking telah disetujui.");
        }

        if ($aksi === 'dec_all') {
            // tolak semua yang masih 'dibooking' dan restore stok masing-masing
            $conn->begin_transaction();
            try {
                $res = $conn->query("SELECT id FROM booking WHERE status = 'dibooking'");
                while ($r = $res->fetch_assoc()) {
                    $bid = intval($r['id']);
                    restore_stok_if_needed($conn, $bid);
                    $stmt = $conn->prepare("UPDATE booking SET status = 'dec' WHERE id = ?");
                    $stmt->bind_param("i", $bid);
                    $stmt->execute();
                }
                $conn->commit();
                go("Semua tiket dibooking telah ditolak dan stok dikembalikan.");
            } catch (Exception $e) {
                $conn->rollback();
                go("Gagal menolak semua: " . $e->getMessage(), 'err');
            }
        }
        
        // Tombol aksi massal baru: acc_selected / dec_selected / delete_selected
        if (($aksi === 'acc_selected' || $aksi === 'dec_selected' || $aksi === 'delete_selected' || $aksi === 'paid_selected') && isset($_POST['selected_ids'])) {
            $selected_ids = $_POST['selected_ids'];
            
            if (empty($selected_ids)) {
                go("Tidak ada tiket yang dipilih", 'err');
            }
            
            $count = count($selected_ids);
            $conn->begin_transaction();
            
            try {
                if ($aksi === 'acc_selected') {
                    $select_status = $conn->prepare("SELECT status FROM booking WHERE id = ?");
                    $update_acc = $conn->prepare("UPDATE booking SET status = 'acc' WHERE id = ?");
                    $updated = 0;
                    foreach ($selected_ids as $id) {
                        $select_status->bind_param("i", $id);
                        $select_status->execute();
                        $res = $select_status->get_result();
                        $row = $res->fetch_assoc();
                        $st = strtolower(trim($row['status'] ?? ''));
                        if (in_array($st, ['kadaluwarsa','dibatalkan'])) { continue; }
                        $update_acc->bind_param("i", $id);
                        $update_acc->execute();
                        $updated++;
                    }
                    $conn->commit();
                    go(($updated) . " tiket berhasil disetujui.");
                    
                } else if ($aksi === 'dec_selected') {
                    // Tolak tiket yang dipilih
                    $stmt = $conn->prepare("UPDATE booking SET status = 'dec' WHERE id = ?");
                    
                    foreach ($selected_ids as $id) {
                        restore_stok_if_needed($conn, $id);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                    }
                    
                    $conn->commit();
                    go("$count tiket berhasil ditolak dan stok dikembalikan.");

                } else if ($aksi === 'paid_selected') {
                    $select_status = $conn->prepare("SELECT status FROM booking WHERE id = ?");
                    $update_paid = $conn->prepare("UPDATE booking SET status = 'dibayar' WHERE id = ?");
                    $updated = 0;
                    foreach ($selected_ids as $id) {
                        $select_status->bind_param("i", $id);
                        $select_status->execute();
                        $res = $select_status->get_result();
                        $row = $res->fetch_assoc();
                        $st = strtolower(trim($row['status'] ?? ''));
                        if (in_array($st, ['kadaluwarsa','dibatalkan'])) { continue; }
                        $update_paid->bind_param("i", $id);
                        $update_paid->execute();
                        $updated++;
                    }
                    $conn->commit();
                    go(($updated) . " tiket berhasil ditandai sebagai dibayar.");
                    
                } else if ($aksi === 'delete_selected') {
                    // Hapus tiket yang dipilih (tanpa mengembalikan stok)
                    $stmt = $conn->prepare("DELETE FROM booking WHERE id = ?");
                    
                    foreach ($selected_ids as $id) {
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                    }
                    
                    $conn->commit();
                    go("$count tiket berhasil dihapus.");
                }
                
            } catch (Exception $e) {
                $conn->rollback();
                go("Gagal memproses tiket: " . $e->getMessage(), 'err');
            }
        }

        // delete single (via form)
        if ($aksi === 'delete' && isset($_POST['id'])) {
            $id = intval($_POST['id']);

            // Ambil dulu status & jumlah tiket (informasi, tetapi tidak akan restore stok saat delete)
            $stmt = $conn->prepare("SELECT status, jumlah_dewasa, jumlah_remaja, jumlah_anak, tanggal_kunjungan FROM booking WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows === 0) go("Booking tidak ditemukan", 'err');

            $conn->begin_transaction();
            try {
                // Hapus booking tanpa mengembalikan stok
                $del = $conn->prepare("DELETE FROM booking WHERE id = ?");
                $del->bind_param("i", $id);
                $del->execute();
                $conn->commit();
                go("Booking #$id berhasil dihapus.");
            } catch (Exception $e) {
                $conn->rollback();
                go("Gagal menghapus booking: " . $e->getMessage(), 'err');
            }
        }

        // jika ada aksi lain via POST (mis: accepted/declined submit buttons)
        if (isset($_POST['booking_id']) && ($aksi === 'accepted' || $aksi === 'declined')) {
            $id = intval($_POST['booking_id']);
            if ($aksi === 'accepted') {
                $stmt = $conn->prepare("UPDATE booking SET status = 'acc' WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                go("Booking #$id disetujui.");
            } else {
                $conn->begin_transaction();
                try {
                    restore_stok_if_needed($conn, $id);
                    $stmt = $conn->prepare("UPDATE booking SET status = 'dec' WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $conn->commit();
                    go("Booking #$id ditolak dan stok dikembalikan.");
                } catch (Exception $e) {
                    $conn->rollback();
                    go("Gagal memproses penolakan: " . $e->getMessage(), 'err');
                }
            }
        }
    }

    // default fallback
    go("Aksi tidak dikenali", 'err');

} catch (Exception $e) {
    // tangkap semua error DB
    go("Exception: " . $e->getMessage(), 'err');
}
