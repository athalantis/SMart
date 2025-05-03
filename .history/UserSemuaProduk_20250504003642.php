<?php
session_start();
include 'UserLayouts/header.php';
include 'config.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle AJAX request for fetching products or processing orders
if (isset($_GET['action']) && $_GET['action'] === 'fetch_products') {
    // Get the search term and filters
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $stockFilter = isset($_GET['stockFilter']) ? $_GET['stockFilter'] : '';
    $purchasedFilter = isset($_GET['purchasedFilter']) ? $_GET['purchasedFilter'] : '';

    // Start the query for products
    $query = "SELECT p.produk_id, p.nama_produk, p.harga, p.stok, p.gambar_produk, 
                     COALESCE(SUM(pm.jumlah_pemesanan), 0) as total_purchased 
              FROM produk p 
              LEFT JOIN pemesanan pm ON p.produk_id = pm.produk_id";

    // Apply search condition
    if ($search) {
        $search = mysqli_real_escape_string($conn, $search);
        $query .= " WHERE p.nama_produk LIKE '%$search%'";
    }

    $query .= " GROUP BY p.produk_id";

    // Apply stock filter
    if ($stockFilter) {
        if ($stockFilter == '1') {
            $query .= " ORDER BY p.stok DESC";
        } else if ($stockFilter == '2') {
            $query .= " ORDER BY p.stok ASC";
        }
    }

    // Apply purchased filter
    if ($purchasedFilter) {
        if ($stockFilter) {
            $query .= ", ";
        } else {
            $query .= " ORDER BY ";
        }
        if ($purchasedFilter == '1') {
            $query .= "total_purchased DESC";
        } else if ($purchasedFilter == '2') {
            $query .= "total_purchased ASC";
        }
    }

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Generate HTML for products
    $output = '';
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= '
            <div class="col-sm-5 col-md-3 me-3 mb-4">
                <div class="card h-100 hovered-card">
                    <a href="#">
                        <img class="card-img-top" 
                             src="./product_picture/' . htmlspecialchars($row['gambar_produk']) . '" 
                             alt="' . htmlspecialchars($row['nama_produk']) . '" 
                             style="height: 200px; object-fit: cover; border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;" />
                        <p class="card-title text-center mt-2">Toko Saya</p>
                        <div class="card-body">
                            <p class="card-text fw-bold">' . htmlspecialchars($row['nama_produk']) . '</p>
                            <p class="card-price text-success">Rp. ' . number_format($row['harga'], 0, ',', '.') . '</p>
                            <p class="card-location">🚚 Tersedia</p>
                            <p class="card-rating">⭐ 4.9 | Stok: ' . intval($row['stok']) . ' tersedia</p>
                            <button type="button" 
                                    class="btn rounded-pill btn-primary w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#orderModal"
                                    onclick="setOrderModal(' . $row['produk_id'] . ', ' . $row['stok'] . ', \'' . htmlspecialchars($row['nama_produk']) . '\', ' . $row['harga'] . ')">
                                Beli Sekarang
                            </button>
                        </div>
                    </a>
                </div>
            </div>';
        }
    } else {
        $output = '<div class="col-12 text-center"><div class="alert alert-info">Belum ada produk yang ditemukan.</div></div>';
    }

    echo $output;
    exit();
}

// Handle AJAX request for placing an order
if (isset($_POST['action']) && $_POST['action'] === 'place_order') {
    header('Content-Type: application/json');

    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['produk_id']) || !isset($data['jumlah'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit();
    }

    // Sanitize and validate input
    $produk_id = (int)$data['produk_id'];
    $jumlah = (int)$data['jumlah'];
    $user_id = (int)$_SESSION['user_id'];

    if ($jumlah <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
        exit();
    }

    // Check product existence and stock
    $query = "SELECT stok FROM produk WHERE produk_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $produk_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    $stok = (int)$row['stok'];

    if ($jumlah > $stok) {
        echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
        exit();
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Update product stock
        $new_stock = $stok - $jumlah;
        $update_query = "UPDATE produk SET stok = ? WHERE produk_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, 'ii', $new_stock, $produk_id);
        mysqli_stmt_execute($update_stmt);

        // Insert order into pemesanan
        $order_query = "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan) VALUES (?, ?, CURDATE(), ?, 'pending')";
        $order_stmt = mysqli_prepare($conn, $order_query);
        mysqli_stmt_bind_param($order_stmt, 'iii', $produk_id, $user_id, $jumlah);
        mysqli_stmt_execute($order_stmt);

        // Commit transaction
        mysqli_commit($conn);

        echo json_encode(['success' => true, 'message' => 'Order placed successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => 'Error processing order: ' . $e->getMessage()]);
    }

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($update_stmt);
    mysqli_stmt_close($order_stmt);
    mysqli_close($conn);
    exit();
}
?>

<div style="padding-top: 100px;">
    <div class="container mt-5">
        <h3 class="mb-4 text-center">Semua Produk Kami</h3>

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <input type="text" id="search-input" class="form-control" placeholder="Cari produk...">
            </div>
            <div class="col-md-3">
                <select id="filter-stock" class="form-control">
                    <option value="">Filter by Stock</option>
                    <option value="1">Most Stock</option>
                    <option value="2">Least Stock</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filter-purchased" class="form-control">
                    <option value="">Filter by Most Purchased</option>
                    <option value="1">Most Purchased</option>
                    <option value="2">Least Purchased</option>
                </select>
            </div>
        </div>

        <!-- Products List -->
        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center" id="product-list">
            <!-- Product list will be loaded here via AJAX -->
        </div>
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

<?php include("UserLayouts/footer.php"); ?>

<!-- Bootstrap and jQuery for Modal and AJAX -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let maxStock = 0;

    $(document).ready(function () {
        // Function to fetch products based on search term and filters
        function fetchProducts() {
            var search = $('#search-input').val();
            var stockFilter = $('#filter-stock').val();
            var purchasedFilter = $('#filter-purchased').val();

            $.ajax({
                url: 'UserSemuaProduk.php',
                type: 'GET',
                data: {
                    action: 'fetch_products',
                    search: search,
                    stockFilter: stockFilter,
                    purchasedFilter: purchasedFilter
                },
                success: function(response) {
                    $('#product-list').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products:', error);
                }
            });
        }

        // Trigger fetch when the search input is changed
        $('#search-input').on('keyup', function () {
            fetchProducts();
        });

        // Trigger fetch when stock filter is changed
        $('#filter-stock').on('change', function () {
            fetchProducts();
        });

        // Trigger fetch when purchased filter is changed
        $('#filter-purchased').on('change', function () {
            fetchProducts();
        });

        // Initial load
        fetchProducts();
    });

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
            url: 'UserSemuaProduk.php',
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            headers: { 'X-Action': 'place_order' }, // Custom header to identify action
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