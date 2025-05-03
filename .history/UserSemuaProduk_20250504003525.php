<?php
include 'UserLayouts/header.php';
include 'config.php'; // koneksi database

// Ambil produk yang direkomendasikan
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk";
$result = mysqli_query($conn, $query);
?>
p
