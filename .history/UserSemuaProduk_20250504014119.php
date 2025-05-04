<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database
?>

<div class="container mt-4">
    <!-- Search Header -->
    <div class="mb-4">
        <h1 class="h3 mb-3">Cari produk...</h1>
        
        <!-- Filter Section -->
        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
            <div class="fw-bold">Filter by Stock</div>
            <div class="vr"></div>
            <select id="filter-stock" class="form-select form-select-sm" style="width: auto;">
                <option value="">All</option>
                <option value="1">Most Stock</option>
                <option value="2">Least Stock</option>
            </select>
            <select id="filter-purchased" class="form-select form-select-sm" style="width: auto;">
                <option value="">By Popularity</option>
                <option value="1">Most Purchased</option>
                <option value="2">Least Purchased</option>
            </select>
        </div>
        
        <!-- Search Tips -->
        <div class="alert alert-light p-3 mb-4">
            <h6 class="fw-bold mb-2">Tips pencarian:</h6>
            <p class="mb-0">Gunakan kata kunci spesifik untuk hasil lebih akurat. Filter berdasarkan stok atau popularitas untuk menemukan produk terbaik.</p>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-3">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php $stok = (int) $row['stok']; ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <!-- Product Image -->
                        <div class="card-img-container ratio ratio-1x1">
                            <img src="./product_picture/<?= htmlspecialchars($row['gambar_produk']) ?>" 
                                 class="card-img-top img-fluid object-fit-cover" 
                                 alt="<?= htmlspecialchars($row['nama_produk']) ?>">
                        </div>
                        
                        <!-- Product Info -->
                        <div class="card-body p-3">
                            <small class="text-muted d-block mb-1">Toko Saya</small>
                            <h6 class="card-title mb-2"><?= htmlspecialchars($row['nama_produk']) ?></h6>
                            <p class="card-price text-success fw-bold mb-2">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                            
                            <!-- Stock & Rating -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-<?= $stok > 0 ? 'success' : 'danger' ?>">
                                    <?= $stok > 0 ? 'In Stock' : 'Out of Stock' ?>
                                </span>
                                <small class="text-muted">Stock: <?= $stok ?></small>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <span class="text-warning">★ ★ ★ ★ ★</span>
                                <small class="text-muted ms-1">4.9</small>
                            </div>
                            
                            <!-- Buy Button -->
                            <button type="button"
                                    class="btn btn-primary w-100 <?= $stok <= 0 ? 'disabled' : '' ?>"
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

<!-- Order Modal (keep existing modal code) -->

<style>
    /* Card Styles */
    .card {
        border-radius: 8px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .card-img-container {
        background-color: #f8f9fa;
    }
    
    .card-title {
        font-size: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8rem;
    }
    
    /* Button Styles */
    .btn-primary {
        background-color: #0d6efd;
        border: none;
        padding: 0.5rem;
        border-radius: 6px;
        font-size: 0.9rem;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
    }
    
    /* Badge Styles */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* Alert Styles */
    .alert-light {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .card-title {
            height: auto;
            -webkit-line-clamp: 3;
        }
    }
</style>
<?php include("UserLayouts/footer.php"); ?>

<!-- AJAX and jQuery for Search and Filtering -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        let searchTimeout;
        
        // Function to fetch products with loading indicator
        function fetchProducts() {
            $('#loading-indicator').show();
            $('#product-list').html('');
            
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
                complete: function() {
                    $('#loading-indicator').hide();
                }
            });
        }

        // Debounced search input
        $('#search-input').on('keyup', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchProducts, 500);
        });

        // Immediate filter changes
        $('#filter-stock, #filter-purchased').on('change', function () {
            fetchProducts();
        });

        // Initial load
        fetchProducts();
    });
</script>

<style>
    /* Search Box Styling */
    .search-box {
        position: relative;
    }
    
    .search-box .form-control {
        padding-left: 2.5rem;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .search-box .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
    
    /* Filter Select Styling */
    .form-select {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
    
    /* Hero Section */
    .display-5 {
        color: #2c3e50;
        font-weight: 700;
    }
    
    /* Tips Box */
    .alert-light {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .display-5 {
            font-size: 2rem;
        }
        
        .search-box, .filter-select {
            margin-bottom: 1rem;
        }
    }
</style>