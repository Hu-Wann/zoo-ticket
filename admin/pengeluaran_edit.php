<?php
session_start();
include "../database/conn.php";
include "sys_pengeluaran.php";

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit();
}

$tanggal = $_GET['tanggal'] ?? '';
if (!$tanggal) {
    header("Location: pengeluaran_list.php");
    exit();
}


$pengeluaran_data = pengeluaran_fetch_by_date($conn, $tanggal);
$kategori_list = pengeluaran_kategori_list();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Pengeluaran - <?= htmlspecialchars($tanggal) ?></title>
    <?php include '../bootstrap.php'; ?>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .main-content {
            margin-left: var(--sidebar-width, 250px);
            padding: 20px 20px 40px 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }

    </style>
</head>

<body>
    <main class="flex-grow-1 p-4">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <div class="container">
                <h1 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Pengeluaran tanggal <?= date('d F Y', strtotime($tanggal)) ?></h1>
                <a href="pengeluaran_list.php" class="btn btn-outline-secondary mb-4"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Pengeluaran</a>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= $_SESSION['error'];
                                                                        unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success'];
                                                                unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="sys_pengeluaran.php" method="POST" novalidate>
                    <input type="hidden" name="action" value="replace_for_date" />
                    <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" />

                    <div class="card p-4 mb-4">
                        <h4>Isi Pengeluaran per Kategori</h4>
                        <?php foreach ($kategori_list as $kategori => $icon):
                            $deskripsi_val = $pengeluaran_data[$kategori]['deskripsi'] ?? '';
                            $jumlah_val = $pengeluaran_data[$kategori]['jumlah'] ?? 0;
                        ?>
                            <div class="mb-3 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-bold"><?= htmlspecialchars($kategori) ?></label>
                                <div class="col-sm-4">
                                    <input
                                        type="number"
                                        min="0"
                                        step="1"
                                        name="jumlah[<?= htmlspecialchars($kategori) ?>]"
                                        class="form-control"
                                        placeholder="Jumlah (Rp)"
                                        value="<?= $jumlah_val > 0 ? $jumlah_val : '' ?>"
                                        title="Masukkan angka tanpa huruf." />
                                </div>
                                <div class="col-sm-5">
                                    <input
                                        type="text"
                                        name="deskripsi[<?= htmlspecialchars($kategori) ?>]"
                                        class="form-control"
                                        placeholder="Deskripsi"
                                        value="<?= htmlspecialchars($deskripsi_val) ?>" />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>