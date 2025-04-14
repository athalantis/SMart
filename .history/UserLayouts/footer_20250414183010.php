 <!-- service awal -->
 <div class="service">
        <div class="container-fluid main-color">
            <div class="container">
                <h2 class="text-center text-white mb-5">Kami Melayani</h2>
                <div class="row justify-content-center">
                    <div class="col-sm-6 col-md-3 mb-3 hovered-card-service">
                        <div class="d-flex justify-content-center">
                            <div class="icon-service d-flex align-items-center justify-content-center">
                                <i class="bi bi-truck display-5 text-white"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-white text-center">
                            <h6>Delivery</h6>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis unde vel dolor! Placeat non quos laboriosam itaque aliquam</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 mb-3 hovered-card-service">
                        <div class="d-flex justify-content-center">
                            <div class="icon-service d-flex align-items-center justify-content-center">
                                <i class="bi bi-box-seam display-5 text-white"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-white text-center">
                            <h6>Return and Refund</h6>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis unde vel dolor! Placeat non quos laboriosam itaque aliquam</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 mb-3 hovered-card-service">
                        <div class="d-flex justify-content-center">
                            <div class="icon-service d-flex align-items-center justify-content-center">
                                <i class="bi bi-headset display-5 text-white"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-white text-center">
                            <h6>Customer Support</h6>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis unde vel dolor! Placeat non quos laboriosam itaque aliquam</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- decor awal -->
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#FF4400" fill-opacity="1" d="M0,224L40,192C80,160,160,96,240,74.7C320,53,400,75,480,106.7C560,139,640,181,720,176C800,171,880,117,960,101.3C1040,85,1120,107,1200,138.7C1280,171,1360,213,1400,234.7L1440,256L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path></svg>
    <!-- decor akhir -->
    <!-- service akhir -->

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Masukkan Jumlah Pemesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modalProdukId">
                    <label for="modalJumlah" class="form-label">Jumlah:</label>
                    <input type="number" class="form-control" id="modalJumlah" min="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmOrderBtn">Pesan Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    <!-- form awal -->
    <div class="form">
        <div class="container-fluid py-5">
            <div class="container">
                <h2 class="text-center">Kritik dan Saran</h2>
                <hr class="col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-0" style="border: 1px solid;">
                <form class="col-sm-8 offset-sm-2 col-md-6 offset-md-3">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Pengirim</label>
                        <input type="email" class="form-control" id="email" placeholder="nama@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pengirim</label>
                        <input type="text" class="form-control" id="nama" placeholder="nama">
                    </div>
                    <div class="mb-3">
                        <label for="pesan" class="form-label">Kritik dan Pesan</label>
                        <textarea class="form-control" id="pesan" rows="3"></textarea>
                    </div>
                    <input class="btn btn-outline-warning w-100 mt-2" type="submit" value="Kirim">
                </form>
            </div>
        </div>
    </div>
    <!-- form akhir -->

    <!-- footer awal -->
    <div class="container-fluid py-5 footer-color">
        <div class="container">
            <h2 class="text-center text-white mb-2">Sosial Media</h2>
            <div class="row justify-content-center text-white">
                <div class="col-sm-1 col-md-1 fs-3 d-flex justify-content-center hovered-social">
                    <i class="bi bi-facebook"></i>
                </div>
                <div class="col-sm-1 col-md-1 fs-3 d-flex justify-content-center hovered-social">
                    <i class="bi bi-instagram"></i>
                </div>
                <div class="col-sm-1 col-md-1 fs-3 d-flex justify-content-center hovered-social">
                    <i class="bi bi-youtube"></i>
                </div>
                <div class="col-sm-1 col-md-1 fs-3 d-flex justify-content-center hovered-social">
                    <i class="bi bi-twitter-x"></i>
                </div>
            </div>
        </div>
    </div>

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

<?php 
include(".includes/footer.php");
?>