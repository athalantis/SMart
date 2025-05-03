<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database

// Check if it's an AJAX request for product details
if (isset($_GET['get_product_details']) && isset($_GET['produk_id'])) {
    $produk_id = mysqli_real_escape_string($conn, $_GET['produk_id']);
    
    // Query to get product details
    $query = "SELECT p.*, COALESCE(SUM(pm.jumlah_pemesanan), 0) AS total_purchased 
              FROM produk p 
              LEFT JOIN pemesanan pm ON p.produk_id = pm.produk_id 
              WHERE p.produk_id = '$produk_id'
              GROUP BY p.produk_id";
    
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nama_produk = $row['nama_produk'];
        $harga = $row['harga'];
        $stok = $row['stok'];
        $deskripsi = isset($row['deskripsi']) ? $row['deskripsi'] : 'Tidak ada deskripsi tersedia';
        $gambar = !empty($row['gambar_produk']) ? $row['gambar_produk'] : 'default_product.jpg';
        $total_purchased = $row['total_purchased'];
        
        // Output HTML content
        ?>
        <div class="text-center mb-4">
            <img src="assets/img/products/<?php echo $gambar; ?>" alt="<?php echo $nama_produk; ?>" class="img-fluid" style="max-height: 300px;">
        </div>
        <div class="product-info">
            <h4><?php echo $nama_produk; ?></h4>
            <p class="fw-bold">Rp <?php echo number_format($harga, 0, ',', '.'); ?></p>
            <div class="d-flex justify-content-between mb-3">
                <span>Stok: <?php echo $stok; ?></span>
                <span>Terjual: <?php echo $total_purchased; ?></span>
            </div>
            <div class="mb-3">
                <h5>Deskripsi Produk</h5>
                <p><?php echo nl2br($deskripsi); ?></p>
            </div>
        </div>
        <script>
            // Set product data for order button in modal
            document.getElementById('modal-order-btn').setAttribute('data-id', '<?php echo $produk_id; ?>');
            document.getElementById('modal-order-btn').setAttribute('data-nama', '<?php echo $nama_produk; ?>');
            document.getElementById('modal-order-btn').setAttribute('data-harga', '<?php echo $harga; ?>');
            document.getElementById('modal-order-btn').setAttribute('data-stok', '<?php echo $stok; ?>');
        </script>
        <?php
        exit;
    }
}

