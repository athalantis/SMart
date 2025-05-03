<?php
session_start();
include 'config.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

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
        $query .= " ORDER BY stok DESC";
    } else if ($stockFilter == '2') {
        $query .= " ORDER BY stok ASC";
    }
}

// Execute the query
$result = mysqli_query($conn, $query);
?>

    <div class="container mt-4">
        <div class="row">
            <?php
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
                                    data-bs-toggle="modal" 
                                    data-bs-target="#orderModal"
                                    onclick="setOrderModal(<?php echo $row['produk_id']; ?>, <?php echo $row['stok']; ?>, '<?php echo htmlspecialchars($row['nama_produk']); ?>', <?php echo $row['harga']; ?>)">
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
        </div>
    </div>

    <!-- Order Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="orderForm">
                        <input type="hidden" id="produk_id" name="produk_id">
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="nama_produk" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Price</label>
                            <input type="text" class="form-control" id="harga" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                            <small class="form-text text-muted">Available stock: <span id="stok_tersedia"></span></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitOrder()">Place Order</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let maxStock = 0;

    function setOrderModal(produk_id, stok, nama_produk, harga) {
        document.getElementById('produk_id').value = produk_id;
        document.getElementById('nama_produk').value = nama_produk;
        document.getElementById('harga').value = 'Rp. ' + harga.toLocaleString('id-ID');
        document.getElementById('stok_tersedia').textContent = stok;
        document.getElementById('jumlah').max = stok;
        maxStock = stok;
    }

    function submitOrder() {
        const form = document.getElementById('orderForm');
        const jumlah = parseInt(document.getElementById('jumlah').value);
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (jumlah > maxStock) {
            alert('Quantity exceeds available stock!');
            return;
        }

        const formData = {
            produk_id: document.getElementById('produk_id').value,
            jumlah: jumlah
        };

        $.ajax({
            url: 'process_order.php',
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    alert('Order placed successfully!');
                    $('#orderModal').modal('hide');
                    form.reset();
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error processing order: ' + error);
            }
        });
    }
    </script>
