<?php
include 'UserLayouts/header.php';
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
?>

<div style="padding-top: 100px;">
    <div class="container mt-5">
        <h3 class="mb-4 text-center">Semua Produk Kami</h3>

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

        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center" id="product-list">
        </div>
    </div>
</div>

<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pesan Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    <input type="hidden" id="produk_id" name="produk_id">
                    <input type="hidden" id="user_id" name="user_id" value="<?= htmlspecialchars($user_id) ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="product_name" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="text" class="form-control" id="product_price" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Tersedia</label>
                        <input type="text" class="form-control" id="available_stock" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Pemesanan</label>
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

<?php include 'UserLayouts/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    function fetchProducts() {
        $.ajax({
            url: 'fetch_products.php',
            method: 'GET',
            data: {
                search: $('#search-input').val(),
                stockFilter: $('#filter-stock').val(),
                purchasedFilter: $('#filter-purchased').val()
            },
            success: function (res) {
                $('#product-list').html(res);
                bindOrderButtons();
            }
        });
    }

    function bindOrderButtons() {
        $('.order-btn').off().on('click', function () {
            const data = $(this).data();
            $('#produk_id').val(data.id);
            $('#product_name').val(data.name);
            $('#product_price').val('Rp ' + new Intl.NumberFormat('id-ID').format(data.price));
            $('#available_stock').val(data.stock);
            $('#jumlah_pemesanan').val(1).attr('max', data.stock);

            $('#stock-warning, #order-success, #order-error').hide();
            $('#submitOrder').prop('disabled', false);
            $('#orderModal').modal('show');
        });
    }

    $('#jumlah_pemesanan').on('input', function () {
        const max = parseInt($('#available_stock').val(), 10);
        const val = parseInt(this.value, 10);
        if (val > max) {
            $('#stock-warning').show();
            $('#submitOrder').prop('disabled', true);
        } else {
            $('#stock-warning').hide();
            $('#submitOrder').prop('disabled', false);
        }
    });

    $('#submitOrder').on('click', function () {
        const data = {
            produk_id: $('#produk_id').val(),
            user_id: $('#user_id').val(),
            jumlah_pemesanan: $('#jumlah_pemesanan').val()
        };

        if (!data.user_id || data.user_id == 0) {
            alert('Silakan login terlebih dahulu.');
            $('#orderModal').modal('hide');
            return;
        }

        $.post('process_order.php', data, function (res) {
            try {
                const result = JSON.parse(res);
                if (result.status === 'success') {
                    $('#order-success').show();
                    $('#submitOrder').prop('disabled', true);
                    setTimeout(() => {
                        $('#orderModal').modal('hide');
                        fetchProducts();
                    }, 2000);
                } else {
                    $('#order-error').text(result.message || 'Gagal memesan produk.').show();
                }
            } catch {
                $('#order-error').text('Respon tidak valid dari server.').show();
            }
        }).fail(() => {
            $('#order-error').text('Gagal terhubung ke server.').show();
        });
    });

    $('#search-input, #filter-stock, #filter-purchased').on('input change', fetchProducts);

    fetchProducts();
});
</script>
