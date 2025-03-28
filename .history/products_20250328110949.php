<?php
include ".includes/header.php";
$title = "Admin's Dashboard";
include ".includes/toast_notification.php";
include "config.php"; // Koneksi database

$query = "SELECT * FROM produk";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2>Daftar Produk</h2>
    <a href="product_create.php" class="btn btn-primary mb-3">Tambah Produk</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $row['produk_id'] ?></td>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td>
                        <img src="product_picture/<?= htmlspecialchars($row['gambar_produk']) ?>" width="80" alt="Produk">
                    </td>
                    <td>
                        <a href="product_edit.php?id=<?= $row['produk_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="product_delete.php?id=<?= $row['produk_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include ".includes/footer.php"; ?>
