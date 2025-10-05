<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Kebun Binatang Indah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Admin Panel</a>
      <div class="d-flex">
        <a href="index.html" class="btn btn-light btn-sm">Keluar</a>
      </div>
    </div>
  </nav>

  <div class="container-fluid mt-4">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2">
        <div class="list-group">
          <a href="#dashboard" class="list-group-item list-group-item-action active" data-bs-toggle="tab">Dashboard</a>
          <a href="#hewan" class="list-group-item list-group-item-action" data-bs-toggle="tab">Data Hewan</a>
          <a href="#tiket" class="list-group-item list-group-item-action" data-bs-toggle="tab">Data Tiket</a>
          <a href="#pengguna" class="list-group-item list-group-item-action" data-bs-toggle="tab">Data Pengguna</a>
        </div>
      </div>

      <!-- Konten -->
      <div class="col-md-10">
        <div class="tab-content">
          <!-- Dashboard -->
          <div class="tab-pane fade show active" id="dashboard">
            <h3>Selamat Datang, Admin!</h3>
            <p>Gunakan menu di samping untuk mengelola data kebun binatang.</p>
            <div class="row text-center mt-4">
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5>🦁 Total Hewan</h5>
                    <p class="fs-4 fw-bold">25</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5>🎟️ Tiket Terjual</h5>
                    <p class="fs-4 fw-bold">320</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5>👤 Pengguna Terdaftar</h5>
                    <p class="fs-4 fw-bold">150</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Data Hewan -->
          <div class="tab-pane fade" id="hewan">
            <h3>Data Hewan</h3>
            <table class="table table-bordered table-striped mt-3">
              <thead class="table-success">
                <tr>
                  <th>No</th>
                  <th>Nama Hewan</th>
                  <th>Jenis</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Singa</td>
                  <td>Karnivora</td>
                  <td>
                    <button class="btn btn-sm btn-warning">Edit</button>
                    <button class="btn btn-sm btn-danger">Hapus</button>
                  </td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Gajah</td>
                  <td>Herbivora</td>
                  <td>
                    <button class="btn btn-sm btn-warning">Edit</button>
                    <button class="btn btn-sm btn-danger">Hapus</button>
                  </td>
                </tr>
              </tbody>
            </table>
            <button class="btn btn-success btn-sm">+ Tambah Hewan</button>
          </div>

          <!-- Data Tiket -->
          <div class="tab-pane fade" id="tiket">
            <h3>Data Tiket</h3>
            <table class="table table-bordered table-striped mt-3">
              <thead class="table-success">
                <tr>
                  <th>No</th>
                  <th>Nama Pemesan</th>
                  <th>Tanggal</th>
                  <th>Jumlah Tiket</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Budi</td>
                  <td>05/10/2025</td>
                  <td>3</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Sari</td>
                  <td>04/10/2025</td>
                  <td>5</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Data Pengguna -->
          <div class="tab-pane fade" id="pengguna">
            <h3>Data Pengguna</h3>
            <table class="table table-bordered table-striped mt-3">
              <thead class="table-success">
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Peran</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Admin</td>
                  <td>admin@zoo.com</td>
                  <td>Admin</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Budi</td>
                  <td>budi@mail.com</td>
                  <td>Pengguna</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
