<?php
include(".includes/header.php");
$title = "Dashboard";
include '.includes/toast_notification.php';

// Use existing config.php for database connection
include 'config.php';

// Fetch products from the database
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk";
$result = mysqli_query($conn, $query);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Carousel Section (Unchanged) -->
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
    
    <hr class="my-5">

    <!-- Dynamic Product Cards -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php 
        // Loop through products and create cards
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="col">
            <div class="card h-100">
                <img class="card-img-top" 
                     src="./product_picture/<?php echo htmlspecialchars($row['gambar_produk']); ?>" 
                     alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" />
                <div class="card-body">
                    <h4 class="card-title">Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></h4>
                    <h5 class="card-title"><?php echo htmlspecialchars($row['nama_produk']); ?></h5>
                    <p class="card-text">Stok: <?php echo intval($row['stok']); ?> tersedia</p>
                    <script>
function confirmOrder(produkId, stok) {
    let jumlah = prompt("Masukkan jumlah pemesanan:", "1");
    if (jumlah !== null && jumlah !== "" && !isNaN(jumlah) && jumlah > 0 && jumlah <= stok) {
        window.location.href = "create_pemesanan.php?produk_id=" + produkId + "&jumlah=" + jumlah;
    } else {
        alert("Jumlah tidak valid!");
    }
}
</script>

<button onclick="confirmOrder(<?php echo $row['produk_id']; ?>, <?php echo $row['stok']; ?>)" 
        class="btn rounded-pill btn-primary">
    Beli Sekarang
</button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <hr class="my-5">

    <!-- Rest of the existing code remains the same -->
    <div class="card">
        <h5 class="card-header">Table Basic</h5>
        <div class="table-responsive text-nowrap">
            <!-- Existing table code -->
            <table class="table">
                <!-- Table content unchanged -->
            </table>
        </div>
    </div>
</div>

<?php 
include (".includes/footer.php");
?>