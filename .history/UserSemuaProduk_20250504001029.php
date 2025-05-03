<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database
include 'UserSemuaBackend.php'; // Include backend for fetching products

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

<?php include("UserLayouts/footer.php"); ?>

<!-- Bootstrap Modal for Product Details -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Product Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="product-details">
        <!-- Product details will be loaded here via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Add to Cart</button>
      </div>
    </div>
  </div>
</div>

<!-- AJAX and jQuery for Search and Filtering -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to fetch products based on search term and filters
        function fetchProducts() {
            var search = $('#search-input').val();
            var stockFilter = $('#filter-stock').val();
            var purchasedFilter = $('#filter-purchased').val();

            $.ajax({
                url: 'fetch_products.php', // Backend script for fetching filtered products
                type: 'GET',
                data: {
                    search: search,
                    stockFilter: stockFilter,
                    purchasedFilter: purchasedFilter
                },
                success: function(response) {
                    $('#product-list').html(response);
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

        // Function to load product details in the modal
        function loadProductDetails(productId) {
            $.ajax({
                url: 'UserSemuaBackend.php', // Backend logic for fetching specific product details
                type: 'GET',
                data: {
                    productId: productId
                },
                success: function(response) {
                    $('#product-details').html(response);
                    $('#productModal').modal('show');
                }
            });
        }

        // Trigger load product details when "Beli Sekarang" button is clicked
        $(document).ready(function () {
    // Function to fetch products based on search term and filters
    function fetchProducts() {
        var search = $('#search-input').val();
        var stockFilter = $('#filter-stock').val();
        var purchasedFilter = $('#filter-purchased').val();

        $.ajax({
            url: 'fetch_products.php', // Backend script for fetching filtered products
            type: 'GET',
            data: {
                search: search,
                stockFilter: stockFilter,
                purchasedFilter: purchasedFilter
            },
            success: function(response) {
                $('#product-list').html(response);
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

    // Function to handle "Pesan Sekarang" click
    $(document).on('click', '.btn-pesan-sekarang', function () {
        var produkId = $(this).data('produk-id');
        var userId = <?php echo $_SESSION['user_id']; ?>; // Assuming user_id is stored in session

        // Send AJAX request to insert into pemesanan table
        $.ajax({
            url: 'UserSemuaBackend.php', // Backend script for inserting order
            type: 'POST',
            data: {
                produk_id: produkId,
                user_id: userId,
                action: 'insert_pemesanan' // Action to handle insert in backend
            },
            success: function(response) {
                alert('Pemesanan berhasil dilakukan!');
                // You can refresh the list or handle further actions here
            }
        });
    });

    // Initial load of products
    fetchProducts();
});

    });
</script>

