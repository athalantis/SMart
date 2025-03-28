<?php
include "config.php";
include "includes/header.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID tidak valid.</div>";
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM produk WHERE produk_id = $id");
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if (!empty($_FILES['gambar_produk']['name'])) {
        $gambar_produk = $_FILES['gambar_produk']['name'];
        move_uploaded_file($_FILES['gambar_produk']['tmp_name'], "product_picture/$gambar_produk");
        $query = "UPDATE produk SET nama_produk='$nama_produk', harga='$harga', stok='$stok', gambar_produk='$gambar_produk' WHERE produk_id=$id";
    } else {
        $query = "UPDATE produk SET nama_produk='$nama_produk', harga='$harga', stok='$stok' WHERE produk_id=$id";
    }

    if ($conn->query($query)) {
        return redirect("products.php");
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui produk.</div>";
    }
}
?>

<div class="container mt-4">
    <h2>Edit Produk</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($product['nama_produk']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $product['harga'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $product['stok'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Gambar Produk (Kosongkan jika tidak ingin mengganti)</label>
            <input type="file" name="gambar_produk" class="form-control">
            <img src="product_picture/<?= $product['gambar_produk'] ?>" width="100">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="products.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include "includes/footer.php"; ?>
