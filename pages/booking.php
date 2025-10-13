<?php
session_start();

// Logout jika tombol logout ditekan 
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: beranda.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Tiket - Kebun Binatang Indah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .booking-card {
      max-width: 600px;
      margin: 0 auto;
      border-radius: 1rem;
      box-shadow: 0 8px 32px rgba(25, 135, 84, 0.12);
      background: #fff;
      border: none;
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
            <a class="nav-link" href="animal.php">Hewan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="booking.php">Booking</a>
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

  <div class="ticket-header">
    <div class="container text-center">
      <h1><i class="bi bi-calendar-check me-2"></i>Booking Tiket</h1>
      <p class="lead">Pesan tiket kebun binatang dengan mudah dan cepat</p>
    </div>
  </div>

  <div class="container">
    <!-- Konten Booking -->
    <div class="row">
      <div class="col-12">
        <h2 class="text-success mb-3 fs-2">Booking Tiket Kebun Binatang Indah</h2>
        <p class="lead fs-4 mb-4">Pesan tiket kunjungan Anda secara online. Pilih jumlah tiket sesuai kategori pengunjung di bawah ini.</p>
        <div class="booking-card p-4 mb-5">
          <form action="../admin/sys_booking.php" method="POST" id="formBooking">

            <div class="mb-3">
              <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
              <input type="text" class="form-control" id="nama" name="nama_pengunjung" placeholder="Masukkan nama lengkap" required
                value="<?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : ''; ?>">

            </div>
            <div class="mb-3">
              <label for="email" class="form-label fw-bold">Email</label>
              <input type="email" class="form-control" id="email" name="email"
                value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>"
                <?php echo isset($_SESSION['email']) ? 'readonly' : ''; ?>>
            </div>

            <div class="mb-3">
              <?php
              $today = date('Y-m-d');
              $maxDate = date('Y-m-d', strtotime('+30 days'));
              ?>
              <label for="tanggal_kunjungan" class="form-label fw-bold">Tanggal Kunjungan</label>
              <input type="date"
                class="form-control"
                id="tanggal_kunjungan"
                name="tanggal_kunjungan"
                min="<?php echo $today; ?>"
                max="<?php echo $maxDate; ?>"
                required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Jumlah Tiket per Kategori</label>
              <div class="row g-3">
                <div class="col-12">
                  <div class="card mb-2">
                    <div class="card-body py-2">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <i class="bi bi-person fs-4 text-success me-2"></i>
                          <span class="fw-bold">Dewasa</span>
                          <small class="text-muted d-block">Rp 40.000</small>
                        </div>
                        <div style="width: 100px;">
                          <input type="number" class="form-control" min="0" max="20" value="0" id="dewasa" name="dewasa" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="card mb-2">
                    <div class="card-body py-2">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <i class="bi bi-people fs-4 text-success me-2"></i>
                          <span class="fw-bold">Remaja</span>
                          <small class="text-muted d-block">Rp 35.000</small>
                        </div>
                        <div style="width: 100px;">
                          <input type="number" class="form-control" min="0" max="20" value="0" id="remaja" name="remaja" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="card mb-2">
                    <div class="card-body py-2">
                      <div class="d-flex align-items-center justify-content-between">
                        <div>
                          <i class="bi bi-person-fill fs-4 text-success me-2"></i>
                          <span class="fw-bold">Anak-anak</span>
                          <small class="text-muted d-block">Rp 25.000</small>
                        </div>
                        <div style="width: 100px;">
                          <input type="number" class="form-control" min="0" max="20" value="0" id="anak" name="anak" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="catatan" class="form-label fw-bold">Catatan Tambahan</label>
              <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="(Opsional)"></textarea>
            </div>

            <div class="mb-4">
              <div class="alert alert-success mb-2">
                <strong>Harga Tiket:</strong>
                <div class="row mt-2">
                  <div class="col-md-4 mb-2">
                    <div class="card bg-light">
                      <div class="card-body text-center py-2">
                        <h6 class="mb-1">Dewasa</h6>
                        <span class="fw-bold">Rp 40.000</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-2">
                    <div class="card bg-light">
                      <div class="card-body text-center py-2">
                        <h6 class="mb-1">Remaja</h6>
                        <span class="fw-bold">Rp 35.000</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-2">
                    <div class="card bg-light">
                      <div class="card-body text-center py-2">
                        <h6 class="mb-1">Anak-anak</h6>
                        <span class="fw-bold">Rp 25.000</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="alert alert-info mb-0">
                <strong>Total Harga:</strong>
                <span id="totalHarga" class="fw-bold text-success">Rp 0</span>
              </div>
            </div>

            <?php if (isset($_SESSION['email'])): ?>
              <button type="submit" name="booking" class="btn btn-success w-100 fw-bold fs-5">
                <i class="bi bi-cart-check me-2"></i> Pesan Tiket
              </button>
            <?php else: ?>
              <button type="button" class="btn btn-success w-100 fw-bold fs-5" onclick="alert('Silakan login terlebih dahulu untuk memesan tiket')">
                <i class="bi bi-cart-check me-2"></i> Pesan Tiket
              </button>
            <?php endif; ?>

          </form>
        </div>


      </div>
    </div>
  </div>

  <!-- Footer -->
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Hitung total harga otomatis
    function hitungTotal() {
      const hargaDewasa = 40000;
      const hargaAnak = 25000;
      const hargaRemaja = 35000;
      const dewasa = parseInt(document.getElementById('dewasa').value) || 0;
      const anak = parseInt(document.getElementById('anak').value) || 0;
      const remaja = parseInt(document.getElementById('remaja').value) || 0;
      const total = (dewasa * hargaDewasa) + (anak * hargaAnak) + (remaja * hargaRemaja);
      document.getElementById('totalHarga').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('dewasa').addEventListener('input', hitungTotal);
    document.getElementById('anak').addEventListener('input', hitungTotal);
    document.getElementById('remaja').addEventListener('input', hitungTotal);

    window.addEventListener('DOMContentLoaded', hitungTotal);

    // 
    document.getElementById('tanggal_kunjungan').addEventListener('change', function() {
      const tanggal = this.value;
      if (!tanggal) return;

      fetch(`../admin/cek_stok.php?tanggal=${tanggal}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'ok') {
            if (data.stok <= 0) {
              alert('❌ Maaf, stok tiket pada tanggal ini sudah habis.');
              document.getElementById('formBooking').querySelector('button[type="submit"]').disabled = true;
            } else {
              document.getElementById('formBooking').querySelector('button[type="submit"]').disabled = false;
            }
          }
        })
        .catch(err => console.error(err));
    });
  </script>
</body>

</html>