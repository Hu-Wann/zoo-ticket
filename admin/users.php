<?php
session_start();
include "../database/conn.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {

  header("Location: ../pages/beranda.php");
  exit;
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $id = $_GET['delete'];
 
  if ($id != $_SESSION['user_id']) {
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: users.php?status=deleted");
    exit;
  } else {
    header("Location: users.php?status=error");
    exit;
  }
}


$editUser = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
  $editId = (int) $_GET['edit'];
  $res = $conn->query("SELECT id, nama, email, role FROM users WHERE id = $editId");
  if ($res && $res->num_rows === 1) {
    $editUser = $res->fetch_assoc();
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
  $id = (int) $_POST['id'];
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $role = mysqli_real_escape_string($conn, $_POST['role']);
  $password = $_POST['password'] ?? '';

  if ($password !== '') {
    $hash = md5($password);
    $conn->query("UPDATE users SET nama='$nama', email='$email', role='$role', password='$hash' WHERE id=$id");
  } else {
    $conn->query("UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id=$id");
  }
  header("Location: users.php?status=updated");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $role = mysqli_real_escape_string($conn, $_POST['role']);
  $password = $_POST['password'] ?? '';

  if ($password === '') {
    header("Location: users.php?status=error_password");
    exit;
  }

  $hash = md5($password);
  $conn->query("INSERT INTO users (nama, email, role, password) VALUES ('$nama', '$email', '$role', '$hash')");
  header("Location: users.php?status=created");
  exit;
}


$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$where = '';
if ($q !== '') {
  $qEsc = mysqli_real_escape_string($conn, $q);
  $like = "%$qEsc%";
  $where = "WHERE nama LIKE '$like' OR email LIKE '$like' OR role LIKE '$like'";
}
$query = "SELECT * FROM users $where ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Pengguna - Admin</title>
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
  </style>
</head>

<body>
  <div class="d-flex">
    <main class="flex-grow-1 p-4">
      <?php include 'sidebar.php'; ?>

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
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'created'): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i> Pengguna baru berhasil dibuat!
          </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'error_password'): ?>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Password wajib diisi untuk akun baru!
          </div>
        <?php endif; ?>

        <div class="content-card">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
              <i class="fas fa-users me-2 text-primary"></i> Daftar Pengguna
            </h5>
            <div class="d-flex align-items-center gap-2">
              <form method="GET" action="users.php" class="d-flex gap-2">
                <input type="text" name="q" class="form-control" placeholder="Cari akun"
                  value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit" class="btn btn-success btn-action"><i class="fas fa-search"></i> Cari</button>
                <a href="users.php" class="btn btn-outline-secondary btn-action">Reset</a>
              </form>
            </div>
          </div>

          <div class="card p-4 mb-4 shadow-sm border-0">
            <h5 class="text-primary mb-3">
              <?php if ($editUser): ?>
                <i class="fas fa-user-edit me-2"></i> Edit Pengguna
              <?php else: ?>
                <i class="fas fa-user-plus me-2"></i> Tambah Pengguna
              <?php endif; ?>
            </h5>
            <form method="POST" action="users.php">
              <?php if ($editUser): ?>
                <input type="hidden" name="id" value="<?= (int) $editUser['id'] ?>">
              <?php endif; ?>
              <div class="row g-3 align-items-center">
                <div class="col-md-3">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-user text-primary"></i></span>
                    <input type="text" name="nama" class="form-control border-0 bg-light" placeholder="Nama Lengkap"
                      value="<?= $editUser ? htmlspecialchars($editUser['nama']) : '' ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-primary"></i></span>
                    <input type="email" name="email" class="form-control border-0 bg-light" placeholder="Email"
                      value="<?= $editUser ? htmlspecialchars($editUser['email']) : '' ?>" required>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-user-tag text-primary"></i></span>
                    <select name="role" class="form-select border-0 bg-light">
                      <option value="user" <?= $editUser && $editUser['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                      <option value="admin" <?= $editUser && $editUser['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-primary"></i></span>
                    <input type="password" name="password" class="form-control border-0 bg-light"
                      placeholder="<?= $editUser ? 'Kata sandi baru (opsional)' : 'Password' ?>" <?= $editUser ? '' : 'required' ?> minlength="6">
                  </div>
                </div>
                <div class="col-md-1">
                  <?php if ($editUser): ?>
                    <button type="submit" name="update_user" class="btn btn-primary w-100 btn-action shadow-sm"
                      style="height: 100%; border-radius: 8px;">
                      <i class="fas fa-save me-1"></i>
                    </button>
                  <?php else: ?>
                    <button type="submit" name="create_user" class="btn btn-success w-100 btn-action shadow-sm"
                      style="height: 100%; border-radius: 8px;">
                      <i class="fas fa-plus me-1"></i>
                    </button>
                  <?php endif; ?>
                </div>
              </div>
            </form>
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
                  $no = 1; 
                  while ($user = $result->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="avatar me-2" style="width: 30px; height: 30px; font-size: 12px;">
                            <?= substr($user['nama'], 0, 1) ?>
                          </div>
                          <?= htmlspecialchars($user['nama'] ?? '') ?>
                        </div>
                      </td>
                      <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                      <td>
                        <span class="badge <?= $user['role'] == 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                          <i class="fas <?= $user['role'] == 'admin' ? 'fa-user-shield' : 'fa-user' ?> me-1"></i>
                          <?= $user['role'] ?>
                        </span>
                      </td>
                      <td>
                        <a href="users.php?edit=<?= $user['id'] ?>" class="btn btn-primary btn-action me-2">
                          <i class="fas fa-edit me-1"></i> Edit
                        </a>
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