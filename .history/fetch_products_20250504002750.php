<?php
include 'config.php'; // koneksi database

// Get the search term and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$stockFilter = isset($_GET['stockFilter']) ? $_GET['stockFilter'] : '';

// Start the query
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk";

// Apply search condition
if ($search) {
    $search = mysqli_real_escape_string($conn, $search);
    $query .= " WHERE nama_produk LIKE '%$search%'";
}

// Apply stock filter
if ($stockFilter) {
    if ($stockFilter == '1') {
        $query .= " ORDER BY stok DESC"; // Most stock first
    } else if ($stockFilter == '2') {
        $query .= " ORDER BY stok ASC"; // Least stock first
    }
}

// Execute the query
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
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
        <?php
    }
} else {
    echo '<div class="col-12 text-center"><div class="alert alert-info">Belum ada produk yang ditemukan.</div></div>';
}
?>
