

    <div class="container-fluid py-3 footer-color2">
        <div class="container">
            <h6 class="text-center mt-2" style="color: #7a7a7a;">Website Made With Group 16</h6>
        </div>
    </div>
    <!-- footer akhir -->

    <!-- Bootstrap Modal Script -->
    <script>
    let selectedProdukId = null;
    let selectedStok = 0;

    function openOrderModal(produkId, stok) {
        selectedProdukId = produkId;
        selectedStok = stok;
        document.getElementById('modalProdukId').value = produkId;
        document.getElementById('modalJumlah').value = 1;
        document.getElementById('modalJumlah').setAttribute("max", stok);
        new bootstrap.Modal(document.getElementById('orderModal')).show();
    }

    // Pastikan event listener hanya ditambahkan sekali
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('confirmOrderBtn').addEventListener('click', function () {
            let jumlah = parseInt(document.getElementById('modalJumlah').value);
            
            if (jumlah > 0 && jumlah <= selectedStok) {
                window.location.href = "create_pemesanan.php?produk_id=" + selectedProdukId + "&jumlah=" + jumlah;
            } else {
                alert("Jumlah tidak valid atau melebihi stok!");
            }
        });
    });
    </script>

    <!-- Optional JavaScript; choose one of the two! -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- JS tambahan-->
    <script src="../dist/js/lightbox-plus-jquery.min.js"></script>

    <!-- Masonry -->
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
</body>
</html>

