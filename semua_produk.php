<?php
include 'UserLayouts/header.php';

// Use existing config.php for database connection
include 'config.php';

// Fetch products from the database
$query = "SELECT*FROM produk";
$result = mysqli_query($conn, $query);
?>

<!-- decor awal -->
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ff4400" fill-opacity="1" d="M0,96L40,112C80,128,160,160,240,154.7C320,149,400,107,480,117.3C560,128,640,192,720,202.7C800,213,880,171,960,133.3C1040,96,1120,64,1200,64C1280,64,1360,96,1400,112L1440,128L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z"></path></svg>
<!-- decor akhir -->

<div class="container-xxl flex-grow-1 container-p-y mt-5">
    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
            <?php 
            // Loop through products and create cards
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-sm-5 col-md-3 me-3">
                <div class="card h-100 hovered-card"> 
                    <a href="#">
                        <img class="card-img-top img-fluid mx-auto d-block" 
                            src="./product_picture/<?php echo htmlspecialchars($row['gambar_produk']); ?>" 
                            alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                        <p class="card-title">SMart</p>
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
</div>

