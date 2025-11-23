<?php
session_start();
include "../database/conn.php";

// Cek hanya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit;
}

// hapus stok jika tanggal sudah lewat
$today = date('Y-m-d');
$conn->query("DELETE FROM stok_tiket WHERE tanggal < '$today'");


if (isset($_POST['hapus_tanggal'])) {
    $hapus_tanggal = $_POST['hapus_tanggal'];
    $conn->query("DELETE FROM stok_tiket WHERE tanggal = '$hapus_tanggal'");
    header("Location: stok.php");
    exit;
}

// Tambah stok 
if (isset($_POST['tambah_stok'])) {
    $tanggal = $_POST['tanggal'];
    $jumlah_stok = intval($_POST['jumlah_stok']);
    // Cek apakah tanggal sudah ada
    $check = $conn->query("SELECT * FROM stok_tiket WHERE tanggal = '$tanggal'");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE stok_tiket SET sisa_stok = '$jumlah_stok' WHERE tanggal = '$tanggal'");
        $pesan = "<div class='alert alert-success'>
Stok untuk tanggal " . date('d-m-Y', strtotime($tanggal)) .
            " berhasil ditambahkan sebanyak <strong>$jumlah_stok</strong>.
</div>";

    } else {
        $conn->query("INSERT INTO stok_tiket (tanggal, sisa_stok) VALUES ('$tanggal', '$jumlah_stok')");
        $pesan = "<div class='alert alert-success'>
Stok untuk tanggal " . date('d-m-Y', strtotime($tanggal)) .
            " berhasil ditambahkan sebanyak <strong>$jumlah_stok</strong>.
</div>";

    }
}

// Edit stok via modal per baris
if (isset($_POST['edit_stok'])) {
    $tanggal = $_POST['edit_tanggal'];
    $jumlah_stok = intval($_POST['jumlah_stok']);

    if (strtotime($tanggal) < strtotime(date('Y-m-d'))) {
        $pesan = "<div class='alert alert-danger'>Tidak dapat mengedit stok untuk tanggal yang sudah lewat.</div>";
    } else {
        $check = $conn->query("SELECT * FROM stok_tiket WHERE tanggal = '$tanggal'");
        if ($check && $check->num_rows > 0) {
            $conn->query("UPDATE stok_tiket SET sisa_stok = '$jumlah_stok' WHERE tanggal = '$tanggal'");
            $pesan = "<div class='alert alert-success'>Stok untuk tanggal " . date('d-m-Y', strtotime($tanggal)) . " berhasil diupdate menjadi <strong>$jumlah_stok</strong>.</div>";
        } else {
            $conn->query("INSERT INTO stok_tiket (tanggal, sisa_stok) VALUES ('$tanggal', '$jumlah_stok')");
            $pesan = "<div class='alert alert-success'>Stok untuk tanggal " . date('d-m-Y', strtotime($tanggal)) . " belum ada, dibuat baru sebanyak <strong>$jumlah_stok</strong>.</div>";
        }
    }
}

