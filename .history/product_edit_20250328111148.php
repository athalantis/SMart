<?php
include ".includes/header.php";
$title = "Edit Produk";
include ".includes/toast_notification.php";
include "config.php"; // Koneksi database

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID tidak valid.</div>";
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM produk WHERE produk_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$stmt->close();

if (!$produk) {
    echo "<div class='alert alert-danger'>Produk tidak ditemukan.</div>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    
    if (!empty($_FILES['gambar_produk']['name'])) {
        $gambar = $_FILES['gambar_produk']['name'];
        $gambar_tmp = $_FILES['gambar_produk']['tmp_name'];
        $upload_dir = "product_picture/";
        $gambar_path = time() . "_" . basename($gambar);
        
        if (move_uploaded_file($gambar_tmp, $upload_dir . $gambar_path)) {
            unlink($upload_dir . $produk['gambar_produk']);
            $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, stok=?, gambar_produk=? WHERE produk_id=?");
            $stmt->bind_param("siisi", $nama_produk, $harga, $stok, $gambar_path, $id);
        }
    } else {
        $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, stok=? WHERE produk_id=?");
        $stmt->bind_param("siii", $nama_produk, $harga, $stok, $id);
    }

    if ($stmt->execute()) {
        return redirect("products.php");
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui produk.</div>";
    }
    $stmt->close();
}
?>

<div class="container mt-4">
    <h2>Edit Produk</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $produk['harga'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $produk['stok'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Gambar Produk (Biarkan kosong jika tidak ingin diubah)</label>
            <input type="file" name="gambar_produk" class="form-control" accept="image/*">
            <img src="product_picture/<?= htmlspecialchars($produk['gambar_produk']) ?>" width="100">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="products.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include ".includes/footer.php"; ?>
