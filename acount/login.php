<?php 
session_start(); 
$alert_message = "";
if (isset($_POST['login'])) {
    include "../database/conn.php"; 

    $login_id = $_POST['login_id'];
    $password = md5($_POST['password']); 

    $sql = "SELECT * FROM users WHERE (email='$login_id' OR nama='$login_id') AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../pages/beranda.php");
        }
        exit;
    } else {
        $alert_message = "<div class='alert alert-danger text-center'>Nama/Email atau password salah ❌</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Kebun Binatang Indah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #e8fbe8 0%, #b2f7b2 100%);
    }
    .login-card {
      max-width: 400px;
      width: 100%;
      border-radius: 1rem;
      box-shadow: 0 8px 32px rgba(25,135,84,0.12);
      border: none;
      background: #fff;
      animation: fadeIn 1s;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to { opacity: 1; transform: translateY(0);}
    }
    .form-control:focus {
      border-color: #198754;
      box-shadow: 0 0 5px rgba(25, 135, 84, 0.5);
    }
    .btn-login {
      background: linear-gradient(90deg, #198754 60%, #43e97b 100%);
      color: #fff;
      font-weight: bold;
      border: none;
      transition: background 0.2s, box-shadow 0.2s;
      box-shadow: 0 2px 8px rgba(25,135,84,0.08);
    }
    .btn-login:hover {
      background: linear-gradient(90deg, #157347 60%, #43e97b 100%);
      box-shadow: 0 4px 16px rgba(25,135,84,0.15);
    }
    .input-group-text {
      background: #e8fbe8;
      border: none;
      font-size: 1.2rem;
      color: #198754;
    }
    .login-icon {
      font-size: 3rem;
      color: #198754;
      background: #e8fbe8;
      border-radius: 50%;
      padding: 1rem;
      margin-bottom: 1rem;
      box-shadow: 0 2px 8px rgba(25,135,84,0.08);
      display: inline-block;
    }
  </style>
</head>
<body>

  <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center">
    <div class="card login-card p-4">
      <div class="text-center">
        <a href="../pages/beranda.php" class="btn btn-sm btn-outline-success mb-3 position-absolute" style="top: 15px; left: 15px;">
          <i class="bi bi-house-door"></i> Kembali ke Beranda
        </a>
        <span class="login-icon">
          <i class="bi bi-person-circle"></i>
        </span>
        <h3 class="fw-bold mb-2 text-success">Welcome back!</h3>
        <p class="text-muted mb-4">Masukkan nama atau email & password untuk login</p>
      </div>

      <!-- FORM LOGIN -->
      <form action="" method="post">
        <?php if (!empty($alert_message)) echo $alert_message; ?>
        <div class="mb-3">
          <label for="login_id" class="form-label">Nama atau Email</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-person"></i>
            </span>
            <input type="text" class="form-control" id="login_id" name="login_id" placeholder="Masukkan nama atau email" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Kata sandi</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-lock"></i>
            </span>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required minlength="6">
          </div>
        </div>
        <button type="submit" name="login" class="btn btn-login w-100 mb-2">Login</button>
      </form>

      <div class="text-center mt-3">
        <p class="mb-1">Belum punya akun? <a href="register.php" class="text-success fw-bold">Daftar</a></p>
      </div>
    </div>
  </div>
</body>
</html>
