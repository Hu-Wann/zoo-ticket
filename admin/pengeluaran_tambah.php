<?php
session_start();
include "../database/conn.php";
include "sys_pengeluaran.php";

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $res = pengeluaran_insert_batch($conn, $tanggal, $_POST['jumlah'], $_POST['deskripsi']);
    if ($res['added'] > 0) {
        $_SESSION['success'] = $res['added'] . " data pengeluaran berhasil ditambahkan.";
        header("Location: pengeluaran_list.php");
        exit;
    } else {
        $_SESSION['error'] = "Tidak ada data pengeluaran yang diisi atau jumlah adalah 0.";
        header("Location: pengeluaran_tambah.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Input Pengeluaran Harian - Admin</title>
    <?php include '../bootstrap.php'; ?>

    <style>
        body {
            background-color: #f8fff8;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #198754;
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-group-custom {
            gap: 10px;
        }

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
        <h1 class="text-success"><i class="bi bi-plus-circle"></i> Input Pengeluaran Harian</h1>
        <p class="lead">Manajemen Pengeluaran Operasional - Kebun Binatang Indah</p>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card p-4">
                    <h4 class="section-title mb-4"><i class="bi bi-receipt"></i> Form Input Pengeluaran</h4>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fs-5"><i class="bi bi-calendar-date"></i> Tanggal
                                Pengeluaran</label>
                            <input type="date" name="tanggal" class="form-control form-control-lg" required
                                value="<?= date('Y-m-d') ?>">
                        </div>

                        <hr class="my-4">

                        <?php
                        $kategori_list = pengeluaran_kategori_list();
                        foreach ($kategori_list as $kategori => $icon) {
                            ?>
                            <div class="kategori-group">
                                <h5 class="section-title"><i class="bi <?= $icon ?>"></i> <?= $kategori ?></h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label">Jumlah (Rp)</label>
                                        <input type="text" name="jumlah[<?= $kategori ?>]" class="form-control input-rupiah"
                                            placeholder="0" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Deskripsi</label>
                                        <input type="text" name="deskripsi[<?= $kategori ?>]" class="form-control"
                                            placeholder="Deskripsi (opsional)">
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="d-flex justify-content-end btn-group-custom mt-4">
                            <a href="pengeluaran_list.php" class="btn btn-outline-secondary"><i
                                    class="bi bi-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-save"></i> Simpan
                                Semua</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.input-rupiah');
            inputs.forEach(input => {
                input.addEventListener('keyup', function (e) {
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