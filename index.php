<?php
session_start();

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: index.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kebun Binatang Indah</title>
  <link rel="icon" href="picture/maskot.png" type="image/png">
  <?php include 'bootstrap.php'; ?>
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
      background: #343a40;
      color: white;
      padding: 24px 0 12px 0;
      margin-top: 48px;
      width: 100%;
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
  <nav class="navbar navbar-expand-lg navbar-light sticky-top"
    style="background-color: white; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    <div class="container">
      <a class="navbar-brand" href="index.php" style="font-weight: bold; color: #198754;">
        <i class="bi bi-tree-fill me-2"></i>Zoo Ticket
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="./pages/tiket.php">Tiket Saya</a>
          </li>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link text-primary fw-bold" href="./admin/dashboard.php">
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
              <a class="nav-link text-success" href="./acount/login.php">
                <i class="bi bi-box-arrow-in-right"></i> Login
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="ticket-header"
    style="background-color: #198754; color: white; padding: 2rem 0; margin-bottom: 2rem; border-radius: 0 0 1rem 1rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <div class="container text-center">
      <h1><i class="bi bi-house-fill me-2"></i>Beranda</h1>
      <p class="lead">Selamat datang di Kebun Binatang Indah</p>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">

      <div class="col-md-2 sidebar position-sticky" style="top: 80px; height: calc(100vh - 80px); z-index: 2;">
        <ul class="nav flex-column">
          <li class="nav-item mb-2">
            <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link" href="index.php">
              <i class="bi bi-house-door me-2"></i> Beranda
            </a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link"
              href="./pages/animal.php">
              <i class="bi bi-paw me-2"></i> Animal
            </a>
          </li>
          <li class="nav-item mb-2">
            <a class="nav-link text-success fw-bold fs-4 d-flex align-items-center sidebar-link"
              href="./pages/booking.php">
              <i class="bi bi-ticket-perforated me-2"></i> Booking Tiket
            </a>
          </li>
        </ul>
      </div>

  
      <div class="col-md-10 p-4">
        <div class="hero-bg p-4 mb-4 d-flex flex-column flex-md-row align-items-center justify-content-between">
          <div>
            <h2 class="text-success fw-bold mb-2">Selamat Datang di Kebun Binatang Indah</h2>
            <p class="lead mb-3">Wisata keluarga, edukasi satwa, dan rekreasi alam terbaik di kota Anda!</p>
            <a href="./pages/booking.php" class="btn btn-success btn-lg fw-bold shadow-sm"><i
                class="bi bi-ticket-perforated me-2"></i>Pesan Tiket Sekarang</a>
          </div>
          <img src="./picture/maskot.png" class="rounded shadow ms-md-4 mt-4 mt-md-0" alt="Zoo Hero"
            style="max-width: 350px;">
        </div>

        <div class="row mb-4 text-center">
          <div class="col-md-4 mb-2">
            <div class="p-3 bg-white rounded shadow-sm h-100">
              <div class="facility-icon"><i class="bi bi-clock"></i></div>
              <h6 class="fw-bold text-success mb-1">Jam Operasional</h6>
              <p class="mb-0">Setiap Hari<br>08.00 - 17.00 WIB</p>
            </div>
          </div>
          <div class="col-md-4 mb-2">
            <div class="p-3 bg-white rounded shadow-sm h-100">
              <div class="facility-icon"><i class="bi bi-geo-alt"></i></div>
              <h6 class="fw-bold text-success mb-1">Lokasi</h6>
              <p class="mb-0">Jl. Satwa Raya No. 1, Kota Hijau</p>
            </div>
          </div>
          <div class="col-md-4 mb-2">
            <div class="p-3 bg-white rounded shadow-sm h-100">
              <div class="facility-icon"><i class="bi bi-telephone"></i></div>
              <h6 class="fw-bold text-success mb-1">Kontak</h6>
              <p class="mb-0">0812-3456-7890<br>info@kebunbinatangindah.com</p>
            </div>
          </div>
        </div>

        <div class="p-4 mb-4"
          style="background: linear-gradient(135deg, #e8fbe8 70%, #b2f7b2 100%); border-radius: 1rem;">
          <div id="carouselZoo" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner rounded shadow-sm">
              <div class="carousel-item active">
                <img src="./picture/gate.png" class="d-block w-50 mx-auto" alt="Kebun Binatang">
              </div>
              <div class="carousel-item">
                <img src="./picture/panda.jpg" class="d-block w-50 mx-auto" alt="panda">
              </div>
              <div class="carousel-item">
                <img src="./picture/koala.jpg" class="d-block w-50 mx-auto" alt="koala">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselZoo" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselZoo" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          </div>
        </div>

        <h3 class="text-success mb-3">Fasilitas Kami</h3>
        <div class="row g-4 mb-4">
          <div class="col-md-3">
            <div class="card shadow-sm h-100 text-center">
              <img src="https://tempatwisataseru.com/wp-content/uploads/2019/08/Taman-Satwa-Ragunan-via-Medcom.jpg"
                class="card-img-top" alt="Area Bermain Anak">
              <div class="card-body">
                <h5 class="card-title text-success">Area Bermain Anak</h5>
                <p class="card-text">Tempat bermain aman & menyenangkan bagi anak-anak dengan wahana edukatif.</p>
                <div class="facility-icon mt-3"><i class="bi bi-balloon"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card shadow-sm h-100 text-center">
              <img
                src="https://awsimages.detik.net.id/community/media/visual/2023/03/21/you-coffee-and-resto-kafe-dekat-ragunan-5_169.jpeg?w=1200"
                class="card-img-top rounded-top" alt="Restoran & Kafe">
              <div class="card-body">
                <h5 class="card-title text-success fs-4">Restoran & Kafe</h5>
                <p class="card-text fs-5">Nikmati hidangan lezat dengan suasana alam terbuka.</p>
                <div class="facility-icon mt-3"><i class="bi bi-cup-hot"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card shadow-sm h-100 text-center">
              <img src="https://gembiralokazoo.com/storage/facility/6iCvP3hJgMz2ijztLzDqHZRK3nVPzG5aglTC6tLM.jpg"
                class="card-img-top rounded-top" alt="Toko Souvenir">
              <div class="card-body">
                <h5 class="card-title text-success fs-4">Toko Souvenir</h5>
                <p class="card-text fs-5">Dapatkan oleh-oleh dan cinderamata khas Kebun Binatang Indah.</p>
                <div class="facility-icon mt-3"><i class="bi bi-bag-heart"></i></div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card shadow-sm h-100 text-center">
              <img
                src="https://cloud.jpnn.com/photo/jatim/news/normal/2023/04/24/salah-satu-area-piknik-untuk-menggelar-tikar-di-kebun-binata-c9z0.jpg"
                class="card-img-top rounded-top" alt="Area Piknik">
              <div class="card-body">
                <h5 class="card-title text-success fs-4">Area Piknik</h5>
                <p class="card-text fs-5">Ruang terbuka hijau untuk bersantai bersama keluarga.</p>
                <div class="facility-icon mt-3"><i class="bi bi-tree"></i></div>
              </div>
            </div>
          </div>
        </div>

        <h3 class="text-success mb-3">Satwa Populer</h3>
        <div class="row g-4 mb-4">
          <div class="col-md-2 col-6">
            <div class="card shadow-sm h-100 text-center overflow-hidden d-flex flex-column justify-content-between">
              <img
                src="https://offloadmedia.feverup.com/secretchicago.com/wp-content/uploads/2022/05/13053044/Linvoln-Park-Zoo-scaled.jpg"
                class="animal-img" alt="Singa">
              <div class="card-body py-2">
                <h6 class="card-title text-success mb-0 fs-5">Singa</h6>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm h-100 text-center overflow-hidden d-flex flex-column justify-content-between">
              <img src="https://cdn.pixabay.com/video/2022/07/03/123027-726548100_tiny.jpg" class="animal-img"
                alt="Gajah">
              <div class="card-body py-2">
                <h6 class="card-title text-success mb-0 fs-5">Gajah</h6>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm h-100 text-center overflow-hidden d-flex flex-column justify-content-between">
              <img src="https://www.shutterstock.com/shutterstock/videos/3647070567/thumb/1.jpg?ip=x480"
                class="animal-img" alt="Harimau">
              <div class="card-body py-2">
                <h6 class="card-title text-success mb-0 fs-5">Harimau</h6>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm h-100 text-center overflow-hidden d-flex flex-column justify-content-between">
              <img src="https://giraffeworlds.com/wp-content/uploads/habitat_giraffe.jpg" class="animal-img"
                alt="Jerapah">
              <div class="card-body py-2">
                <h6 class="card-title text-success mb-0 fs-5">Jerapah</h6>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm h-100 text-center overflow-hidden d-flex flex-column justify-content-between">
              <img src="./picture/panda.jpg" class="animal-img" alt="Panda">
              <div class="card-body py-2">
                <h6 class="card-title text-success mb-0 fs-5">Panda</h6>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm h-100 text-center overflow-hidden d-flex flex-column justify-content-between">
              <img src="./picture/koala.jpg" class="animal-img" alt="Koala">
              <div class="card-body py-2">
                <h6 class="card-title text-success mb-0 fs-5">Koala</h6>
              </div>
            </div>
          </div>
        </div>

        <h3 class="text-success mt-5 mb-3">Event & Informasi Menarik</h3>
        <div class="row g-4 mb-4">
          <div class="col-md-6">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h5 class="card-title text-success">🎤 Pertunjukan Satwa</h5>
                <p class="card-text">Setiap akhir pekan, nikmati aksi dari burung eksotis, hingga reptil yang
                  menakjubkan!</p>
                <span class="badge bg-success">Setiap Sabtu & Minggu</span>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h5 class="card-title text-success">📚 Program Edukasi</h5>
                <p class="card-text">Kegiatan edukasi khusus anak sekolah, mengenal satwa secara langsung bersama
                  pemandu.</p>
                <span class="badge bg-success">Reservasi Group</span>
              </div>
            </div>
          </div>
        </div>

        <h3 class="text-success mb-3">Galeri Foto</h3>
        <div class="row g-3">
          <div class="col-md-3 col-6">
            <div class="card shadow-sm h-100">
              <img
                src="https://wendytour.co.id/wp-content/uploads/2019/10/N454J_1decbeed-5a7d-472e-af89-c1e6d888cf8c.jpg"
                class="card-img-top rounded-top" alt="Gerbang Kebun Binatang">
              <div class="card-body p-2">
                <p class="card-text text-success fw-bold mb-0">beruang</p>
                <small class="text-muted">salah satu beruang yang ada dalam pengawasan kami</small>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="card shadow-sm h-100">
              <img
                src="https://media.istockphoto.com/id/1289372008/id/foto/anak-anak-memberi-makan-badak-di-kebun-binatang-keluarga-di-taman-hewan.jpg?b=1&s=612x612&w=0&k=20&c=zCFWXCkd8lrCbh3A9I2lPYIWLP9wJbfU9TqHuMDoop0="
                class="card-img-top rounded-top" alt="Memberi Makan Badak">
              <div class="card-body p-2">
                <p class="card-text text-success fw-bold mb-0">Memberi Makan Badak</p>
                <small class="text-muted">Interaksi pengunjung dengan satwa</small>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="card shadow-sm h-100">
              <img
                src="https://p4.wallpaperbetter.com/wallpaper/332/374/563/panda-china-giant-panda-zoo-cute-animals-wallpaper-preview.jpg"
                class="card-img-top rounded-top" alt="Panda">
              <div class="card-body p-2">
                <p class="card-text text-success fw-bold mb-0">Panda Lucu</p>
                <small class="text-muted">Satwa favorit anak-anak</small>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="card shadow-sm h-100">
              <img
                src="https://media.istockphoto.com/id/1248960383/id/video/seorang-wanita-muda-dan-putra-kecilnya-memberi-makan-jerapah-di-taman-safari.jpg?s=640x640&k=20&c=eowyDFndfphzbaXukc2hXGxCknP0T7vk7TAsCwPg42I="
                class="card-img-top rounded-top" alt="Memberi Makan Jerapah">
              <div class="card-body p-2">
                <p class="card-text text-success fw-bold mb-0">Memberi Makan Jerapah</p>
                <small class="text-muted">Pengalaman seru bersama keluarga</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="footer text-center">
        <div class="container">
          <div class="row">
            <div class="col-md-6 mb-2 mb-md-0">
              <span class="fw-bold">Kebun Binatang Indah</span> &copy; 2025
            </div>
            <div class="col-md-6">
              <div class="d-flex flex-column flex-md-row justify-content-end">
                <div class="me-md-3">
                  Jl. Satwa Raya No. 1, Kota Hijau | Telp: 0812-3456-7890 | Email: info@kebunbinatangindah.com
                </div>
                <div class="mt-2 mt-md-0 small">
                  <span><i class="bi bi-facebook"></i> KebunBinatangIndah</span>
                  <span><i class="bi bi-instagram"></i> @kebunbinatangindah</span> &nbsp;
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>