<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database
?>

<div style="padding-top: 100px;">
    <div class="container mt-5">
        <!-- Hero Section -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold mb-3">Semua Produk Kami</h1>
            <p class="lead text-muted">Temukan produk terbaik untuk kebutuhan Anda</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="search-box position-relative">
                    <input type="text" id="search-input" class="form-control ps-5" placeholder="Cari produk...">
                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <select id="filter-stock" class="form-select">
                    <option value="">Filter by Stock</option>
                    <option value="1">Most Stock</option>
                    <option value="2">Least Stock</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filter-purchased" class="form-select">
                    <option value="">Filter by Popularity</option>
                    <option value="1">Most Purchased</option>
                    <option value="2">Least Purchased</option>
                </select>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="alert alert-light border mb-4 d-flex align-items-center">
            <i class="bi bi-lightbulb me-2 fs-4 text-warning"></i>
            <div>
                <strong>Tips pencarian:</strong> Gunakan kata kunci spesifik untuk hasil lebih akurat. 
                Filter berdasarkan stok atau popularitas untuk menemukan produk terbaik.
            </div>
        </div>

        <!-- Products List -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4" id="product-list">
            <!-- Product list will be loaded here via AJAX -->
        </div>

        <!-- Loading Indicator -->
        <div id="loading-indicator" class="text-center my-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat produk...</p>
        </div>
    </div>
</div>

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