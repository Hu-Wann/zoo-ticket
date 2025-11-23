<?php
session_start();
include '../database/conn.php';

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: ../index.php");
  exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
  $query = "SELECT * FROM animals WHERE 
            nama LIKE '%$search%' OR 
            habitat LIKE '%$search%' OR 
            makanan LIKE '%$search%' OR 
            deskripsi LIKE '%$search%' OR 
            status_konservasi LIKE '%$search%' 
            ORDER BY id DESC";
} else {
  $query = "SELECT * FROM animals ORDER BY id DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hewan - Kebun Binatang Indah</title>
  <?php include '../bootstrap.php'; ?>
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

    /* Sidebar (match index.php) */
    .sidebar {
      min-height: 100vh;
      background-color: #d4f8d4;
      padding-top: 20px;
      border-right: 2px solid #b2f7b2;
    }

    .sidebar-link {
      border-radius: 0.5rem;
      padding: 0.5rem 1rem;
      transition: background 0.2s, color 0.2s;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
      background: linear-gradient(90deg, #e8fbe8 60%, #b2f7b2 100%);
      color: #157347 !important;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
      <a class="navbar-brand" href="../index.php">
        <i class="bi bi-tree-fill me-2"></i>Zoo Ticket
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="tiket.php">Tiket Saya</a></li>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link text-primary fw-bold" href="../admin/dashboard.php"><i
                  class="bi bi-speedometer2"></i> Admin Panel</a></li>
          <?php endif; ?>
          <?php if (isset($_SESSION['email'])): ?>
            <li class="nav-item"><span class="nav-link text-success">👋
                <?php echo htmlspecialchars($_SESSION['nama']); ?></span></li>
            <li class="nav-item"><a class="nav-link text-danger" href="?logout=1"><i class="bi bi-box-arrow-right"></i>
                Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link text-success" href="../acount/login.php"><i
                  class="bi bi-box-arrow-in-right"></i> Login</a></li>
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

  <!-- Sidebar + Konten -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar position-sticky" style="top: 80px; height: calc(100vh - 80px); z-index: 2;">
        <?php $page = basename($_SERVER['SCRIPT_NAME']); ?>
        <ul class="nav flex-column">
          <li class="nav-item mb-2">
            <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link <?php echo $page === 'index.php' ? 'active' : ''; ?>"
              href="../index.php">
              <i class="bi bi-house-door me-2"></i> Beranda
            </a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link <?php echo $page === 'animal.php' ? 'active' : ''; ?>"
              href="animal.php">
              <i class="bi bi-paw me-2"></i> Animal
            </a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link <?php echo $page === 'booking.php' ? 'active' : ''; ?>"
              href="booking.php">
              <i class="bi bi-ticket-perforated me-2"></i> Booking Tiket
            </a>
          </li>
        </ul>
      </div>
      <div class="col-md-10 p-4">
        <!-- Form Pencarian -->
        <div class="row mb-4">
          <div class="col-md-6 mx-auto">
            <form class="d-flex shadow-sm rounded overflow-hidden" method="GET" action="">
              <input class="form-control border-0 py-2" type="search" name="search" placeholder="Cari nama hewan..."
                value="<?php echo htmlspecialchars($search); ?>">
              <button style="margin-left: 10px;" class="btn btn-success px-3" type="submit">
                <i class="bi bi-search"></i> Cari
              </button>
              <?php if ($search !== ''): ?>
                <a href="animal.php" class="btn btn-outline-secondary ms-2">Reset</a>
              <?php endif; ?>
            </form>
          </div>
        </div>

        <!-- Daftar Hewan -->
        <div class="row">
          <div class="col-12">
            <h2 class="text-success mb-4 fs-2">Daftar Hewan</h2>
            <div class="row g-4 mb-4">
              <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card animal-card h-100 text-center">
                      <img src="../picture/<?php echo $row['gambar']; ?>" class="animal-img"
                        alt="<?php echo $row['nama']; ?>">
                      <div class="card-body">
                        <h5 class="card-title text-success fw-bold mb-3"><?php echo htmlspecialchars($row['nama']); ?></h5>
                        <div class="animal-info mb-2">
                          <span class="badge bg-light text-dark mb-1"><i class="bi bi-geo-alt me-1"></i>Habitat:
                            <?php echo $row['habitat']; ?></span><br>
                          <span class="badge bg-light text-dark"><i class="bi bi-egg-fried me-1"></i>Makanan:
                            <?php echo $row['makanan']; ?></span>
                        </div>
                        <p class="card-text"><?php echo $row['deskripsi']; ?></p>
                        <span class="badge bg-success"><?php echo $row['status_konservasi']; ?></span>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              <?php else: ?>
                <p class="text-muted">Tidak ada hewan ditemukan.</p>
              <?php endif; ?>
            </div>
          </div>
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
          <p>&copy; 2025 Zoo Ticket. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>