<style>
.sidebar {
  width: var(--sidebar-width, 250px);
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  background: var(--primary-color, #198754);
  color: white;
  padding: 20px 0;
  transition: all 0.3s;
  z-index: 1000;
}

.sidebar .logo {
  padding: 15px 25px;
  font-size: 22px;
  font-weight: 700;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 20px;
}

.sidebar .nav-link {
  color: rgba(255, 255, 255, 0.8);
  padding: 12px 25px;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 10px;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
  color: white;
  background: rgba(255, 255, 255, 0.1);
  border-left: 4px solid var(--accent-color, #ffc107);
}

.sidebar .nav-link i {
  width: 20px;
  text-align: center;
}

@media (max-width: 992px) {
  .sidebar {
    width: 70px;
  }

  .sidebar .logo {
    padding: 15px;
    font-size: 18px;
    text-align: center;
  }

  .sidebar .nav-link span {
    display: none;
  }

  .sidebar .nav-link {
    padding: 12px;
    justify-content: center;
  }
}
</style>

<div class="sidebar">
  <div class="logo">
    <i class="fas fa-paw me-2"></i> Zoo Admin
  </div>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="hewan.php">
        <i class="fas fa-hippo"></i>
        <span>Kelola Hewan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="laporan_tiket.php">
        <i class="fas fa-ticket-alt"></i>
        <span>Kelola Tiket</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="laporan_pendapatan.php">
        <i class="fas fa-chart-line"></i>
        <span>Laporan Keuangan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="pengeluaran_list.php">
        <i class="fas fa-receipt"></i>
        <span>Kelola Pengeluaran</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="users.php">
        <i class="fas fa-users"></i>
        <span>Kelola Pengguna</span>
      </a>
    </li>
    <li class="nav-item mt-4">
      <a class="nav-link" href="../index.php">
        <i class="fas fa-home"></i>
        <span>Halaman Utama</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-danger" href="../acount/logout.php">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </li>
  </ul>
</div>
