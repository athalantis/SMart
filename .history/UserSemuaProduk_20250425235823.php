<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database

// Default query for products with the JOIN to count total purchased products
$query = "SELECT p.produk_id, p.nama_produk, p.harga, p.stok, p.gambar_produk, 
                 IFNULL(SUM(pm.jumlah), 0) as total_purchased 
          FROM produk p 
          LEFT JOIN pemesanan pm ON p.produk_id = pm.produk_id 
          GROUP BY p.produk_id";

// Check for AJAX search and filter requests
$search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
$min_stock = isset($_POST['min_stock']) ? intval($_POST['min_stock']) : 0;
$max_stock = isset($_POST['max_stock']) ? intval($_POST['max_stock']) : PHP_INT_MAX;

if ($search) {
    $query .= " AND p.nama_produk LIKE '%$search%'";
}

if ($min_stock > 0) {
    $query .= " AND p.stok >= $min_stock";
}

if ($max_stock < PHP_INT_MAX) {
    $query .= " AND p.stok <= $max_stock";
}

$query .= " ORDER BY total_purchased DESC"; // Ordering by most purchased

$result = mysqli_query($conn, $query);
?>

<!-- HTML Structure -->
<div style="padding-top: 100px; margin-bottom: 250px;">
    <div class="container mt-5">
        <h3 class="mb-4 text-center">semua produk kami</h3>

        <!-- Search and Filter Bar -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-4">
                <input type="text" id="search-input" class="form-control" placeholder="Cari produk..." />
            </div>
            <div class="col-md-3">
                <input type="number" id="min-stock" class="form-control" placeholder="Min Stok" />
            </div>
            <div class="col-md-3">
                <input type="number" id="max-stock" class="form-control" placeholder="Max Stok" />
            </div>
        </div>

        <!-- Product List -->
        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center" id="product-list">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-sm-5 col-md-3 me-3 mb-4">
                        <div class="card h-100 hovered-card">
                            <a href="#">
                                <img class="card-img-top" 
                                     src="./product_picture/<?php echo htmlspecialchars($row['gambar_produk']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" 
                                     style="height: 200px; object-fit: cover; border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;" />
                                <p class="card-title text-center mt-2">Toko Saya</p>
                                <div class="card-body">
                                    <p class="card-text fw-bold"><?php echo htmlspecialchars($row['nama_produk']); ?></p>
                                    <p class="card-price text-success">Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                    <p class="card-location">🚚 Tersedia</p>
                                    <p class="card-rating">⭐ 4.9 | Stok: <?php echo intval($row['stok']); ?> tersedia</p>
                                    <button type="button" 
                                            class="btn rounded-pill btn-primary w-100" 
                                            onclick="openOrderModal(<?php echo $row['produk_id']; ?>, <?php echo $row['stok']; ?>)">
                                        Beli Sekarang
                                    </button>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">Belum ada produk rekomendasi tersedia.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("UserLayouts/footer.php"); ?>
