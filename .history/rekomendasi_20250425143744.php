<?php
include 'UserLayouts/header.php';

// Use existing config.php for database connection
include 'config.php';

// Fetch products from the database
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk";
$result = mysqli_query($conn, $query);
?>