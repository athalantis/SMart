<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database
session_start();

// Function to handle AJAX requests for product listing
if(isset($_GET['action']) && $_GET['action'] == 'fetch_products') {
    // Initialize query
    $query = "SELECT p.produk_id, p.nama_produk, p.harga, p.stok, p.gambar_produk, p.deskripsi,
             (SELECT COUNT(*) FROM pemesanan WHERE produk_id = p.produk_id) as jumlah_terjual 
             FROM produk p WHERE 1=1";

    // Check if search parameter exists
    if(isset($_GET['search']) && !empty($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $query .= " AND (p.nama_produk LIKE '%$search%' OR p.deskripsi LIKE '%$search%')";
    }

    // Check stock filter
    if(isset($_GET['stockFilter']) && !empty($_GET['stockFilter'])) {
        if($_GET['stockFilter'] == 1) {
            $query .= " ORDER BY p.stok DESC"; // Most stock
        } else if($_GET['stockFilter'] == 2) {
            $query .= " ORDER BY p.stok ASC"; // Least stock
        }
    } 
    // Check purchased filter
    else if(isset($_GET['purchasedFilter']) && !empty($_GET['purchasedFilter'])) {
        if($_GET['purchasedFilter'] == 1) {
            $query .= " ORDER BY jumlah_terjual DESC"; // Most purchased
        } else if($_GET['purchasedFilter'] == 2) {
            $query .= " ORDER BY jumlah_terjual ASC"; // Least purchased
        }
    } else {
        // Default order
        $query .= " ORDER BY p.produk_id DESC";
    }

    $result = mysqli_query($conn, $query);
    $output = '';

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $produk_id = $row['produk_id'];
            $nama_produk = $row['nama_produk'];
            $harga = $row['harga'];
            $stok = $row['stok'];
            $gambar = $row['gambar_produk'];
            $terjual = $row['jumlah_terjual'];
            $deskripsi = $row['deskripsi'];
            
            // Format the price with rupiah
            $formatted_price = "Rp " . number_format($harga, 0, ',', '.');
            
            $output .= '
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/products/' . $gambar . '" class="card-img-top" alt="' . $nama_produk . '" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">' . $nama_produk . '</h5>
                        <p class="card-text">Harga: ' . $formatted_price . '</p>
                        <p class="card-text">Stok: ' . $stok . ' | Terjual: ' . $terjual . '</p>
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-primary btn-sm view-product" data-id="' . $produk_id . '" 
                        data-name="' . $nama_produk . '" 
                        data-price="' . $harga . '" 
                        data-stock="' . $stok . '" 
                        data-image="' . $gambar . '" 
                        data-description="' . htmlspecialchars($deskripsi) . '">Lihat Detail</button>
                    </div>
                </div>
            </div>';
        }
    } else {
        $output = '<div class="col-12 text-center"><p>Tidak ada produk yang ditemukan</p></div>';
    }

    echo $output;
    exit;
}

// Function to handle order placement
if(isset($_POST['action']) && $_POST['action'] == 'place_order') {
    $response = array();

    // Check if user is logged in
    if(!isset($_SESSION['user_id'])) {
        $response = array(
            'status' => false,
            'message' => 'Login required to place an order'
        );
    } else {
        // Validate request
        $produk_id = isset($_POST['produk_id']) ? mysqli_real_escape_string($conn, $_POST['produk_id']) : '';
        $jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 0;
        $user_id = $_SESSION['user_id'];
        
        // Validate input
        if(empty($produk_id) || $jumlah <= 0) {
            $response = array(
                'status' => false,
                'message' => 'Invalid product or quantity'
            );
        } else {
            // Check if product exists and has enough stock
            $check_query = "SELECT stok FROM produk WHERE produk_id = '$produk_id'";
            $check_result = mysqli_query($conn, $check_query);
            
            if(mysqli_num_rows($check_result) > 0) {
                $product = mysqli_fetch_assoc($check_result);
                
                if($product['stok'] < $jumlah) {
                    $response = array(
                        'status' => false,
                        'message' => 'Insufficient stock. Available: ' . $product['stok']
                    );
                } else {
                    // Begin transaction
                    mysqli_begin_transaction($conn);
                    
                    try {
                        // Insert into pemesanan table
                        $tanggal = date('Y-m-d');
                        $insert_query = "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan) 
                                        VALUES ('$produk_id', '$user_id', '$tanggal', '$jumlah', 'pending')";
                        
                        if(mysqli_query($conn, $insert_query)) {
                            $pemesanan_id = mysqli_insert_id($conn);
                            
                            // Update product stock
                            $update_query = "UPDATE produk SET stok = stok - $jumlah WHERE produk_id = '$produk_id'";
                            mysqli_query($conn, $update_query);
                            
                            // Commit transaction
                            mysqli_commit($conn);
                            
                            $response = array(
                                'status' => true,
                                'message' => 'Order placed successfully',
                                'order_id' => $pemesanan_id
                            );
                        } else {
                            throw new Exception("Failed to place order");
                        }
                    } catch (Exception $e) {
                        // Rollback transaction
                        mysqli_rollback($conn);
                        
                        $response = array(
                            'status' => false,
                            'message' => $e->getMessage()
                        );
                    }
                }
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Product not found'
                );
            }
        }
    }
    
    echo json_encode($response);
    exit;
}

