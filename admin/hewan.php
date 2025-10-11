<?php
session_start();
include "../database/conn.php";

// Cek login & role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acount/login.php");
    exit;
}

// ==== CREATE ====
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $habitat = $_POST['habitat'];
    $makanan = $_POST['makanan'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status_konservasi'];

    // Upload gambar
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

// ==== UPDATE ====
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $habitat = $_POST['habitat'];
    $makanan = $_POST['makanan'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status_konservasi'];

    // Upload gambar jika ada
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

// ==== DELETE ====
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // hapus juga file gambarnya
    $result = $conn->query("SELECT gambar FROM animals WHERE id=$id");
    $data = $result->fetch_assoc();
    if ($data && $data['gambar'] != '' && file_exists($data['gambar'])) {
        unlink($data['gambar']);
    }
    $conn->query("DELETE FROM animals WHERE id=$id");
    header("Location: hewan.php");
    exit;
}

// ==== READ ====
$hewan = $conn->query("SELECT * FROM animals");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Data Hewan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
    <div class="d-flex">
      <a href="../acount/logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">


  <table class="table table-bordered table-striped">
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
      <?php $no=1; while($row=$hewan->fetch_assoc()): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['habitat']) ?></td>
        <td><?= htmlspecialchars($row['makanan']) ?></td>
        <td><?= htmlspecialchars($row['status_konservasi']) ?></td>
        <td>
          <?php if($row['gambar']): ?>
            <img src="../picture/<?= $row['gambar'] ?>" width="70">
          <?php endif; ?>
        </td>
        <td>
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">Edit</button>
          <a href="?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus hewan ini?')">Hapus</a>
        </td>
      </tr>

      <!-- Modal Edit -->
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
                  <label>Nama</label>
                  <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                </div>
                <div class="mb-3">
                  <label>Habitat</label>
                  <input type="text" name="habitat" class="form-control" value="<?= htmlspecialchars($row['habitat']) ?>" required>
                </div>
                <div class="mb-3">
                  <label>Makanan</label>
                  <input type="text" name="makanan" class="form-control" value="<?= htmlspecialchars($row['makanan']) ?>" required>
                </div>
                <div class="mb-3">
                  <label>Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                </div>
                <div class="mb-3">
                  <label>Status Konservasi</label>
                  <input type="text" name="status_konservasi" class="form-control" value="<?= htmlspecialchars($row['status_konservasi']) ?>" required>
                </div>
                <div class="mb-3">
                  <label>Gambar (jika ingin ganti)</label>
                  <input type="file" name="gambar" class="form-control">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="edit" class="btn btn-warning">Simpan Perubahan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </tbody>
  </table>
  <div class="mt-4 text-center">
    <a href="dashboard.php" class="btn btn-outline-success"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    <a href="add_animal.php" class="btn btn-success ms-2"><i class="bi bi-ticket-perforated"></i> tambah Hewan</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
