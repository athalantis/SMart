<?php
include "config.php";
include "includes/header.php";

$query = "SELECT * FROM produk ORDER BY produk_id DESC";
$result = $conn->query($query);
?>

<div class="container mt-4">
    <h2>Daftar Produk</h2>
    <a href="product_create.php" class="btn btn-primary mb-3">Tambah Produk</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['produk_id'] ?></td>
                    <td><img src="product_picture/<?= $row['gambar_produk'] ?>" width="80" height="80"></td>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td>
                        <a href="product_edit.php?id=<?= $row['produk_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="product_delete.php?id=<?= $row['produk_id'] ?>" onclick="return confirm('Yakin ingin menghapus?');" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "includes/footer.php"; ?>
