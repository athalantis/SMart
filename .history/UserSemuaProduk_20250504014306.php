<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database
?>

<div style="padding-top: 80px;">
    <div class="container mt-4">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="h3 mb-3 fw-bold">Cari produk...</h1>
            
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
            
            <!-- Search Box -->
            <div class="search-box position-relative mb-4">
                <input type="text" id="search-input" class="form-control ps-5" placeholder="Cari produk...">
                <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
            </div>
            
            <!-- Search Tips -->
            <div class="alert alert-light p-3 mb-4">
                <h6 class="fw-bold mb-2">Tips pencarian:</h6>
                <p class="mb-0">Gunakan kata kunci spesifik untuk hasil lebih akurat. Filter berdasarkan stok atau popularitas untuk menemukan produk terbaik.</p>
            </div>
        </div>

        <!-- Products List -->
        <div class="row g-3" id="product-list">
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
    /* Base Styles */
    body {
        background-color: #f8f9fa;
    }
    
    /* Search Box Styling */
    .search-box {
        position: relative;
        max-width: 500px;
    }
    
    .search-box .form-control {
        padding-left: 2.5rem;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
        height: 45px;
    }
    
    .search-box .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
    
    /* Filter Select Styling */
    .form-select, .form-select-sm {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
        height: 35px;
    }
    
    .form-select:focus, .form-select-sm:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
    
    /* Card Styles */
    .card {
        border-radius: 10px;
        transition: all 0.3s ease;
        overflow: hidden;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-img-container {
        background-color: #f8f9fa;
        height: 180px;
        overflow: hidden;
    }
    
    .card-img-top {
        height: 100%;
        width: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    .card-title {
        font-size: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8rem;
        margin-bottom: 0.5rem;
    }
    
    /* Button Styles */
    .btn-primary {
        background-color: #0d6efd;
        border: none;
        padding: 0.5rem;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
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
        border: 1px solid #e0e0e0;
    }
    
    /* Rating Stars */
    .text-warning {
        color: #ffc107 !important;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .card-title {
            height: auto;
            -webkit-line-clamp: 3;
        }
        
        .search-box {
            max-width: 100%;
        }
        
        .d-flex.flex-wrap {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem !important;
        }
        
        .vr {
            display: none;
        }
        
        .form-select, .form-select-sm {
            width: 100% !important;
        }
    }
</style>