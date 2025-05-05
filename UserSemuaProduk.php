<!-- style cs -->
<link rel="stylesheet" href="website.css">

<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database
?>

<!-- carousel awal -->
    <div class="carousel">
        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mt-4">
                        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button
                                type="button"
                                data-bs-target="#carouselExampleCaptions"
                                data-bs-slide-to="0"
                                class="active"
                                aria-current="true"
                                aria-label="Slide 1"
                                ></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4" aria-label="Slide 5"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="5" aria-label="Slide 6"></button>
                            </div>
                            <di class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="./img/carousel/carousel4.jpg" class="d-block w-100" alt="..." />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Diskon Akhir Bulan</h5>
                                        <p>Hemat hingga 50% untuk koleksi terbaru musim ini!.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="./img/carousel/carousel5.jpg" class="d-block w-100" alt="..." />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Best Seller Minggu Ini</h5>
                                        <p>Temukan produk favorit pelanggan dengan harga spesial.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="./img/carousel/carousel6.jpg" class="d-block w-100" alt="..." />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Gratis Ongkir!</h5>
                                        <p>Pengiriman cepat dan gratis untuk semua pesanan di atas Rp150.000!.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="./img/carousel/carousel7.jpg" class="d-block w-100" alt="..." />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Koleksi Baru Telah Tiba</h5>
                                        <p>Jangan lewatkan tren terbaru dengan desain eksklusif hanya di sini.</p>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- carousel akhir -->
        
<!-- kategori awal -->
    <div class="kategori">
        <div class="container">
            <div class="container shadow p-3 bg-body rounded border border-1 justify-content-center mt-3">
                <p class="fs-5 m-1">KATEGORI</p>
                <div class="row">
                    <div class="col-sm-4 col-md-2 mb-2">
                        <div class="card-cate">
                            <a href="kat-elek.php">
                            <img src="./img/kategori/cate1.jpg" alt="Elektronik" class="img-size" />
                            <p class="text-center">Elektronik</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-2 mb-2">
                        <div class="card-cate">
                            <a href="kat-fas.php">
                            <img src="./img/kategori/cate2.webp" alt="Fashion" class="img-size" />
                            <p class="text-center">Fashion</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-2 mb-2">
                        <div class="card-cate">
                            <a href="kat-tas.php">
                            <img src="./img/kategori/cate3.webp" alt="Tas" class="img-size" />
                            <p class="text-center">Tas</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-2 mb-2">
                        <div class="card-cate">
                            <a href="kat-dap.php">
                            <img src="./img/kategori/cate4.webp" alt="Dapur" class="img-size" />
                            <p class="text-center">Dapur</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-2 mb-4">
                        <div class="card-cate">
                            <a href="kat-mak.php">
                            <img src="./img/kategori/cate5.webp" alt="Makanan" class="img-size" />
                            <p class="text-center">Makanan</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-2 mb-2">
                        <div class="card-cate">
                            <a href="kategori/kat-kos.php">
                            <img src="./img/kategori/cate6.webp" alt="Kosmetik" class="img-size" />
                            <p class="text-center">Kosmeowtik</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- kategori akhir -->
    
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
