<?php
include 'config.php'; // Database connection

if (isset($_POST['action']) && $_POST['action'] == 'insert_pemesanan') {
    $produkId = (int)$_POST['produk_id'];
    $userId = (int)$_POST['user_id'];
    $tanggalPemesanan = date('Y-m-d'); // Current date
    $jumlahPemesanan = 1; // Assuming quantity is 1 for now
    $statusPemesanan = 'pending'; // Default status

    // Insert into pemesanan table
    $query = "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan)
              VALUES ($produkId, $userId, '$tanggalPemesanan', $jumlahPemesanan, '$statusPemesanan')";

    if (mysqli_query($conn, $query)) {
        echo 'Pemesanan berhasil dilakukan';
    } else {
        echo 'Terjadi kesalahan: ' . mysqli_error($conn);
    }
}
?>
