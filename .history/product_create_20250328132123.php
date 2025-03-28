<?php
include ".includes/header.php";
$title = "Tambah Produk";
include ".includes/toast_notification.php";
include "config.php"; // Koneksi database

// Ambil daftar distributor dari database
$distributor_query = "SELECT distributor_id, nama FROM distributor";
$distributor_result = $conn->query($distributor_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $distributor_id = $_POST['distributor_id'];
    $gambar = $_FILES['gambar_produk']['name'];
    $gambar_tmp = $_FILES['gambar_produk']['tmp_name'];
    
    $upload_dir = "product_picture/";
    $gambar_path = time() . "_" . basename($gambar);
    
    if (move_uploaded_file($gambar_tmp, $upload_dir . $gambar_path)) {
        $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, stok, gambar_produk, distributor_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siisi", $nama_produk, $harga, $stok, $gambar_path, $distributor_id);

        if ($stmt->execute()) {
            redirect("products.php");
        } else {
            echo "<div class='alert alert-danger'>Gagal menambahkan produk.</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Gagal mengupload gambar.</div>";
    }
}
?>

<div class="container mt-4">
    <h2>Tambah Produk</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Distributor</label>
            <select name="distributor_id" class="form-control" required>
                <option value="" disabled selected>Pilih Distributor</option>
                <?php while ($row = $distributor_result->fetch_assoc()): ?>
                    <option value="<?= $row['distributor_id']; ?>"><?= htmlspecialchars($row['nama']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Gambar Produk</label>
            <input type="file" name="gambar_produk" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="products.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include ".includes/footer.php"; ?>

<?php
function redirect($url) {
    echo "<script>window.location.href='$url';</script>";
    exit;
}
?>
