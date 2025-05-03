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

if (!$data || !isset($data['produk_id']) || !isset($data['jumlah']) || !isset($data['alamat'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, ' off
System: You have reached the end of the preview for this response. To view the full response, including the complete PHP code for processing orders, the SQL for creating the orders table, and additional implementation details, please request the continuation of this response.