<?php
include 'config.php';

$search = $_GET['search'] ?? '';
$stockFilter = $_GET['stockFilter'] ?? '';
$purchasedFilter = $_GET['purchasedFilter'] ?? '';

$query = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%'";

if ($stockFilter == '1') {
    $query .= " ORDER BY stok DESC";
} elseif ($stockFilter == '2') {
    $query .= " ORDER BY stok ASC";
}

if ($purchasedFilter == '1') {
    $query .= ", jumlah_terbeli DESC";
} elseif ($purchasedFilter == '2') {
    $query .= ", jumlah_terbeli ASC";
}

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $gambar = !empty($row['gambar_produk']) ? $row['gambar_produk'] : 'default.png';
    $stok = $row['stok'];
    $disabled = $stok <= 0 ? 'disabled' : '';

    echo "<div class='col mb-4'>
        <div class='card'>
            <img src='uploads/{$gambar}' class='card-img-top' alt='{$row['nama_produk']}'>
            <div class='card-body'>
                <h5 class='card-title'>{$row['nama_produk']}</h5>
                <p class='card-text'>Stok: {$stok}</p>
                <button class='btn btn-primary btn-product-details' data-product-id='{$row['produk_id']}'>Lihat Detail</button>
                <button class='btn btn-success btn-pesan-sekarang' data-produk-id='{$row['produk_id']}' {$disabled}>Pesan </button>
            </div>
        </div>
    </div>";
}
?>

<script>
// JavaScript for handling the 'Pesan Sekarang' button click
document.addEventListener('DOMContentLoaded', function () {
    const pesanButtons = document.querySelectorAll('.btn-pesan-sekarang');
    
    pesanButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-produk-id');
            if (productId) {
                // Send the request to process the order
                fetch('fetch_products.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=purchase&product_id=${productId}`
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Show the result from the server
                    location.reload(); // Reload to update the stock and purchased quantity
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Coba lagi.');
                });
            }
        });
    });
});
</script>

<?php
// Handle the purchase request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'purchase') {
    $productId = $_POST['product_id'] ?? '';
    
    if ($productId) {
        // Retrieve product info
        $productQuery = "SELECT * FROM produk WHERE produk_id = '$productId' LIMIT 1";
        $productResult = mysqli_query($conn, $productQuery);
        
        if ($productResult && mysqli_num_rows($productResult) > 0) {
            $product = mysqli_fetch_assoc($productResult);
            $stock = $product['stok'];
            
            if ($stock > 0) {
                // Update the stock and jumlah_terbeli (purchased count)
                $newStock = $stock - 1;
                $newPurchasedCount = $product['jumlah_terbeli'] + 1;
                
                $updateQuery = "UPDATE produk SET stok = $newStock, jumlah_terbeli = $newPurchasedCount WHERE produk_id = '$productId'";
                $updateResult = mysqli_query($conn, $updateQuery);
                
                if ($updateResult) {
                    echo "Pesanan berhasil dilakukan!";
                } else {
                    echo "Gagal memperbarui data produk.";
                }
            } else {
                echo "Stok produk tidak cukup.";
            }
        } else {
            echo "Produk tidak ditemukan.";
        }
    } else {
        echo "ID produk tidak valid.";
    }
}
?>
