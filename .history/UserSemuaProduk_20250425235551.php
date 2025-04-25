<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database
?>

<div style="padding-top: 100px; margin-bottom: 250px;">
    <div class="container mt-5">
        <h3 class="mb-4 text-center">Semua Produk Kami</h3>

        <!-- Search and Filter Inputs -->
        <div class="row mb-4">
            <div class="col-md-6 mb-2">
                <input type="text" id="search" class="form-control" placeholder="Cari produk...">
            </div>
            <div class="col-md-3 mb-2">
                <select id="filter" class="form-control">
                    <option value="">-- Filter --</option>
                    <option value="most_purchased">Paling Banyak Dibeli</option>
                    <option value="most_stock">Stok Terbanyak</option>
                </select>
            </div>
        </div>

        <!-- Product List -->
        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center" id="product-list">
            <!-- Produk akan ditampilkan di sini via AJAX -->
        </div>
    </div>
</div>

<?php include("UserLayouts/footer.php"); ?>

<!-- Add JavaScript for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadProducts(search = '', filter = '') {
    $.ajax({
        url: 'search_filter_produk.php',
        type: 'GET',
        data: { search: search, filter: filter },
        success: function(response) {
            $('#product-list').html(response);
        }
    });
}

// Trigger ketika load pertama kali
$(document).ready(function() {
    loadProducts(); // Load all products initially

    // Handle search keyup event
    $('#search').on('keyup', function() {
        let search = $(this).val();
        let filter = $('#filter').val();
        loadProducts(search, filter);
    });

    // Handle filter change event
    $('#filter').on('change', function() {
        let search = $('#search').val();
        let filter = $(this).val();
        loadProducts(search, filter);
    });
});
</script>
