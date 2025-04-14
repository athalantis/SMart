<?php
include '../IncludesUser/header.php';
include '.includes/toast_notification.php';

// Use existing config.php for database connection
include 'config.php';

// Fetch products from the database
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk";
$result = mysqli_query($conn, $query);
?>


    <!-- Navbar akhir -->

    <!-- banner awal -->
    <div class="container-fluid banner d-flex align-items-center">
        <div class="container">
            <p class="text-white text-center mb-1 fs-2">Selamat Datang Di Website Kami<i class="bi bi-emoji-smile"></i></p>
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
        <!-- Carousel Section -->
        <div class="row mb-5">
            <div class="col-md">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></li>
                        <li data-bs-target="#carouselExample" data-bs-slide-to="1"></li>
                        <li data-bs-target="#carouselExample" data-bs-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="./assets/img/element/pusat-olahraga.png" alt="First slide" />
                            <div class="carousel-caption d-none d-md-block">
                                <h3>First slide</h3>
                                <p>Eos mutat malis maluisset et, agam ancillae quo te, in vim congue pertinacia.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="./assets/img/element/kantor.png" alt="Second slide" />
                            <div class="carousel-caption d-none d-md-block">
                                <h3>Second slide</h3>
                                <p>In numquam omittam sea.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="./assets/img/element/dapur.png" alt="Third slide" />
                            <div class="carousel-caption d-none d-md-block">
                                <h3>Third slide</h3>
                                <p>Lorem ipsum dolor sit amet, virtute consequat ea qui, minim graeco mel no.</p>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="row text-center mb-4">
            <div class="col">
                <h2>Produk Tersedia</h2>
            </div>
        </div>

        <!-- Dynamic Product Cards with New Styling -->
        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
            <?php 
            // Loop through products and create cards
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-sm-5 col-md-3 me-3 mb-4">
                <div class="card h-100 hovered-card">
                    <a href="#">
                        <img class="card-img-top" 
                            src="./product_picture/<?php echo htmlspecialchars($row['gambar_produk']); ?>" 
                            alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" />
                        <p class="card-title">Toko Saya</p>
                        <div class="card-body">
                            <p class="card-text"><?php echo htmlspecialchars($row['nama_produk']); ?></p>
                            <p class="card-price">Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                            <p class="card-location">🚚 Tersedia</p>
                            <p class="card-rating">⭐ 4.9 | Stok: <?php echo intval($row['stok']); ?> tersedia</p>
                            <button type="button" 
                                    class="btn rounded-pill btn-primary w-100" 
                                    onclick="openOrderModal(<?php echo $row['produk_id']; ?>, <?php echo $row['stok']; ?>)">
                                Beli Sekarang
                            </button>
                        </div>
                    </a>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="row text-center">
            <div class="col mt-4">
                <a class="btn btn-outline-warning" href="#" role="button">Lihat Semua Produk</a>
            </div>
        </div>

        <hr class="my-5">
    </div>

    <!-- decor awal -->
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#FF4400" fill-opacity="1" d="M0,32L40,80C80,128,160,224,240,234.7C320,245,400,171,480,117.3C560,64,640,32,720,48C800,64,880,128,960,128C1040,128,1120,64,1200,53.3C1280,43,1360,85,1400,106.7L1440,128L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path></svg>
    <!-- decor akhir -->

   <?php
?>