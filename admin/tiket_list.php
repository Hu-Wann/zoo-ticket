<?php
session_start();
include "../database/conn.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

$query = "SELECT * FROM booking ORDER BY tanggal_booking DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tiket - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { 
            background-color: #f8fff8; 
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        table th { 
            background-color: #198754; 
            color: white; 
            padding: 12px;
            font-weight: 500;
        }
        .btn-action { 
            margin: 2px; 
            border-radius: 6px;
            transition: all 0.2s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table td, .table th { 
            vertical-align: middle; 
            padding: 12px;
        }
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 6px;
        }
        .mass-action-bar {
            background-color: #f0f9f0;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .mass-action-bar .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
        }
        .page-header {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .custom-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .selected-count {
            background: #e9f7ef;
            padding: 6px 12px;
            border-radius: 6px;
            margin-left: auto;
            font-weight: 500;
            color: #198754;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="text-success mb-0"><i class="bi bi-ticket-detailed me-2"></i> Daftar Tiket Booking</h3>
        <a href="dashboard.php" class="btn btn-outline-success btn-action">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <form method="POST" action="proses_tiket.php" id="ticketForm">
        <!-- Tombol aksi massal -->
        <div class="mass-action-bar">
            <button type="submit" name="aksi" value="acc_selected" class="btn btn-success" id="approveSelected" disabled>
                <i class="bi bi-check2-square me-1"></i> Setujui Terpilih
            </button>
            <button type="submit" name="aksi" value="dec_selected" class="btn btn-danger" id="rejectSelected" disabled>
                <i class="bi bi-x-square me-1"></i> Tolak Terpilih
            </button>
            <button type="submit" name="aksi" value="delete_selected" class="btn btn-outline-danger" id="deleteSelected" disabled>
                <i class="bi bi-trash me-1"></i> Hapus Terpilih
            </button>
            <div class="selected-count" id="selectedCount">0 tiket dipilih</div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead>
                        <tr class="text-center">
                            <th width="40px">
                                <input type="checkbox" id="checkAll" class="custom-checkbox">
                            </th>
                            <th width="50px">ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tanggal Kunjungan</th>
                            <th width="70px">Dewasa</th>
                            <th width="70px">Remaja</th>
                            <th width="70px">Anak</th>
                            <th width="100px">Kode Redeem</th>
                            <th width="100px">Status</th>
                            <th width="120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php $no = 1; // Inisialisasi nomor urut ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="selected_ids[]" value="<?= $row['id']; ?>" class="ticket-checkbox custom-checkbox">
                                    </td>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_pengunjung']); ?></td>
                                    <td><?= htmlspecialchars($row['email']); ?></td>
                                    <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_kunjungan'])); ?></td>
                                    <td class="text-center"><?= $row['jumlah_dewasa']; ?></td>
                                    <td class="text-center"><?= $row['jumlah_remaja']; ?></td>
                                    <td class="text-center"><?= $row['jumlah_anak']; ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['kode_redeem']); ?></td>
                                    <td class="text-center">
                                        <?php
                                            $status = $row['status'];
                                            if ($status === 'dibooking')
                                                echo "<span class='badge bg-warning text-dark'>Dibooking</span>";
                                            elseif ($status === 'acc' || $status === 'accepted')
                                                echo "<span class='badge bg-success'>Disetujui</span>";
                                            elseif ($status === 'dec' || $status === 'declined')
                                                echo "<span class='badge bg-danger'>Ditolak</span>";
                                            elseif ($status === 'kadaluwarsa')
                                                echo "<span class='badge bg-secondary'>Kadaluwarsa</span>";
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($status === 'dibooking'): ?>
                                            <a href="proses_tiket.php?id=<?= $row['id']; ?>&aksi=acc" class="btn btn-success btn-sm btn-action" title="Setujui">
                                                <i class="bi bi-check-circle"></i>
                                            </a>
                                            <a href="proses_tiket.php?id=<?= $row['id']; ?>&aksi=dec" class="btn btn-danger btn-sm btn-action" title="Tolak">
                                                <i class="bi bi-x-circle"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="print_tiket.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-primary btn-sm btn-action" title="Cetak">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirmDelete(<?= $row['id']; ?>)" class="btn btn-outline-danger btn-sm btn-action" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="bi bi-ticket-perforated text-muted" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Belum ada data tiket.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<!-- Form untuk hapus tiket -->
<form id="deleteForm" method="POST" action="proses_tiket.php" style="display: none;">
    <input type="hidden" name="id" id="deleteId">
    <input type="hidden" name="aksi" value="delete">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Pilih semua checkbox
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.ticket-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    // Update jumlah tiket yang dipilih
    function updateSelectedCount() {
        const selectedBoxes = document.querySelectorAll('.ticket-checkbox:checked');
        const count = selectedBoxes.length;
        document.getElementById('selectedCount').textContent = count + ' tiket dipilih';
        
        // Enable/disable tombol aksi massal
        const actionButtons = [
            document.getElementById('approveSelected'),
            document.getElementById('rejectSelected'),
            document.getElementById('deleteSelected')
        ];
        
        actionButtons.forEach(button => {
            button.disabled = count === 0;
        });
    }

    // Tambahkan event listener untuk semua checkbox tiket
    document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Konfirmasi hapus tiket
    function confirmDelete(id) {
        if (confirm('Yakin ingin menghapus tiket ini?')) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }

    // Konfirmasi sebelum submit form
    document.getElementById('ticketForm').addEventListener('submit', function(e) {
        const action = e.submitter.value;
        
        if (action === 'acc_selected') {
            if (!confirm('Setujui semua tiket yang dipilih?')) {
                e.preventDefault();
            }
        } else if (action === 'dec_selected') {
            if (!confirm('Tolak semua tiket yang dipilih?')) {
                e.preventDefault();
            }
        } else if (action === 'delete_selected') {
            if (!confirm('Hapus semua tiket yang dipilih?')) {
                e.preventDefault();
            }
        }
    });

    // Inisialisasi counter
    updateSelectedCount();
</script>
</body>
</html>