// Main page content
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

<!-- Product Detail Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Product Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <img id="product-image" src="" class="img-fluid rounded" alt="Product Image">
          </div>
          <div class="col-md-6">
            <h4>Detail Produk</h4>
            <p><strong>Harga:</strong> <span id="product-price"></span></p>
            <p><strong>Stok:</strong> <span id="product-stock"></span></p>
            <p><strong>Deskripsi:</strong> <span id="product-description"></span></p>
            
            <form id="place-order-form">
              <div class="mb-3">
                <label for="order-quantity" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="order-quantity" min="1" value="1">
                <input type="hidden" id="order-product-id">
                <input type="hidden" id="order-max-quantity">
                <div id="order-error" class="text-danger mt-2" style="display: none;"></div>
              </div>
              <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Order Successful</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="successMessage"></p>
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
                url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                type: 'GET',
                data: {
                    action: 'fetch_products',
                    search: search,
                    stockFilter: stockFilter,
                    purchasedFilter: purchasedFilter
                },
                success: function(response) {
                    $('#product-list').html(response);
                    bindProductEvents();
                }
            });
        }

        // Function to bind events to product buttons after loading
        function bindProductEvents() {
            $('.view-product').off('click').on('click', function() {
                var productId = $(this).data('id');
                var productName = $(this).data('name');
                var productPrice = $(this).data('price');
                var productStock = $(this).data('stock');
                var productImage = $(this).data('image');
                var productDescription = $(this).data('description');
                
                // Populate modal with product details
                $('#productModalLabel').text(productName);
                $('#product-image').attr('src', 'assets/img/products/' + productImage);
                $('#product-price').text('Rp ' + productPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
                $('#product-stock').text(productStock);
                $('#product-description').text(productDescription || 'No description available');
                
                // Set product ID for order form
                $('#order-product-id').val(productId);
                $('#order-max-quantity').val(productStock);
                $('#order-quantity').attr('max', productStock);
                
                // Reset quantity input and error message
                $('#order-quantity').val(1);
                $('#order-error').hide();
                
                // Show modal
                $('#productModal').modal('show');
            });
        }

        // Handle order form submission
        $('#place-order-form').on('submit', function(e) {
            e.preventDefault();
            
            var productId = $('#order-product-id').val();
            var quantity = $('#order-quantity').val();
            var maxQuantity = $('#order-max-quantity').val();
            
            // Validate quantity
            if(parseInt(quantity) > parseInt(maxQuantity)) {
                $('#order-error').text('Quantity exceeds available stock').show();
                return;
            }
            
            $.ajax({
                url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                type: 'POST',
                data: {
                    action: 'place_order',
                    produk_id: productId,
                    jumlah: quantity
                },
                dataType: 'json',
                success: function(response) {
                    if(response.status) {
                        // Order successful
                        $('#productModal').modal('hide');
                        
                        // Show success message
                        $('#successMessage').text(response.message);
                        $('#successModal').modal('show');
                        
                        // Refresh products after successful order
                        setTimeout(function() {
                            fetchProducts();
                        }, 1000);
                    } else {
                        // Show error message
                        $('#order-error').text(response.message).show();
                    }
                },
                error: function() {
                    $('#order-error').text('Error processing your order. Please try again.').show();
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

        // Validate quantity input
        $('#order-quantity').on('change', function() {
            var quantity = $(this).val();
            var maxQuantity = $('#order-max-quantity').val();
            
            if(parseInt(quantity) > parseInt(maxQuantity)) {
                $('#order-error').text('Quantity exceeds available stock (' + maxQuantity + ')').show();
            } else {
                $('#order-error').hide();
            }
        });

        // Initial load
        fetchProducts();
    });
</script>