<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../acount/login.php");
  exit;
}

if (isset($_POST['tambah'])) {
  $nama = $_POST['nama'];
  $habitat = $_POST['habitat'];
  $makanan = $_POST['makanan'];
  $deskripsi = $_POST['deskripsi'];
  $status = $_POST['status_konservasi'];

  $namaFile = $_FILES['gambar']['name'];
  $tmpName = $_FILES['gambar']['tmp_name'];
  $folder = "../picture/";

  if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
  }

  if ($namaFile != '') {
    $pathFile = $folder . time() . "_" . basename($namaFile);
    move_uploaded_file($tmpName, $pathFile);
  } else {
    $pathFile = '';
  }

  $stmt = $conn->prepare("INSERT INTO animals (nama, habitat, makanan, deskripsi, status_konservasi, gambar) VALUES (?,?,?,?,?,?)");
  $stmt->bind_param("ssssss", $nama, $habitat, $makanan, $deskripsi, $status, $pathFile);
  $stmt->execute();
  header("Location: hewan.php");
  exit;
}

if (isset($_POST['edit'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $habitat = $_POST['habitat'];
  $makanan = $_POST['makanan'];
  $deskripsi = $_POST['deskripsi'];
  $status = $_POST['status_konservasi'];

  $namaFile = $_FILES['gambar']['name'];
  $tmpName = $_FILES['gambar']['tmp_name'];
  $folder = "../picture/";

  if ($namaFile != '') {
    $pathFile = $folder . time() . "_" . basename($namaFile);
    move_uploaded_file($tmpName, $pathFile);

    $stmt = $conn->prepare("UPDATE animals SET nama=?, habitat=?, makanan=?, deskripsi=?, status_konservasi=?, gambar=? WHERE id=?");
    $stmt->bind_param("ssssssi", $nama, $habitat, $makanan, $deskripsi, $status, $pathFile, $id);
  } else {
    $stmt = $conn->prepare("UPDATE animals SET nama=?, habitat=?, makanan=?, deskripsi=?, status_konservasi=? WHERE id=?");
    $stmt->bind_param("sssssi", $nama, $habitat, $makanan, $deskripsi, $status, $id);
  }

  $stmt->execute();
  header("Location:hewan.php");
  exit;
}

if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $result = $conn->query("SELECT gambar FROM animals WHERE id=$id");
  $data = $result->fetch_assoc();
  if ($data && $data['gambar'] != '' && file_exists($data['gambar'])) {
    unlink($data['gambar']);
  }
  $conn->query("DELETE FROM animals WHERE id=$id");
  header("Location: hewan.php");
  exit;
}

$hewan = $conn->query("SELECT * FROM animals");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Hewan - Admin</title>
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
      padding: 20px 20px 40px 20px;
      transition: all 0.3s;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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
  </style>
</head>

<body>
  <div class="d-flex">
    <main class="flex-grow-1 p-4">
      <?php include 'sidebar.php'; ?>

      <div class="main-content">
        <div class="header">
          <h4 class="mb-0">Kelola Data Hewan</h4>
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

        <div class="card">
          <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Hewan</h5>
            <a href="add_animal.php" class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-1"></i> Tambah Hewan
            </a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead class="table-success">
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Habitat</th>
                    <th>Makanan</th>
                    <th>Status Konservasi</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  while ($row = $hewan->fetch_assoc()): ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= htmlspecialchars($row['nama']) ?></td>
                      <td><?= htmlspecialchars($row['habitat']) ?></td>
                      <td><?= htmlspecialchars($row['makanan']) ?></td>
                      <td><?= htmlspecialchars($row['status_konservasi']) ?></td>
                      <td>
                        <?php if ($row['gambar']): ?>
                          <img src="<?= (strpos($row['gambar'],'/')!==false || strpos($row['gambar'],'\\')!==false) ? $row['gambar'] : ('../picture/' . $row['gambar']) ?>" width="70" class="img-thumbnail">
                        <?php else: ?>
                          <span class="text-muted">Tidak ada gambar</span>
                         <?php endif; ?>
                      </td>
                      <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                          data-bs-target="#modalEdit<?= $row['id'] ?>">
                          <i class="fas fa-edit"></i> Edit
                        </button>
                        <a href="?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                          onclick="return confirm('Hapus hewan ini?')">
                          <i class="fas fa-trash"></i> Hapus
                        </a>
                      </td>
                    </tr>

                    <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <form method="post" enctype="multipart/form-data">
                            <div class="modal-header bg-warning">
                              <h5 class="modal-title">Edit Hewan</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="id" value="<?= $row['id'] ?>">
                              <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control"
                                  value="<?= htmlspecialchars($row['nama']) ?>" required>
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Habitat</label>
                                <input type="text" name="habitat" class="form-control"
                                  value="<?= htmlspecialchars($row['habitat']) ?>" required>
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Makanan</label>
                                <input type="text" name="makanan" class="form-control"
                                  value="<?= htmlspecialchars($row['makanan']) ?>" required>
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control"
                                  required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Status Konservasi</label>
                                <input type="text" name="status_konservasi" class="form-control"
                                  value="<?= htmlspecialchars($row['status_konservasi']) ?>" required>
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Gambar (jika ingin ganti)</label>
                                <input type="file" name="gambar" class="form-control">
                                <?php if ($row['gambar']): ?>
                                  <div class="mt-2">
                                    <small class="text-muted">Gambar saat ini:</small><br>
                                    <img src="<?= (strpos($row['gambar'],'/')!==false || strpos($row['gambar'],'\\')!==false) ? $row['gambar'] : ('../picture/' . $row['gambar']) ?>" width="100" class="img-thumbnail">
                                  </div>
                                <?php endif; ?>
                              </div>
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" name="edit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>