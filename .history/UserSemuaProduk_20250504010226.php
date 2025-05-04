<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database
session_start(); // Start session for user_id

// Handle AJAX order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    header('Content-Type: application/json'); // Ensure JSON response
    $response = ['success' => false, 'message' => ''];

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'User not logged in.';
        echo json_encode($response);
        exit;
    }

    $produk_id = isset($_POST['produk_id']) ? intval($_POST['produk_id']) : 0;
    $jumlah_pemesanan = isset($_POST['jumlah_pemesanan']) ? intval($_POST['jumlah_pemesanan']) : 0;
    $user_id = intval($_SESSION['user_id']);
    $tanggal_pemesanan = date('Y-m-d');

    if ($produk_id <= 0 || $jumlah_pemesanan <= 0) {
        $response['message'] = 'Invalid product ID or quantity.';
        echo json_encode($response);
        exit;
    }

    // Verify stock with prepared statement
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

    // Insert into pemesanan with prepared statement
    $insert_stmt = mysqli_prepare($conn, "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan) VALUES (?, ?, ?, ?, 'pending')");
    mysqli_stmt_bind_param($insert_stmt, 'iisi', $produk_id, $user_id, $tanggal_pemesanan, $jumlah_pemesanan);

    if (mysqli_stmt_execute($insert_stmt)) {
        // Update stock with prepared statement
        $update_stmt = mysqli_prepare($conn, "UPDATE produk SET stok = stok - ? WHERE produk_id = ?");
        mysqli_stmt_bind_param($update_stmt, 'ii', $jumlah_pemesanan, $produk_id);
        mysqli_stmt_execute($update_stmt);

        $response['success'] = true;
    } else {
        $response['message'] = 'Failed to place order: ' . mysqli_error($conn);
    }

    mysqli_stmt_close($stock_stmt);
    mysqli_stmt_close($insert_stmt);
    mysqli_stmt_close($update_stmt);

    echo json_encode($response);
    exit;
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
                        <label for="jumlah_pemesanan" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="jumlah_pemesanan" name="jumlah_pemesanan" min="1" required>
                        <small class="form-text text-muted">Available stock: <span id="stok_tersedia"></span></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitOrder()">Order Now</button>
            </div>
        </div>
    </div>
</div>

<?php include("UserLayouts/footer.php"); ?>

<!-- jQuery, Bootstrap JS, and Custom Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to fetch products based on search term and filters
        function fetchProducts() {
            var search = $('#search-input').val();
            var stockFilter = $('#filter-stock').val();
            var purchasedFilter = $('#filter-purchased').val();

            $.ajax({
                url: 'fetch_products.php',
                type: 'GET',
                data: {
                    search: search,
                    stockFilter: stockFilter,
                    purchasedFilter: purchasedFilter
                },
                success: function(response) {
                    $('#product-list').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products:', status, error);
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

    let maxStock = 0;

    function setOrderModal(produk_id, stok, nama_produk) {
        document.getElementById('produk_id').value = produk_id;
        document.getElementById('nama_produk').value = nama_produk;
        document.getElementById('stok_tersedia').textContent = stok;
        document.getElementById('jumlah_pemesanan').max = stok;
        maxStock = stok;
    }

    function submitOrder() {
        const produk_id = document.getElementById('produk_id').value;
        const jumlah_pemesanan = document.getElementById('jumlah_pemesanan').value;

        if (!jumlah_pemesanan || jumlah_pemesanan <= 0 || jumlah_pemesanan > maxStock) {
            alert('Please enter a valid quantity within available stock.');
            return;
        }

        $.ajax({
            url: 'UserSemuaProduk.php',
            type: 'POST',
            data: {
                action: 'place_order',
                produk_id: produk_id,
                jumlah_pemesanan: jumlah_pemesanan
            },
            success: function(response) {
                let res;
                try {
                    res = JSON.parse(response);
                } catch (e) {
                    alert('Invalid response from server: ' + response);
                    return;
                }
                if (res.success) {
                    alert('Order placed successfully!');
                    $('#orderModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX Error: ' + status + ' - ' + error);
            }
        });
    }
</script>