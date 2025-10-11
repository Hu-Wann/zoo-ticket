<?php
session_start();
include "../database/conn.php";

// Cek hanya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit;
}



// Tambah stok baru jika form disubmit
if (isset($_POST['tambah_stok'])) {
    $tanggal = $_POST['tanggal'];
    $jumlah_stok = $_POST['jumlah_stok'];

    // Cek apakah tanggal sudah ada
    $check = $conn->query("SELECT * FROM stok_tiket WHERE tanggal = '$tanggal'");

    if ($check->num_rows > 0) {
        // Update stok jika tanggal sudah ada
        $conn->query("UPDATE stok_tiket SET sisa_stok = '$jumlah_stok' WHERE tanggal = '$tanggal'");
        $pesan = "<div class='alert alert-success'>Stok untuk tanggal " . date('d-m-Y', strtotime($tanggal)) . " berhasil diupdate!</div>";
    } else {
        // Tambah stok baru jika tanggal belum ada
        $conn->query("INSERT INTO stok_tiket (tanggal, sisa_stok) VALUES ('$tanggal', '$jumlah_stok')");
        $pesan = "<div class='alert alert-success'>Stok untuk tanggal " . date('d-m-Y', strtotime($tanggal)) . " berhasil ditambahkan!</div>";
    }
}

// Ambil semua stok dari DB
$result = $conn->query("SELECT * FROM stok_tiket ORDER BY tanggal ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Tiket - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            padding: 20px;
            transition: all 0.3s;
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
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-paw me-2"></i> Zoo Admin
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="hewan.php">
                        <i class="fas fa-hippo"></i>
                        <span>Kelola Hewan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="laporan_tiket.php">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Kelola Tiket</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="stok.php">
                        <i class="fas fa-calendar-day"></i>
                        <span>Kelola Stok</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">
                        <i class="fas fa-users"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link" href="../pages/beranda.php">
                        <i class="fas fa-home"></i>
                        <span>Halaman Utama</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../acount/logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>

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

            <?php if (isset($pesan)) echo $pesan; ?>

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
                                    <input type="date"
                                        class="form-control"
                                        id="tanggal"
                                        name="tanggal"
                                        required
                                        min="<?= $min_date ?>"
                                        max="<?= $max_date ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah_stok" class="form-label">Jumlah Stok</label>
                                    <input type="number" class="form-control" id="jumlah_stok" name="jumlah_stok" min="0" required>
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

                                        echo "<tr>
        <td>{$no}</td>
        <td>" . date('d-m-Y', strtotime($row['tanggal'])) . "</td>
        <td>{$row['sisa_stok']}</td>
        <td>{$status}</td>
        <td>
          <form method='post' action='' style='display:inline;'>
            <input type='hidden' name='hapus_tanggal' value='{$row['tanggal']}'>
            <button type='submit' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus stok tanggal ini?')\">
              <i class='fas fa-trash'></i>
            </button>
          </form>
        </td>
      </tr>";
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