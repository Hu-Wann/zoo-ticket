<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Hewan - Admin Panel</title>
    <?php include '../bootstrap.php'; ?>

    <style>
        body {
            background-color: #f8fff8;
            padding: 20px;
        }

        .container {
            max-width: 900px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 24px rgba(25, 135, 84, 0.15);
            border: none;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(90deg, #198754 0%, #25b570 100%);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .card-body {
            padding: 2rem;
            background-color: #ffffff;
        }

        .form-label {
            font-weight: 500;
            color: #198754;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d4f8d4;
            padding: 10px 15px;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }

        .btn-primary {
            background-color: #198754;
            border-color: #198754;
            border-radius: 8px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #157347;
            border-color: #157347;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            border-radius: 8px;
            padding: 10px 20px;
        }

        .alert {
            border-radius: 8px;
        }

        .page-title {
            color: #198754;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .header-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="page-title mb-4"><i class="bi bi-plus-circle-fill header-icon"></i> Tambah Hewan Baru</h1>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="bi bi-clipboard-plus"></i> Form Tambah Hewan</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        include("../database/conn.php");

                        session_start();
                        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                            header("Location: ../acount/login.php");
                            exit();
                        }


                        function uploadGambar($file)
                        {
                            $target_dir = "../picture/";

                            // Pastikan folder ada
                            if (!file_exists($target_dir)) {
                                mkdir($target_dir, 0777, true);
                            }

                            $file_name = basename($file["name"]);
                            $target_file = $target_dir . $file_name;
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                            // Cek file valid gambar
                            if (!getimagesize($file["tmp_name"])) {
                                return ["status" => false, "message" => "File bukan gambar."];
                            }

                            // Batas ukuran 5MB
                            if ($file["size"] > 5000000) {
                                return ["status" => false, "message" => "Ukuran file terlalu besar (max 5MB)."];
                            }

                            // Format diizinkan
                            $allowed = ["jpg", "jpeg", "png", "gif"];
                            if (!in_array($imageFileType, $allowed)) {
                                return ["status" => false, "message" => "Hanya file JPG, JPEG, PNG & GIF yang diizinkan."];
                            }

                            // Jika file dengan nama sama sudah ada → hapus file lama supaya tidak dobel
                            if (file_exists($target_file)) {

                                $target_file = '../picture/' . $file_name;
                                if (file_exists($target_file) && is_writable($target_file)) {
                                    @unlink($target_file);
                                }
                            }

                            // Upload file baru
                            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                                return ["status" => true, "file_name" => $file_name];
                            } else {
                                return ["status" => false, "message" => "Gagal mengupload file."];
                            }
                        }


                        // Proses form saat disubmit
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $nama = $_POST['nama'];
                            $habitat = $_POST['habitat'];
                            $makanan = $_POST['makanan'];
                            $deskripsi = $_POST['deskripsi'];
                            $status = $_POST['status'];

                            if (isset($_FILES["file_gambar"]) && $_FILES["file_gambar"]["error"] == 0) {
                                $upload_result = uploadGambar($_FILES["file_gambar"]);

                                if ($upload_result["status"]) {
                                    $gambar = $upload_result["file_name"];

                                    $sql = "INSERT INTO animals (nama, habitat, makanan, deskripsi, status_konservasi, gambar)
                    VALUES ('$nama', '$habitat', '$makanan', '$deskripsi', '$status', '$gambar')";

                                    if ($conn->query($sql) === TRUE) {
                                        $success_message = "<div class='alert alert-success'><i class='bi bi-check-circle-fill'></i> Hewan berhasil ditambahkan dengan gambar!</div>";
                                    } else {
                                        $error_message = "<div class='alert alert-danger'><i class='bi bi-exclamation-triangle-fill'></i> Error: " . $conn->error . "</div>";
                                    }
                                } else {
                                    $error_message = "<div class='alert alert-danger'><i class='bi bi-exclamation-triangle-fill'></i> " . $upload_result["message"] . "</div>";
                                }
                                header("refresh:1;url=hewan.php");
                            } else {
                                $error_message = "<div class='alert alert-danger'><i class='bi bi-exclamation-triangle-fill'></i> Silakan pilih file gambar!</div>";
                            }
                        }
                        ?>


                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nama" class="form-label"><i class="bi bi-tag"></i> Nama Hewan</label>
                                <input type="text" id="nama" name="nama" placeholder="Masukkan nama hewan" required
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="habitat" class="form-label"><i class="bi bi-geo-alt"></i> Habitat</label>
                                <input type="text" id="habitat" name="habitat" placeholder="Masukkan habitat hewan"
                                    required class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="makanan" class="form-label"><i class="bi bi-egg-fried"></i> Makanan</label>
                                <select name="makanan" class="form-select border-0 bg-light">
                                    <option value="Karnivora">Karnivora</option>
                                    <option value="Herbivora">Herbivora</option>
                                    <option value="Omnivora">Omnivora</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label"><i class="bi bi-card-text"></i>
                                    Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi hewan"
                                    required class="form-control" rows="4"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label"><i class="bi bi-shield"></i> Status
                                    Konservasi</label>
                                <input type="text" id="status" name="status"
                                    placeholder="Contoh: Terancam Punah, Dilindungi, dll" required class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="file_gambar" class="form-label"><i class="bi bi-image"></i> Upload
                                    Gambar</label>
                                <input type="file" id="file_gambar" name="file_gambar" accept="image/*" required
                                    class="form-control">
                                <div class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, GIF. Ukuran
                                    maksimal: 5MB</div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="hewan.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>
                                    Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah
                                    Hewan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4 text-muted">
                    <small>Admin Panel Kebun Binatang Indah &copy; 2023</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>