// Process order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produk_id']) && isset($_POST['jumlah'])) {
    // Get user ID from session
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $produk_id = mysqli_real_escape_string($conn, $_POST['produk_id']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $tanggal = date('Y-m-d');
    
    // Check stock availability
    $check_stock = mysqli_query($conn, "SELECT stok FROM produk WHERE produk_id = '$produk_id'");
    $stock_data = mysqli_fetch_assoc($check_stock);
    
    if ($stock_data['stok'] < $jumlah) {
        echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi.']);
        exit;
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert into pemesanan table
        $insert_query = "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan) 
                         VALUES ('$produk_id', '$user_id', '$tanggal', '$jumlah', 'pending')";
        
        $insert_result = mysqli_query($conn, $insert_query);
        
        if (!$insert_result) {
            throw new Exception("Error inserting order: " . mysqli_error($conn));
        }
        
        // Update stock
        $update_query = "UPDATE produk SET stok = stok - $jumlah WHERE produk_id = '$produk_id'";
        $update_result = mysqli_query($conn, $update_query);
        
        if (!$update_result) {
            throw new Exception("Error updating stock: " . mysqli_error($conn));
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        echo json_encode(['success' => true]);
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Check if it's an AJAX request for filtered products
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Get search and filter parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$stockFilter = isset($_GET['stockFilter']) ? $_GET['stockFilter'] : '';
$purchasedFilter = isset($_GET['purchasedFilter']) ? $_GET['purchasedFilter'] : '';

// Base query
$query = "SELECT p.produk_id, p.nama_produk, p.harga, p.stok, p.gambar_produk, p.deskripsi, 
          COALESCE(SUM(pm.jumlah_pemesanan), 0) AS total_purchased 
          FROM produk p 
          LEFT JOIN pemesanan pm ON p.produk_id = pm.produk_id";

// Add search condition if provided
if (!empty($search)) {
    $query .= " WHERE p.nama_produk LIKE '%$search%' OR p.deskripsi LIKE '%$search%'";
}

$query .= " GROUP BY p.produk_id";

// Add order by clause based on filters
if ($stockFilter == '1') {
    $query .= " ORDER BY p.stok DESC";
} elseif ($stockFilter == '2') {
    $query .= " ORDER BY p.stok ASC";
}

if ($purchasedFilter == '1') {
    $query .= " ORDER BY total_purchased DESC";
} elseif ($purchasedFilter == '2') {
    $query .= " ORDER BY total_purchased ASC";
}

$result = mysqli_query($conn, $query);

// If it's an AJAX request, only return the product list HTML
if ($isAjax) {
    // Output products HTML
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $produk_id = $row['produk_id'];
            $nama_produk = $row['nama_produk'];
            $harga = $row['harga'];
            $stok = $row['stok'];
            $gambar = !empty($row['gambar_produk']) ? $row['gambar_produk'] : 'default_product.jpg';
            $total_purchased = isset($row['total_purchased']) ? $row['total_purchased'] : 0;
    ?>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/products/<?php echo $gambar; ?>" class="card-img-top" alt="<?php echo $nama_produk; ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $nama_produk; ?></h5>
                        <p class="card-text">Rp <?php echo number_format($harga, 0, ',', '.'); ?></p>
                        <p class="card-text">Stok: <?php echo $stok; ?></p>
                        <p class="card-text">Terjual: <?php echo $total_purchased; ?></p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary view-details" data-id="<?php echo $produk_id; ?>" 
                                    data-nama="<?php echo $nama_produk; ?>" 
                                    data-harga="<?php echo $harga; ?>" 
                                    data-stok="<?php echo $stok; ?>"
                                    data-bs-toggle="modal" data-bs-target="#productModal">
                                Lihat Detail
                            </button>
                            <?php if ($stok > 0) : ?>
                                <button class="btn btn-success order-product" data-id="<?php echo $produk_id; ?>" 
                                        data-nama="<?php echo $nama_produk; ?>" 
                                        data-harga="<?php echo $harga; ?>"
                                        data-stok="<?php echo $stok; ?>"
                                        data-bs-toggle="modal" data-bs-target="#orderModal">
                                    Pesan Sekarang
                                </button>
                            <?php else : ?>
                                <button class="btn btn-secondary" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    } else {
        echo '<div class="col-12 text-center"><p>Tidak ada produk ditemukan</p></div>';
    }
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
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $produk_id = $row['produk_id'];
                    $nama_produk = $row['nama_produk'];
                    $harga = $row['harga'];
                    $stok = $row['stok'];
                    $gambar = !empty($row['gambar_produk']) ? $row['gambar_produk'] : 'default_product.jpg';
                    $total_purchased = isset($row['total_purchased']) ? $row['total_purchased'] : 0;
            ?>
                    <div class="col">
                        <div class="card h-100">
                            <img src="assets/img/products/<?php echo $gambar; ?>" class="card-img-top" alt="<?php echo $nama_produk; ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $nama_produk; ?></h5>
                                <p class="card-text">Rp <?php echo number_format($harga, 0, ',', '.'); ?></p>
                                <p class="card-text">Stok: <?php echo $stok; ?></p>
                                <p class="card-text">Terjual: <?php echo $total_purchased; ?></p>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary view-details" data-id="<?php echo $produk_id; ?>" 
                                            data-nama="<?php echo $nama_produk; ?>" 
                                            data-harga="<?php echo $harga; ?>" 
                                            data-stok="<?php echo $stok; ?>"
                                            data-bs-toggle="modal" data-bs-target="#productModal">
                                        Lihat Detail
                                    </button>
                                    <?php if ($stok > 0) : ?>
                                        <button class="btn btn-success order-product" data-id="<?php echo $produk_id; ?>" 
                                                data-nama="<?php echo $nama_produk; ?>" 
                                                data-harga="<?php echo $harga; ?>"
                                                data-stok="<?php echo $stok; ?>"
                                                data-bs-toggle="modal" data-bs-target="#orderModal">
                                            Pesan Sekarang
                                        </button>
                                    <?php else : ?>
                                        <button class="btn btn-secondary" disabled>Stok Habis</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<div class="col-12 text-center"><p>Tidak ada produk ditemukan</p></div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Product Detail Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="product-detail-content">
                <!-- Product details will be loaded here via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="modal-order-btn">Pesan Sekarang</button>
            </div>
        </div>
    </div>
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Pesan Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="order-form">
                    <input type="hidden" id="order-product-id" name="produk_id">
                    <div class="mb-3">
                        <label for="product-name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="product-name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="product-price" class="form-label">Harga</label>
                        <input type="text" class="form-control" id="product-price" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="quantity" name="jumlah" min="1" value="1">
                        <small class="text-muted">Stok tersedia: <span id="available-stock"></span></small>
                    </div>
                    <div class="mb-3">
                        <label for="total-price" class="form-label">Total Harga</label>
                        <input type="text" class="form-control" id="total-price" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submit-order">Konfirmasi Pesanan</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Pesanan Berhasil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    Pesanan Anda berhasil dibuat! Silakan cek status pesanan di halaman profil Anda.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<?php include("UserLayouts/footer.php"); ?>

<!-- AJAX and jQuery for Search and Filtering -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to fetch products based on search term and filters
        function fetchProducts() {
            var search = $('#search-input').val();
            var stockFilter = $('#filter-stock').val();
            var purchasedFilter = $('#filter-purchased').val();

            $.ajax({
                url: window.location.href,
                type: 'GET',
                data: {
                    search: search,
                    stockFilter: stockFilter,
                    purchasedFilter: purchasedFilter
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#product-list').html(response);
                    attachEventListeners();
                }
            });
        }

        // Attach event listeners to dynamically added elements
        function attachEventListeners() {
            // View Details button click
            $('.view-details').click(function() {
                var productId = $(this).data('id');
                $.ajax({
                    url: window.location.href,
                    type: 'GET',
                    data: { 
                        get_product_details: true,
                        produk_id: productId 
                    },
                    success: function(response) {
                        $('#product-detail-content').html(response);
                    }
                });
            });

            // Order button click
            $('.order-product').click(function() {
                var productId = $(this).data('id');
                var productName = $(this).data('nama');
                var productPrice = $(this).data('harga');
                var productStock = $(this).data('stok');
                
                $('#order-product-id').val(productId);
                $('#product-name').val(productName);
                $('#product-price').val('Rp ' + formatNumber(productPrice));
                $('#available-stock').text(productStock);
                calculateTotal(productPrice, 1);
            });

            // Modal Order button from product detail modal
            $('#modal-order-btn').click(function() {
                var productId = $(this).data('id');
                var productName = $(this).data('nama');
                var productPrice = $(this).data('harga');
                var productStock = $(this).data('stok');
                
                $('#productModal').modal('hide');
                
                $('#order-product-id').val(productId);
                $('#product-name').val(productName);
                $('#product-price').val('Rp ' + formatNumber(productPrice));
                $('#available-stock').text(productStock);
                calculateTotal(productPrice, 1);
                
                setTimeout(function() {
                    $('#orderModal').modal('show');
                }, 500);
            });
        }

        // Format number with thousand separator
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Calculate total price based on quantity
        function calculateTotal(price, quantity) {
            var total = price * quantity;
            $('#total-price').val('Rp ' + formatNumber(total));
        }

        // Quantity change event
        $(document).on('change keyup', '#quantity', function() {
            var price = $('#product-price').val().replace('Rp ', '').replace(/\./g, '');
            var quantity = $(this).val();
            calculateTotal(parseInt(price), parseInt(quantity));
        });

        // Submit order
        $(document).on('click', '#submit-order', function() {
            var productId = $('#order-product-id').val();
            var quantity = $('#quantity').val();
            var stock = parseInt($('#available-stock').text());
            
            if (quantity <= 0) {
                alert('Jumlah harus lebih dari 0');
                return;
            }
            
            if (quantity > stock) {
                alert('Jumlah melebihi stok yang tersedia');
                return;
            }
            
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    produk_id: productId,
                    jumlah: quantity
                },
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.success) {
                            $('#orderModal').modal('hide');
                            setTimeout(function() {
                                $('#successModal').modal('show');
                            }, 500);
                            // Refresh product list after successful order
                            fetchProducts();
                        } else {
                            alert(result.message || 'Terjadi kesalahan saat membuat pesanan');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan dalam format respons.');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi nanti.');
                }
            });
        });

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

        // Initial attachment of event listeners
        attachEventListeners();
    });
</script>