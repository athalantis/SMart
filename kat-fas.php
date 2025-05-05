<link rel="stylesheet" href="websites.css">
<?php

// js untuk review akhir.
include 'UserLayouts/header.php';

// Use existing config.php for database connection
include 'config.php';

// Fetch products from the database
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk WHERE id_kategori = 2";
$result = mysqli_query($conn, $query);
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
                        <div class="carousel-inner">
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
                        <a href="kat-kos.php">
                        <img src="  ./img/kategori/cate6.webp" alt="Kosmetik" class="img-size" />
                        <p class="text-center">Kosmeowtik</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- kategori akhir -->

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row text-center mt-5 mb-5">
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
            <div class="col">
                <a class="btn btn-outline-warning" href="UserSemuaProduk.php" role="button">Lihat Semua Produk</a>
            </div>
        </div>
    </div>
</div>


<?php include("UserLayouts/footer.php"); ?>