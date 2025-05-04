<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pesan') {
    $produkId = intval($_POST['produk_id']);

    $result = mysqli_query($conn, "SELECT * FROM produk WHERE produk_id = $produkId");
    $produk = mysqli_fetch_assoc($result);

    if ($produk && $produk['stok'] > 0) {
        $newStok = $produk['stok'] - 1;
        $newTerbeli = $produk['jumlah_terbeli'] + 1;

        $update = mysqli_query($conn, "UPDATE produk SET stok = $newStok, jumlah_terbeli = $newTerbeli WHERE produk_id = $produkId");

        if ($update) {
            echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal update produk.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Stok habis atau produk tidak ditemukan.']);
    }
    exit;
}

// GET: Tampilkan produk
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
