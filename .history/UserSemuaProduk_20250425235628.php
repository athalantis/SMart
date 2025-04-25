<?php
include 'config.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Base query
$query = "SELECT p.produk_id, p.nama_produk, p.harga, p.stok, p.gambar_produk
          FROM produk p
          WHERE p.nama_produk LIKE '%$search%'";

// Apply filter logic based on selection
if ($filter == 'most_purchased') {
    // Assuming you want to order by a related quantity (just an example if you had 'jumlah')
    $query .= " LEFT JOIN pemesanan pm ON p.produk_id = pm.produk_id 
                GROUP BY p.produk_id ORDER BY SUM(pm.jumlah) DESC";
} elseif ($filter == 'most_stock') {
    $query .= " ORDER BY p.stok DESC";  // Sort by stock
} else {
    $query .= " ORDER BY p.produk_id DESC";  // Default to newest
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0):
    while ($row = mysqli_fetch_assoc($result)): ?>
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
    <?php endwhile;
else: ?>
    <div class="col-12 text-center">
        <div class="alert alert-info">Produk tidak ditemukan.</div>
    </div>
<?php endif; ?>
