    <?php
    include 'config.php';

    $search = $_GET['search'] ?? '';
    $stockFilter = $_GET['stockFilter'] ?? '';
    $purchasedFilter = $_GET['purchasedFilter'] ?? '';

    $query = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%'";

    if ($stockFilter == '1') {
        $query .= " ORDER BY stok DESC";
    } elseif ($stockFilter == '2') {
        $query .= " ORDER BY stok ASC";
    }

    if ($purchasedFilter == '1') {
        $query .= ", jumlah_terbeli DESC";
    } elseif ($purchasedFilter == '2') {
        $query .= ", jumlah_terbeli ASC";
    }

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $gambar = !empty($row['gambar_produk']) ? $row['gambar_produk'] : 'default.png';
        $stok = $row['stok'];
        $disabled = $stok <= 0 ? 'disabled' : '';

        echo "<div class='col mb-4'>
            <div class='card'>
                <img src='uploads/{$gambar}' class='card-img-top' alt='{$row['nama_produk']}'>
                <div class='card-body'>
                    <h5 class='card-title'>{$row['nama_produk']}</h5>
                    <p class='card-text'>Stok: {$stok}</p>
                    <button class='btn btn-primary btn-product-details' data-product-id='{$row['produk_id']}'>Lihat Detail</button>
                    <button class='btn btn-success btn-pesan-sekarang' data-produk-id='{$row['produk_id']}' {$disabled}>Pesan Sekarang</button>
                </div>
            </div>
        </div>";
    }
    ?>
