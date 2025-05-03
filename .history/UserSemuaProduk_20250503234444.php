<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get current user ID
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
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
                <h5 class="modal-title" id="orderModalLabel">Pesan Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    <input type="hidden" id="produk_id" name="produk_id">
                    <input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?>">

                    <div class="mb-3">
                        <label for="product_name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="product_name" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="product_price" class="form-label">Harga</label>
                        <input type="text" class="form-control" id="product_price" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="available_stock" class="form-label">Stok Tersedia</label>
                        <input type="text" class="form-control" id="available_stock" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_pemesanan" class="form-label">Jumlah Pemesanan</label>
                        <input type="number" class="form-control" id="jumlah_pemesanan" name="jumlah_pemesanan" min="1" required>
                        <div id="stock-warning" class="text-danger" style="display: none;">Jumlah melebihi stok yang tersedia!</div>
                    </div>

                    <div class="alert alert-success" id="order-success" style="display: none;">Pesanan berhasil dibuat!</div>
                    <div class="alert alert-danger" id="order-error" style="display: none;">Terjadi kesalahan saat membuat pesanan.</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitOrder">Pesan Sekarang</button>
            </div>
        </div>
    </div>
</div>

<?php include("UserLayouts/footer.php"); ?>

<!-- AJAX and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    function fetchProducts() {
        let search = $('#search-input').val();
        let stockFilter = $('#filter-stock').val();
        let purchasedFilter = $('#filter-purchased').val();

        $.ajax({
            url: 'fetch_products.php',
            type: 'GET',
            data: {
                search: search,
                stockFilter: stockFilter,
                purchasedFilter: purchasedFilter
            },
            success: function (response) {
                $('#product-list').html(response);
                attachOrderButtonHandlers();
            }
        });
    }

    function attachOrderButtonHandlers() {
        $('.order-btn').off('click').on('click', function () {
            const produkId = $(this).data('id');
            const namaProduk = $(this).data('name');
            const harga = $(this).data('price');
            const stok = $(this).data('stock');

            $('#produk_id').val(produkId);
            $('#product_name').val(namaProduk);
            $('#product_price').val('Rp ' + new Intl.NumberFormat('id-ID').format(harga));
            $('#available_stock').val(stok);
            $('#jumlah_pemesanan').val(1).attr('max', stok);

            $('#stock-warning, #order-success, #order-error').hide();
            $('#submitOrder').prop('disabled', false);
            $('#orderModal').modal('show');
        });
    }

    $('#jumlah_pemesanan').on('input', function () {
        const stok = parseInt($('#available_stock').val());
        const jumlah = parseInt($(this).val());

        if (jumlah > stok) {
            $('#stock-warning').show();
            $('#submitOrder').prop('disabled', true);
        } else {
            $('#stock-warning').hide();
            $('#submitOrder').prop('disabled', false);
        }
    });

    $('#submitOrder').on('click', function () {
        const produkId = $('#produk_id').val();
        const userId = $('#user_id').val();
        const jumlahPemesanan = $('#jumlah_pemesanan').val();

        if (userId == 0) {
            alert('Silakan login terlebih dahulu untuk melakukan pemesanan.');
            $('#orderModal').modal('hide');
            return;
        }

        if (!jumlahPemesanan || jumlahPemesanan <= 0) {
            alert('Silakan masukkan jumlah pemesanan yang valid.');
            return;
        }

        $.ajax({
            url: 'process_order.php',
            type: 'POST',
            data: {
                produk_id: produkId,
                user_id: userId,
                jumlah_pemesanan: jumlahPemesanan
            },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    $('#order-success').show();
                    $('#submitOrder').prop('disabled', true);
                    setTimeout(function () {
                        $('#orderModal').modal('hide');
                        fetchProducts();
                    }, 2000);
                } else {
                    $('#order-error').text(result.message || 'Terjadi kesalahan saat membuat pesanan.').show();
                }
            },
            error: function () {
                $('#order-error').text('Terjadi kesalahan saat menghubungi server.').show();
            }
        });
    });

    $('#search-input, #filter-stock, #filter-purchased').on('input change', fetchProducts);

    fetchProducts(); // Initial load
});
</script>
