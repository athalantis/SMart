<?php
include 'config.php';

// Get search term and filters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$stockFilter = isset($_GET['stockFilter']) ? $_GET['stockFilter'] : '';
$purchasedFilter = isset($_GET['purchasedFilter']) ? $_GET['purchasedFilter'] : '';

// Base query
$query = "SELECT p.produk_id, p.nama_produk, p.harga, p.stok, p.gambar_produk, 
          COUNT(pm.pemesanan_id) as total_ordered
          FROM produk p
          LEFT JOIN pemesanan pm ON p.produk_id = pm.produk_id";

// Add search condition if search term is provided
if (!empty($search)) {
    $query .= " WHERE p.nama_produk LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
} else {
    $query .= " WHERE 1=1";
}

// Group by product
$query .= " GROUP BY p.produk_id";

// Add sorting based on filters
if ($stockFilter == '1') {
    $query .= " ORDER BY p.stok DESC";
} elseif ($stockFilter == '2') {
    $query .= " ORDER BY p.stok ASC";
} elseif ($purchasedFilter == '1') {
    $query .= " ORDER BY total_ordered DESC";
} elseif ($purchasedFilter == '2') {
    $query .= " ORDER BY total_ordered ASC";
} else {
    $query .= " ORDER BY p.nama_produk ASC";
}

$result = mysqli_query($conn, $query);

// Check if there are products
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="col">
            <div class="card h-100">
                <?php if (!empty($row['gambar_produk'])): ?>
                    <img src="<?php echo $row['gambar_produk']; ?>" class="card-img-top" alt="<?php echo $row['nama_produk']; ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <img src="uploads/default-product.jpg" class="card-img-top" alt="Default Product Image" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['nama_produk']; ?></h5>
                    <p class="card-text">
                        <strong>Harga:</strong> Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?><br>
                        <strong>Stok:</strong> <?php echo $row['stok']; ?>
                    </p>
                    
                    <?php if ($row['stok'] > 0): ?>
                        <button type="button" class="btn btn-primary order-btn" 
                            data-id="<?php echo $row['produk_id']; ?>"
                            data-stock="<?php echo $row['stok']; ?>">
                            Pesan Sekarang
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" disabled>Stok Habis</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<div class="col-12 text-center"><p>Tidak ada produk yang ditemukan.</p></div>';
}

mysqli_close($conn);
?>

<!-- Tambahkan script AJAX di bawah -->
<script>
document.querySelectorAll('.order-btn').forEach(button => {
    button.addEventListener('click', function () {
        const produkId = this.dataset.id;
        const userId = 1; // Ganti dengan session user_id jika ada (misal: <?php echo $_SESSION['user_id']; ?>)
        const jumlah = 1;

        fetch('insert_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `produk_id=${produkId}&user_id=${userId}&jumlah=${jumlah}`
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => {
            alert('Terjadi kesalahan saat memesan.');
            console.error(error);
        });
    });
});
</script>
