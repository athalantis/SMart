<?php
include 'config.php';
session_start();

// ===== HANDLE ORDER SUBMISSION VIA AJAX =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => ''];

    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'User not logged in.';
        echo json_encode($response);
        exit;
    }

    $produk_id = intval($_POST['produk_id']);
    $jumlah_pemesanan = intval($_POST['jumlah_pemesanan']);
    $user_id = intval($_SESSION['user_id']);
    $tanggal_pemesanan = date('Y-m-d');

    // Check stock
    $stock_stmt = mysqli_prepare($conn, "SELECT stok FROM produk WHERE produk_id = ?");
    mysqli_stmt_bind_param($stock_stmt, 'i', $produk_id);
    mysqli_stmt_execute($stock_stmt);
    $stock_result = mysqli_stmt_get_result($stock_stmt);
    $stock_row = mysqli_fetch_assoc($stock_result);

    if (!$stock_row || $stock_row['stok'] < $jumlah_pemesanan) {
        $response['message'] = 'Insufficient stock or product not found.';
        echo json_encode($response);
        exit;
    }

    // Place order
    $insert_stmt = mysqli_prepare($conn, "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan) VALUES (?, ?, ?, ?, 'pending')");
    mysqli_stmt_bind_param($insert_stmt, 'iisi', $produk_id, $user_id, $tanggal_pemesanan, $jumlah_pemesanan);

    if (mysqli_stmt_execute($insert_stmt)) {
        $update_stmt = mysqli_prepare($conn, "UPDATE produk SET stok = stok - ? WHERE produk_id = ?");
        mysqli_stmt_bind_param($update_stmt, 'ii', $jumlah_pemesanan, $produk_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        $response['success'] = true;
        $response['message'] = 'Order placed successfully!';
    } else {
        $response['message'] = 'Failed to place order: ' . mysqli_error($conn);
    }

    mysqli_stmt_close($stock_stmt);
    mysqli_stmt_close($insert_stmt);
    echo json_encode($response);
    exit;
}

// ===== AUTH CHECK =====
if (!isset($_SESSION['user_id'])) {
    echo '<div class="col-12 text-center"><div class="alert alert-danger">Please log in to view products.</div></div>';
    exit;
}

// ===== FETCH PRODUCTS =====
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$stockFilter = $_GET['stockFilter'] ?? '';
$purchasedFilter = $_GET['purchasedFilter'] ?? '';

$searchClause = $search ? "WHERE nama_produk LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'" : '';

if ($purchasedFilter) {
    $query = "
        SELECT p.produk_id, p.nama_produk, p.harga, p.stok, p.gambar_produk, COUNT(pm.pemesanan_id) as purchase_count
        FROM produk p
        LEFT JOIN pemesanan pm ON p.produk_id = pm.produk_id
        " . ($search ? "WHERE p.nama_produk LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'" : "") . "
        GROUP BY p.produk_id
    ";
    if ($purchasedFilter == '1') {
        $query .= " ORDER BY purchase_count DESC";
    } elseif ($purchasedFilter == '2') {
        $query .= " ORDER BY purchase_count ASC";
    }
} else {
    $query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk $searchClause";

    if ($stockFilter == '1') {
        $query .= " ORDER BY stok DESC";
    } elseif ($stockFilter == '2') {
        $query .= " ORDER BY stok ASC";
    }
}

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <div class="row g-4">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php $stok = (int) $row['stok']; ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-img-container" style="height: 200px; overflow: hidden;">
                            <img src="./product_picture/<?= htmlspecialchars($row['gambar_produk']) ?>" 
                                 class="card-img-top img-fluid h-100 w-100 object-fit-cover" 
                                 alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <small class="text-muted">Toko Saya</small>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                                <p class="card-price text-success fw-bold mb-1">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-<?= $stok > 0 ? 'success' : 'danger' ?>">
                                        <?= $stok > 0 ? 'In Stock' : 'Out of Stock' ?>
                                    </span>
                                    <small class="text-muted">Stock: <?= $stok ?></small>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="text-warning">★ ★ ★ ★ ★</span>
                                    <small class="text-muted ms-1">4.9</small>
                                </div>
                            </div>
                          
                        </div>
                    </div>
                </div>

                <!-- gap -->

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

            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No products found.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Product</h5>
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
                        <label for="jumlah_pemesanan" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="jumlah_pemesanan" name="jumlah_pemesanan" min="1" value="1" required>
                        <div class="form-text">Available stock: <span id="stok_tersedia" class="fw-bold">0</span></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitOrder()">Confirm Order</button>
            </div>
        </div>
    </div>
</div>

<script>
    let maxStock = 0;

    function setOrderModal(produk_id, stok, nama_produk) {
        $('#produk_id').val(produk_id);
        $('#nama_produk').val(nama_produk);
        $('#stok_tersedia').text(stok);
        $('#jumlah_pemesanan').attr({
            'max': stok,
            'value': 1
        }).val(1);
        maxStock = stok;
    }

    function submitOrder() {
        const produk_id = $('#produk_id').val();
        const jumlah_pemesanan = parseInt($('#jumlah_pemesanan').val());

        if (!jumlah_pemesanan || jumlah_pemesanan <= 0 || jumlah_pemesanan > maxStock) {
            alert('Please enter a valid quantity within available stock.');
            return;
        }

        $.ajax({
            url: 'fetch_products.php',
            type: 'POST',
            data: {
                action: 'place_order',
                produk_id: produk_id,
                jumlah_pemesanan: jumlah_pemesanan
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#orderModal').modal('hide');
                    setTimeout(() => location.reload(), 500);
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

<style>
    .card {
        border-radius: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-img-container {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-color: #f8f9fa;
    }
    
    .object-fit-cover {
        object-fit: cover;
        object-position: center;
    }
    
    .modal-content {
        border-radius: 10px;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
    }
    
    .btn-outline-secondary {
        border-radius: 8px;
        padding: 8px 16px;
    }
</style>