// Ambil stok dari DB
$result = $conn->query("SELECT * FROM stok_tiket ORDER BY tanggal ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Tiket - Admin</title>
    <?php include '../bootstrap.php'; ?>
    <style>
        :root {
            --primary-color: #198754;
            --secondary-color: #0d6efd;
            --accent-color: #ffc107;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--primary-color);
            color: white;
            padding: 20px 0;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar .logo {
            padding: 15px 25px;
            font-size: 22px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 25px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--accent-color);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 25px;
            width: calc(100% - var(--sidebar-width));
            transition: all 0.3s;
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .logo {
                padding: 15px;
                font-size: 18px;
                text-align: center;
            }

            .sidebar .nav-link span {
                display: none;
            }

            .sidebar .nav-link {
                padding: 12px;
                justify-content: center;
            }

            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h4 class="mb-0">Kelola Stok Tiket</h4>
                <div class="user-info">
                    <div class="avatar">
                        <?= substr($_SESSION['nama'], 0, 1) ?>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold"><?= $_SESSION['nama'] ?></p>
                        <small class="text-muted">Administrator</small>
                    </div>
                </div>
            </div>

            <?php if (isset($pesan))
                echo $pesan; ?>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i> Tambah/Update Stok Tiket</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <?php
                                // Tanggal minimal: hari ini
                                $min_date = date('Y-m-d');
                                // Tanggal maksimal: 1 tahun ke depan
                                $max_date = date('Y-m-d', strtotime('+1 year'));
                                ?>
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required
                                        min="<?= $min_date ?>" max="<?= $max_date ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah_stok" class="form-label">Jumlah Stok</label>
                                    <input type="number" class="form-control" id="jumlah_stok" name="jumlah_stok"
                                        min="0" required>
                                </div>
                                <button type="submit" name="tambah_stok" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Simpan Stok
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Stok</h5>
                        </div>
                        <div class="card-body">
                            <p>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Stok tiket diatur per tanggal kunjungan
                            </p>
                            <p>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Stok akan berkurang otomatis saat ada booking baru
                            </p>
                            <p>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Anda dapat menambah atau mengupdate stok untuk tanggal tertentu
                            </p>
                            <p>
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                Pastikan stok selalu tersedia untuk menghindari kekecewaan pengunjung
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Stok Tiket Per Tanggal</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-success">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Sisa Stok</th>
                                    <th>Status</th>
                                    <th>aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    $no = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        // Tentukan status stok
                                        if ($row['sisa_stok'] > 100) {
                                            $status = '<span class="badge bg-success">Tersedia</span>';
                                        } elseif ($row['sisa_stok'] > 0) {
                                            $status = '<span class="badge bg-warning">Terbatas</span>';
                                        } else {
                                            $status = '<span class="badge bg-danger">Habis</span>';
                                        }

                                        $modalId = 'editModal_' . $no;
                                        $tglDisplay = date('d-m-Y', strtotime($row['tanggal']));

                                        echo "<tr>
        <td>{$no}</td>
        <td>{$tglDisplay}</td>
        <td>{$row['sisa_stok']}</td>
        <td>{$status}</td>
        <td>
          <button type='button' class='btn btn-sm btn-warning me-1' data-bs-toggle='modal' data-bs-target='#{$modalId}'>
            <i class='fas fa-edit'></i>
          </button>
          <form method='post' action='' style='display:inline;'>
            <input type='hidden' name='hapus_tanggal' value='{$row['tanggal']}'>
            <button type='submit' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus stok tanggal ini?')\">
              <i class='fas fa-trash'></i>
            </button>
          </form>

          <div class='modal fade' id='{$modalId}' tabindex='-1' aria-labelledby='{$modalId}Label' aria-hidden='true'>
            <div class='modal-dialog'>
              <div class='modal-content'>
                <div class='modal-header'>
                  <h5 class='modal-title' id='{$modalId}Label'>Edit Stok Tanggal {$tglDisplay}</h5>
                  <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <form method='post' action=''>
                  <div class='modal-body'>
                    <input type='hidden' name='edit_tanggal' value='{$row['tanggal']}'>
                    <div class='mb-3'>
                      <label class='form-label'>Tanggal</label>
                      <input type='text' class='form-control' value='{$tglDisplay}' readonly>
                    </div>
                    <div class='mb-3'>
                      <label for='jumlah_stok_{$no}' class='form-label'>Jumlah Stok</label>
                      <input type='number' class='form-control' id='jumlah_stok_{$no}' name='jumlah_stok' min='0' value='{$row['sisa_stok']}' required>
                    </div>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                    <button type='submit' name='edit_stok' class='btn btn-primary'>Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </td>
      </tr>";
                                        $no++;
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center text-muted'>Belum ada data stok</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>