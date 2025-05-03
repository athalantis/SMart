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
                            <button type="button"
                                    class="btn btn-primary mt-auto <?= $stok <= 0 ? 'disabled' : '' ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#orderModal"
                                    onclick="<?= $stok > 0 ? "setOrderModal({$row['produk_id']}, {$stok}, '" . addslashes(htmlspecialchars($row['nama_produk'])) . "')" : '' ?>">
                                Buy Now
                            </button>
                        </div>
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
    /* Base Styles */
    .card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        overflow: hidden;
        background: #ffffff;
    }
    
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    }
    
    /* Card Image Container */
    .card-img-container {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        background-color: #f8f9fa;
        position: relative;
        overflow: hidden;
    }
    
    .card-img-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.02) 0%, rgba(0,0,0,0) 100%);
        z-index: 1;
    }
    
    .object-fit-cover {
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
    }
    
    .card:hover .object-fit-cover {
        transform: scale(1.05);
    }
    
    /* Card Body */
    .card-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        background: linear-gradient(to bottom, #ffffff 0%, #f9f9f9 100%);
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-price {
        font-size: 1.2rem;
        color: #27ae60;
    }
    
    /* Badge Styles */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: 8px;
    }
    
    /* Rating Styles */
    .text-warning {
        color: #f39c12 !important;
        letter-spacing: 1px;
    }
    
    /* Button Styles */
    .btn-primary {
        background-color: #3498db;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
    }
    
    .btn-primary:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }
    
    .btn-primary:active {
        transform: translateY(0);
    }
    
    .btn-primary.disabled {
        background-color: #95a5a6;
        cursor: not-allowed;
    }
    
    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .modal-header {
        border-bottom: 1px solid #e0e0e0;
        padding: 1.25rem 1.5rem;
    }
    
    .modal-title {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e0e0e0;
        padding: 1rem 1.5rem;
    }
    
    .btn-outline-secondary {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        border: 1px solid #bdc3c7;
        color: #7f8c8d;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #95a5a6;
        color: #34495e;
    }
    
    /* Form Styles */
    .form-control {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        border: 1px solid #dfe6e9;
    }
    
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
    
    .form-label {
        font-weight: 500;
        color: #34495e;
        margin-bottom: 0.5rem;
    }
    
    .form-text {
        color: #7f8c8d;
        font-size: 0.85rem;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .card {
            margin-bottom: 1.5rem;
        }
        
        .modal-dialog {
            margin: 1rem;
        }
    }
    
    /* Animation for Empty State */
    .alert-info {
        border-radius: 10px;
        background-color: #e8f4fc;
        border-color: #b8e0fa;
        color: #3498db;
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>