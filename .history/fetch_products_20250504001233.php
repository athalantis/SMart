<?php
include 'config.php'; // koneksi database

// Fetch the products based on search and filters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$stockFilter = isset($_GET['stockFilter']) ? $_GET['stockFilter'] : '';
$purchasedFilter = isset($_GET['purchasedFilter']) ? $_GET['purchasedFilter'] : '';

// Build the query
$query = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%'";

if ($stockFilter == '1') {
    $query .= " ORDER BY stok DESC"; // Most stock
} elseif ($stockFilter == '2') {
    $query .= " ORDER BY stok ASC"; // Least stock
}

if ($purchasedFilter == '1') {
    $query .= " ORDER BY recommendations DESC"; // Most purchased (assuming recommendations means most popular)
} elseif ($purchasedFilter == '2') {
    $query .= " ORDER BY recommendations ASC"; // Least purchased
}

// Execute the query
$result = mysqli_query($conn, $query);

// Loop through the products and render them
while ($row = mysqli_fetch_assoc($result)) {
    echo '
    <div class="col">
        <div class="card">
            <img src="' . $row['gambar_produk'] . '" class="card-img-top" alt="' . $row['nama_produk'] . '">
            <div class="card-body">
                <h5 class="card-title">' . $row['nama_produk'] . '</h5>
                <p class="card-text">Price: Rp ' . number_format($row['harga'], 0, ',', '.') . '</p>
                <p class="card-text">Stock: ' . $row['stok'] . '</p>
                <button class="btn btn-primary btn-pesan-sekarang" data-produk-id="' . $row['produk_id'] . '">Pesan Sekarang</button>
            </div>
        </div>
    </div>';
}
?>
