<link rel="stylesheet" href="website.css">

<?php
// js untuk review awal
session_start(); // Memulai session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clear_reviews'])) {
    unset($_SESSION['reviews']); // Hapus semua ulasan dari session// 
     echo "Ulasan berhasil dihapus.";
    exit();
}

// Cek apakah form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $pesanSingkat = isset($_POST['pesan-singkat']) ? $_POST['pesan-singkat'] : '';
    $pesan = isset($_POST['pesan']) ? $_POST['pesan'] : '';

    // Tambahkan ulasan baru ke session
    if (!empty($nama) && !empty($pesan)) {
        $_SESSION['reviews'][] = [
            'nama' => $nama,
            'pesanSingkat' => $pesanSingkat,
            'pesan' => $pesan
        ];

} else{
        echo "<script>alert('Nama dan Pesan wajib diisi!');</script>";
    }
}
// js untuk review akhir.
include 'UserLayouts/header.php';

// Use existing config.php for database connection
include 'config.php';

// Fetch products from the database
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk WHERE recommendations = 1";
$result = mysqli_query($conn, $query);
?>

    <!-- banner awal -->
    <style> 
        #banner{
            height: 92vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url(img/banner.jpg);
            background-size: cover;
            background-position:bottom 75% center;
        }
    </style>
    <div id="banner" class="container-fluid banner d-flex align-items-center">
        <div class="container">
            <p class="text-white text-center mb-1 fs-2">Selamat Datang Di Website Kami <i class="bi bi-emoji-smile"></i></p>
            <p class="text-white text-center mb-3 fs-3">Silahkan Cari disini!</p>
            <div class="col-md-8 offset-md-2">
                <div class="input-group input-group-md mb-3">
                    <input type="text" class="form-control" placeholder="search" aria-label="search" aria-describedby="button-addon2">
                    <button class="btn btn-md main-color" type="button" id="button-addon2"><i class="bi bi-search text-white"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- decor awal -->
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ff4400" fill-opacity="1" d="M0,96L40,112C80,128,160,160,240,154.7C320,149,400,107,480,117.3C560,128,640,192,720,202.7C800,213,880,171,960,133.3C1040,96,1120,64,1200,64C1280,64,1360,96,1400,112L1440,128L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path></svg>
    <!-- decor akhir -->
    <!-- banner akhir -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row text-center mt-5 mb-5">
            <div class="col">
                <h2>Rekomendasi Produk Untuk Anda</h2>
            </div>
        </div>

        <!-- Dynamic Product Cards with New Styling -->
        <div class="row justify-content-center">
            <?php 
            // Loop through products and create cards
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-sm-5 col-md-3 me-3 mb-2">
                <div class="card hovered-card mb-4">
                <img class="card-img-top img-fluid mx-auto d-block" 
                    src="./product_picture/<?php echo htmlspecialchars($row['gambar_produk']); ?>" 
                        alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                <p class="card-merk">SMart</p>
                    <div class="card-body">
                        <p class="card-text fs-5" style="padding:-20px;"><?php echo htmlspecialchars($row['nama_produk']); ?></p>
                        <p class="card-price">Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                        <p class="card-location">🚚 Tersedia</p>
                        <p class="card-rating">⭐ 4.9 | Stok: <?php echo intval($row['stok']); ?> tersedia</p>
                        <button type="button" 
                            class="btn rounded-pill main-color text-white w-100 mt-2" 
                            onclick="openOrderModal(<?php echo $row['produk_id']; ?>, <?php echo $row['stok']; ?>)">
                                Beli Sekarang
                        </button>
                    </div>
             </div>
           </div>
            <?php } ?>
        </div>
        <div class="row text-center">
            <div class="col mb-4">
                <a class="btn btn-outline-warning" href="UserSemuaProduk.php" role="button">Lihat Semua Produk</a>
            </div>
        </div>
    </div>

    <!-- decor awal -->
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#FF4400" fill-opacity="1" d="M0,32L40,80C80,128,160,224,240,234.7C320,245,400,171,480,117.3C560,64,640,32,720,48C800,64,880,128,960,128C1040,128,1120,64,1200,53.3C1280,43,1360,85,1400,106.7L1440,128L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path></svg>
    <!-- decor akhir -->

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
                            <p>Pesanan Anda akan kami kirim dengan cepat dan aman ke seluruh wilayah Indonesia menggunakan jasa ekspedisi Gus-EX.</p>
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
                            <p>Jika produk tidak sesuai/rusak dapat dikembalikan dan kami akan melakukan penggantian/pengembalian dana..</p>
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
                            <p>Kami siap membantu Anda kapan saja jika mengalami kendala atau memiliki pertanyaan seputar produk dan layanan.</p>
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

    <!-- review awal -->
    <div class="ulasan" style="margin-top: -150px;">
        <div class="container-fluid py-5 white">
            <div class="container">
                <h2 class="text-center text-black mb-5">Review Pengguna</h2>
                <div class="row" data-masonry='{"percentPosition": true }'>
                    <?php if (!empty($_SESSION['reviews'])): ?>
                    <?php foreach ($_SESSION['reviews'] as $review): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card hovered-card-service p-3 bg-produk">
                            <figure>
                                <blockquote class="blockquote">
                                    <p><?php echo $review['pesanSingkat']; ?></p>
                                </blockquote>
                            <figcaption class="blockquote-footer">
                                <?php echo $review['nama']; ?>
                            </figcaption>
                            <p><?php echo $review['pesan']; ?></p>
                            </figure>
                        </div>
                    </div>
                    <?php endforeach; ?>
            
                    <?php else: ?>
                    <div class="col-md-12 text-center main-color-f fs-3" style="margin-top: -140px ;">
                        <p>Belum ada review yang dikirimkan.</p>
                    </div>
                    <?php endif; ?>
                    </div>
            </div> 
        </div>
    </div>
<!-- review akhir -->
<!-- decor akhir -->
 <div class="bg">
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ff4400" fill-opacity="1" d="M0,128L40,106.7C80,85,160,43,240,37.3C320,32,400,64,480,96C560,128,640,160,720,170.7C800,181,880,171,960,154.7C1040,139,1120,117,1200,117.3C1280,117,1360,139,1400,149.3L1440,160L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path></svg>
   </div>
      <!-- decor akhir -->

    <!-- footer awal -->
    <div class="container-fluid py-5 main-color ">
        <div class="container">
            <h2 class="text-center text-white mb-4" style="margin-top: -100px;">Sosial Media</h2>
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
            <h6 class="text-center mt-2" style="color: #7a7a7a;">Website Made With Group 14</h6>
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