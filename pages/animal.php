<?php
include '../database/conn.php'; 


$sql = "SELECT * FROM animals";
$result = $koneksi->query($sql);
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
      background-color: #f8fff8;
    }

    .sidebar {
      min-height: 100vh;
      background-color: #d4f8d4;
      padding-top: 20px;
      border-right: 2px solid #b2f7b2;
    }

    .hero-bg {
      background: linear-gradient(90deg, #e8fbe8 60%, #b2f7b2 100%);
      border-radius: 1rem;
      box-shadow: 0 4px 24px rgba(25, 135, 84, 0.08);
    }

    .facility-icon {
      font-size: 2rem;
      color: #198754;
      margin-bottom: 0.5rem;
    }

    .animal-img {
      border-radius: 1rem;
      object-fit: cover;
      height: 120px;
      width: 100%;
    }

    .footer {
      background: #d4f8d4;
      color: #198754;
      padding: 24px 0 12px 0;
      margin-top: 48px;
      border-top: 2px solid #b2f7b2;
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
  <!-- Judul -->
  <div class="text-center py-3 bg-white shadow-sm">
    <h1 class="text-success">🌿 Kebun Binatang Indah</h1>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="tiket.html">Informasi Tiket</a></li>
        <li class="nav-item"><a class="nav-link" href="../acount/login.html">Login</a></li>
      </ul>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar position-sticky" style="top: 80px; height: calc(100vh - 80px); z-index: 2;">
        <ul class="nav flex-column">
          <li class="nav-item mb-2">
            <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link" href="index.html">
              <i class="bi bi-house-door me-2"></i> Beranda
            </a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link active" href="#">
              <i class="bi bi-paw me-2"></i> Animal
            </a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link text-success fw-bold fs-5" href="booking.html">
              <i class="bi bi-ticket-perforated me-2"></i> Booking Tiket
            </a>
          </li>
        </ul>
      </div>

      <!-- Konten -->
      <div class="col-md-10 p-4">
        <h2 class="text-success mb-3 fs-2">Daftar Hewan</h2>
        <div class="row g-4 mb-4">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card shadow-sm h-100 text-center">
                  <img src="<?php echo $row['gambar']; ?>" class="card-img-top" alt="<?php echo $row['nama']; ?>" style="height:150px;object-fit:cover;">
                  <div class="card-body">
                    <h5 class="card-title text-success"><?php echo $row['emoji'].' '.$row['nama']; ?></h5>
                    <div class="animal-info">
                      Habitat: <?php echo $row['habitat']; ?><br>
                      Makanan: <?php echo $row['makanan']; ?>
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
</body>
</html>
