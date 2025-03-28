<?php
session_start();
include 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$tanggal_pemesanan = date("Y-m-d");

// Ambil parameter dari URL
if (!isset($_GET['produk_id']) || !isset($_GET['jumlah'])) {
    echo "<script>alert('Parameter tidak lengkap!'); window.location.href='dashboard.php';</script>";
    exit;
}

$produk_id = intval($_GET['produk_id']);
$jumlah_pemesanan = intval($_GET['jumlah']);

// Cek stok produk
$query = "SELECT stok FROM produk WHERE produk_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $produk_id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$stmt->close();

if (!$produk || $produk['stok'] < $jumlah_pemesanan) {
    echo "<script>alert('Stok tidak mencukupi!'); window.location.href='dashboard.php';</script>";
    exit;
}

// Insert ke tabel pemesanan
$query = "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iisi", $produk_id, $user_id, $tanggal_pemesanan, $jumlah_pemesanan);

if ($stmt->execute()) {
    // Kurangi stok produk
    $query = "UPDATE produk SET stok = stok - ? WHERE produk_id = ?";
    $stmt_update = $conn->prepare($query);
    $stmt_update->bind_param("ii", $jumlah_pemesanan, $produk_id);
    $stmt_update->execute();
    $stmt_update->close();

    echo "<script>alert('Pemesanan berhasil!'); window.location.href='dashboard.php';</script>";
} else {
    echo "<script>alert('Pemesanan gagal!'); window.location.href='dashboard.php';</script>";
}
$stmt->close();
?>
