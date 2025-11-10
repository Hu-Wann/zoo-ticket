<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit();
}

if (!isset($_GET['tanggal'])) {
    header("Location: pengeluaran_list.php");
    exit();
}

$tanggal = $_GET['tanggal'];

// Ambil data pengeluaran yang ada untuk tanggal ini
$query_existing = "SELECT kategori, deskripsi, jumlah FROM pengeluaran WHERE tanggal = '$tanggal'";
$result_existing = mysqli_query($conn, $query_existing);

$existing_data = [];
while ($row = mysqli_fetch_assoc($result_existing)) {
    $existing_data[$row['kategori']] = [
        'jumlah' => $row['jumlah'],
        'deskripsi' => $row['deskripsi']
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah_arr = $_POST['jumlah'];
    $deskripsi_arr = $_POST['deskripsi'];

    // 1. Hapus semua data lama untuk tanggal ini
    mysqli_query($conn, "DELETE FROM pengeluaran WHERE tanggal = '$tanggal'");

    // 2. Masukkan data baru seperti proses tambah
    $updated_count = 0;
    foreach ($jumlah_arr as $kategori => $jumlah) {
        // Konversi format rupiah ke integer
        $jumlah_numeric = (int) filter_var($jumlah, FILTER_SANITIZE_NUMBER_INT);

        if (!empty($jumlah) && $jumlah_numeric > 0) {
            $jumlah_val = $jumlah_numeric;
            $kategori_val = mysqli_real_escape_string($conn, $kategori);
            $deskripsi_val = mysqli_real_escape_string($conn, $deskripsi_arr[$kategori]);

            $query = "INSERT INTO pengeluaran (tanggal, kategori, deskripsi, jumlah)
                      VALUES ('$tanggal', '$kategori_val', '$deskripsi_val', '$jumlah_val')";
            
            if (mysqli_query($conn, $query)) {
                $updated_count++;
            }
        }
    }

    if ($updated_count > 0) {
        $_SESSION['success'] = "$updated_count data pengeluaran pada tanggal $tanggal berhasil diperbarui.";
    } else {
        $_SESSION['success'] = "Semua data pengeluaran pada tanggal $tanggal telah dihapus.";
    }

    header("Location: pengeluaran_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengeluaran Harian - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fff8; }
        .card { border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .section-title { color: #198754; font-weight: 700; }
        .form-label { font-weight: 600; }
        .btn-group-custom { gap: 10px; }
        .kategori-group {
            border: 1px solid #dee2e6;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            background-color: #fff;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="text-center py-3 bg-white shadow-sm mb-4">
    <h1 class="text-success"><i class="bi bi-pencil-square"></i> Edit Pengeluaran Harian</h1>
    <p class="lead">Tanggal: <strong><?= date("d F Y", strtotime($tanggal)) ?></strong></p>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card p-4">
                <h4 class="section-title mb-4"><i class="bi bi-receipt"></i> Form Edit Pengeluaran</h4>

                <form method="POST">
                    <?php 
                    $kategori_list = [
                        "Gaji Karyawan" => "bi-person-badge", 
                        "Pangan" => "bi-egg-fried", 
                        "Perawatan Hewan" => "bi-heart-pulse", 
                        "Pemeliharaan Kandang" => "bi-house-gear"
                    ];
                    foreach ($kategori_list as $kategori => $icon) {
                        $jumlah_val = isset($existing_data[$kategori]) ? $existing_data[$kategori]['jumlah'] : '';
                        $deskripsi_val = isset($existing_data[$kategori]) ? $existing_data[$kategori]['deskripsi'] : '';
                    ?>
                    <div class="kategori-group">
                        <h5 class="section-title"><i class="bi <?= $icon ?>"></i> <?= $kategori ?></h5>
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label">Jumlah (Rp)</label>
                                <input type="text" name="jumlah[<?= $kategori ?>]" class="form-control input-rupiah" placeholder="0" value="<?= number_format($jumlah_val ?: 0, 0, ',', '.') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Deskripsi</label>
                                <input type="text" name="deskripsi[<?= $kategori ?>]" class="form-control" placeholder="Deskripsi (opsional)" value="<?= htmlspecialchars($deskripsi_val) ?>">
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="d-flex justify-content-end btn-group-custom mt-4">
                        <a href="pengeluaran_list.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
                        <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.input-rupiah');
        inputs.forEach(input => {
            // Format on load
            if(input.value) {
                input.value = formatRupiah(input.value);
            }
            // Format on key up
            input.addEventListener('keyup', function(e) {
                this.value = formatRupiah(this.value);
            });
        });

        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }
    });
</script>
</body>
</html>