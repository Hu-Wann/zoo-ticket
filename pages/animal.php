<?php
session_start();
include '../database/conn.php'; 

// Logout jika tombol logout ditekan 
if (isset($_GET['logout'])) { 
  session_destroy(); 
  header("Location: beranda.php"); 
  exit; 
} 

$sql = "SELECT * FROM animals";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hewan - Kebun Binatang Indah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .animal-img {
      border-radius: 1rem;
      object-fit: cover;
      height: 200px;
      width: 100%;
    }

    .animal-card {
      border-radius: 1rem;
      overflow: hidden;
      transition: transform 0.3s, box-shadow 0.3s;
      border: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .animal-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(25, 135, 84, 0.15);
    }

    .ticket-header {
      background-color: #198754;
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
      border-radius: 0 0 1rem 1rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navbar {
      background-color: white;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: bold;
      color: #198754;
    }

    .nav-link {
      color: #333;
      font-weight: 500;
    }

    .nav-link:hover {
      color: #198754;
    }

    .footer {
      background: #343a40;
      color: white;
      padding: 24px 0 12px 0;
      margin-top: 48px;
      width: 100%;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
      <a class="navbar-brand" href="beranda.php">
        <i class="bi bi-tree-fill me-2"></i>Zoo Ticket
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="beranda.php">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="animal.php">Hewan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="booking.php">Booking</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="tiket.php">Tiket Saya</a>
          </li>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link text-primary fw-bold" href="../admin/dashboard.php">
              <i class="bi bi-speedometer2"></i> Admin Panel
            </a>
          </li>
          <?php endif; ?>
          <?php if (isset($_SESSION['email'])): ?>
          <li class="nav-item">
            <span class="nav-link text-success">
              👋 <?php echo htmlspecialchars($_SESSION['nama']); ?>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="?logout=1">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
          <?php else: ?>
          <li class="nav-item">
            <a class="nav-link text-success" href="../acount/login.php">
              <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Header -->
  <div class="ticket-header">
    <div class="container text-center">
      <h1><i class="bi bi-paw me-2"></i>Koleksi Hewan</h1>
      <p class="lead">Temukan berbagai hewan menarik di kebun binatang kami</p>
    </div>
  </div>

  <div class="container">
    <!-- Konten -->
    <div class="row">
      <div class="col-12">
        <h2 class="text-success mb-4 fs-2">Daftar Hewan</h2>
        <div class="row g-4 mb-4">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card animal-card h-100 text-center">
                  <img src="<?php echo $row['gambar']; ?>" class="animal-img" alt="<?php echo $row['nama']; ?>">
                  <div class="card-body">
                    <div class="animal-info mb-2">
                      <span class="badge bg-light text-dark mb-1"><i class="bi bi-geo-alt me-1"></i>Habitat: <?php echo $row['habitat']; ?></span><br>
                      <span class="badge bg-light text-dark"><i class="bi bi-egg-fried me-1"></i>Makanan: <?php echo $row['makanan']; ?></span>
                    </div>
                    <p class="card-text"><?php echo $row['deskripsi']; ?></p>
                    <span class="badge bg-success"><?php echo $row['status_konservasi']; ?></span>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p class="text-muted">Belum ada data hewan.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5><i class="bi bi-tree-fill me-2"></i>Zoo Ticket</h5>
          <p>Sistem pemesanan tiket kebun binatang online yang mudah dan cepat.</p>
        </div>
        <div class="col-md-6 text-md-end">
          <p>&copy; 2023 Zoo Ticket. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
