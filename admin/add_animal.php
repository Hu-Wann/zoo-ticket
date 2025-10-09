<?php
include("../database/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = $_POST['nama'];
    $habitat   = $_POST['habitat'];
    $makanan   = $_POST['makanan'];
    $deskripsi = $_POST['deskripsi'];
    $status    = $_POST['status'];
    $gambar    = $_POST['gambar'];

    $sql = "INSERT INTO animals (nama, habitat, makanan, deskripsi, status_konservasi, emoji, gambar) 
            VALUES ('$nama', '$habitat', '$makanan', '$deskripsi', '$status', '$emoji', '$gambar')";

    if ($koneksi->query($sql) === TRUE) {
        echo "<div style='padding:10px;background:#d4f8d4;color:#198754;'>✅ Hewan berhasil ditambahkan!</div>";
    } else {
        echo "<div style='padding:10px;background:#f8d7da;color:#842029;'>❌ Error: " . $koneksi->error . "</div>";
    }
}
?>

<form method="POST" style="max-width:400px;margin:auto;margin-top:30px;">
    <h3>Tambah Hewan</h3>
    <input type="text" name="nama" placeholder="Nama Hewan" required class="form-control mb-2">
    <input type="text" name="habitat" placeholder="Habitat" required class="form-control mb-2">
    <input type="text" name="makanan" placeholder="Makanan" required class="form-control mb-2">
    <textarea name="deskripsi" placeholder="Deskripsi" required class="form-control mb-2"></textarea>
    <input type="text" name="status" placeholder="Status Konservasi" required class="form-control mb-2">
    <input type="text" name="emoji" placeholder="Emoji (misal 🦁)" requir
ed class="form-control mb-2">
    <input type="text" name="gambar" placeholder="URL Gambar" required class="form-control mb-2">
    <button type="submit" class="btn btn-primary w-100">Tambah Hewan</button>
</form>         