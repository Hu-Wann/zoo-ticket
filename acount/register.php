<?php
include "../database/conn.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama     = $_POST['nama'];
  $email    = $_POST['email'];
  $password = $_POST['password'];
  $confirm  = $_POST['confirm'];

  $role = 'user';

  if ($password !== $confirm) {
    $pesan = "<div class='alert alert-danger text-center'>❌ Password tidak cocok!</div>";
  } else {
    // Hash password dengan md5 (agar sesuai proses login kamu)
    $password_md5 = md5($password);

    $sql = "INSERT INTO users (nama, email, password, role) 
            VALUES ('$nama', '$email', '$password_md5', '$role')";

    if ($conn->query($sql) === TRUE) {
      // Setelah registrasi, arahkan ke beranda user
      header("Location: ../pages/index.php");
      exit;
    } else {
      $pesan = "<div class='alert alert-danger text-center'>❌ Error: " . $conn->error . "</div>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar - Kebun Binatang Indah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #e8fbe8 0%, #b2f7b2 100%);
    }

    .register-card {
      max-width: 420px;
      width: 100%;
      border-radius: 1rem;
      box-shadow: 0 8px 32px rgba(25, 135, 84, 0.12);
      background: #fff;
      margin: 30px auto;
      animation: fadeIn 1s;
    }

 @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to { opacity: 1; transform: translateY(0);}
    }
    .register-header {
      background: linear-gradient(90deg, #198754 60%, #43e97b 100%);
      color: white;
      text-align: center;
      padding: 20px 0;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
    }

    .btn-register {
      background: linear-gradient(90deg, #198754 60%, #43e97b 100%);
      color: white;
      font-weight: bold;
      border: none;
    }

    .btn-register:hover {
      background: linear-gradient(90deg, #157347 60%, #43e97b 100%);
    }

    .input-group-text {
      background: #e8fbe8;
      border: none;
      color: #198754;
    }
  </style>
</head>

<body>

  <div class="register-card">
    <div class="register-header position-relative">
      <a href="../pages/index.php" class="btn btn-sm btn-light position-absolute" style="top: 10px; left: 10px;">
        <i class="bi bi-house-door"></i> Kembali ke Beranda
      </a>
      <i class="bi bi-person-plus" style="font-size:2rem;"></i>
      <h4 class="mb-0 mt-2">Daftar Akun Baru</h4>
    </div>
    <div class="card-body p-4">
      <?php if (!empty($pesan)) echo $pesan; ?>

      <form action="" method="POST">
        <div class="mb-3">
          <label for="fullname" class="form-label">username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control" id="fullname" name="nama" required>
          </div>
        </div>


        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Kata Sandi</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" required minlength="6">
          </div>
        </div>

        <div class="mb-3">
          <label for="confirm" class="form-label">Konfirmasi Kata Sandi</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" id="confirm" name="confirm" required minlength="6">
          </div>
        </div>

        <button type="submit" class="btn btn-register w-100"><a href="login.php">Daftar</a></button>
      </form>

      <div class="text-center mt-3">
        <p class="mb-1">Sudah punya akun? <a href="login.php" class="text-success fw-bold">Login</a></p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>