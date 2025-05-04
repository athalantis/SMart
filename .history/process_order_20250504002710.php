<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['produk_id']) || !isset($data['jumlah'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit();
}

// Sanitize and validate input
$produk_id = (int)$data['produk_id'];
$jumlah = (int)$data['jumlah'];
$user_id = (int)$_SESSION['user_id'];

if ($jumlah <= 0) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit();
}

// Check product existence and stock
$query = "SELECT stok FROM produk WHERE produk_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $produk_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

$row = mysqli_fetch_assoc($result);
$stok = (int)$row['stok'];

if ($jumlah > $stok) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
    exit();
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Update product stock
    $new_stock = $stok - $jumlah;
    $update_query = "UPDATE produk SET stok = ? WHERE produk_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, 'ii', $new_stock, $produk_id);
    mysqli_stmt_execute($update_stmt);

    // Insert order into pemesanan
    $order_query = "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan) VALUES (?, ?, CURDATE(), ?, 'pending')";
    $order_stmt = mysqli_prepare($conn, $order_query);
    mysqli_stmt_bind_param($order_stmt, 'iii', $produk_id, $user_id, $jumlah);
    mysqli_stmt_execute($order_stmt);

    // Commit transaction
    mysqli_commit($conn);

    echo json_encode(['success' => true, 'message' => 'Order placed successfully']);
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'message' => 'Error processing order: ' . $e->getMessage()]);
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($update_stmt);
mysqli_stmt_close($order_stmt);
mysqli_close($conn);
?>