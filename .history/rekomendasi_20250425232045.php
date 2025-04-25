<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database

// Ambil hanya produk yang direkomendasikan (recommendations = 1)
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk WHERE recommendations = 1";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <h3 class="mb-4">Produk Rekomendasi</h3>
    <div class="row">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="uploads/<?= htmlspecialchars($row['gambar_produk']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nama_produk']) ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                            <p class="card-text">Harga: Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                            <p class="card-text">Stok: <?= $row['stok'] ?></p>
                            <a href="detail_produk.php?id=<?= $row['produk_id'] ?>" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">Belum ada produk rekomendasi.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include(".includes/footer.php"); ?>
