<?php
session_start();
include "../database/conn.php";

// cek login & role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
<<<<<<< HEAD
<<<<<<< HEAD
  header("Location: ../pages/beranda.php");
  exit;
=======
    header("Location: ../pages/index.php");
    exit;
>>>>>>> fe5caa6787f979ef370fab3cd40cb25a7f2ce130
}
=======

  header("Location: ../pages/index.php");
  exit;
}     
>>>>>>> f7f9847ab7cc248c7216a26b616e873095d4cb17

// Hapus pengguna jika ada request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $id = $_GET['delete'];
  // Tidak menghapus admin yang sedang login
  if ($id != $_SESSION['user_id']) {
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: users.php?status=deleted");
    exit;
  } else {
    header("Location: users.php?status=error");
    exit;
  }
}

// Ambil data pengguna
$query = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Pengguna - Admin</title>
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

    .content-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 25px;
      margin-bottom: 25px;
    }

    .table-container {
      border-radius: 10px;
      overflow: hidden;
    }

    .table {
      margin-bottom: 0;
    }

    .table thead {
      background-color: #f8f9fa;
    }

    .table thead th {
      border-bottom: none;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.85rem;
      color: #6c757d;
      padding: 15px;
    }

    .table tbody td {
      padding: 15px;
      vertical-align: middle;
    }

    .badge {
      padding: 6px 12px;
      font-weight: 500;
      border-radius: 50px;
    }

    .btn-action {
      border-radius: 50px;
      padding: 6px 15px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }

    .alert {
      border-radius: 10px;
      border-left: 4px solid;
      padding: 15px 20px;
    }

    .alert-success {
      border-left-color: #28a745;
    }

    .alert-danger {
      border-left-color: #dc3545;
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
          <a class="nav-link active" href="users.php">
            <i class="fas fa-users"></i>
            <span>Kelola Pengguna</span>
          </a>
        </li>
        <li class="nav-item mt-4">
          <a class="nav-link" href="../pages/index.php">
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
        <h4 class="mb-0">Kelola Pengguna</h4>
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

      <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle me-2"></i> Pengguna berhasil dihapus!
        </div>
      <?php elseif (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle me-2"></i> Tidak dapat menghapus akun admin yang sedang aktif!
        </div>
      <?php endif; ?>

      <div class="content-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="mb-0">
            <i class="fas fa-users me-2 text-primary"></i> Daftar Pengguna
          </h5>
          <div>
            <a href="dashboard.php" class="btn btn-outline-secondary btn-action">
              <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
          </div>
        </div>

        <div class="table-container">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result->num_rows > 0):
                $no = 1; // ✅ Nomor urut mulai dari 1
                while ($user = $result->fetch_assoc()):
              ?>
                  <tr>
                    <td><?= $no++ ?></td> <!-- ✅ Tampilkan nomor urut -->
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="avatar me-2" style="width: 30px; height: 30px; font-size: 12px;">
                          <?= substr($user['nama'], 0, 1) ?>
                        </div>
                        <?= htmlspecialchars($user['nama']) ?>
                      </div>
                    </td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                      <span class="badge <?= $user['role'] == 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                        <i class="fas <?= $user['role'] == 'admin' ? 'fa-user-shield' : 'fa-user' ?> me-1"></i>
                        <?= $user['role'] ?>
                      </span>
                    </td>
                    <td>
                      <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <a href="users.php?delete=<?= $user['id'] ?>" class="btn btn-danger btn-action"
                          onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                          <i class="fas fa-trash-alt me-1"></i> Hapus
                        </a>
                      <?php else: ?>
                        <span class="badge bg-secondary">
                          <i class="fas fa-user-check me-1"></i> Akun Aktif
                        </span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endwhile;
              else: ?>
                <tr>
                  <td colspan="5" class="text-center py-4">
                    <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                    <p>Tidak ada data pengguna</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>