<?php
include 'config.php';

// Initialize response array
$response = ['status' => 'error', 'message' => ''];

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $produk_id = isset($_POST['produk_id']) ? intval($_POST['produk_id']) : 0;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $jumlah_pemesanan = isset($_POST['jumlah_pemesanan']) ? intval($_POST['jumlah_pemesanan']) : 0;
    
    // Validate data
    if ($produk_id <= 0 || $user_id <= 0 || $jumlah_pemesanan <= 0) {
        $response['message'] = 'Data pemesanan tidak valid.';
        echo json_encode($response);
        exit;
    }
    
    // Check if user exists
    $userQuery = "SELECT * FROM users WHERE user_id = $user_id";
    $userResult = mysqli_query($conn, $userQuery);
    
    if (mysqli_num_rows($userResult) == 0) {
        $response['message'] = 'User tidak ditemukan.';
        echo json_encode($response);
        exit;
    }
    
    // Check if product exists and has enough stock
    $productQuery = "SELECT stok FROM produk WHERE produk_id = $produk_id";
    $productResult = mysqli_query($conn, $productQuery);
    
    if (mysqli_num_rows($productResult) == 0) {
        $response['message'] = 'Produk tidak ditemukan.';
        echo json_encode($response);
        exit;
    }
    
    $productData = mysqli_fetch_assoc($productResult);
    
    if ($productData['stok'] < $jumlah_pemesanan) {
        $response['message'] = 'Stok produk tidak mencukupi.';
        echo json_encode($response);
        exit;
    }
    
    // Begin transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Set current date
        $tanggal_pemesanan = date('Y-m-d');
        
        // Insert into pemesanan table
        $insertQuery = "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan) 
                      VALUES ($produk_id, $user_id, '$tanggal_pemesanan', $jumlah_pemesanan, 'pending')";
        
        if (!mysqli_query($conn, $insertQuery)) {
            throw new Exception("Gagal menambahkan pemesanan: " . mysqli_error($conn));
        }
        
        // Update product stock
        $updateStockQuery = "UPDATE produk SET stok = stok - $jumlah_pemesanan WHERE produk_id = $produk_id";
        
        if (!mysqli_query($conn, $updateStockQuery)) {
            throw new Exception("Gagal memperbarui stok produk: " . mysqli_error($conn));
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        // Return success response
        $response['status'] = 'success';
        $response['message'] = 'Pemesanan berhasil dibuat.';
        echo json_encode($response);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        
        $response['message'] = $e->getMessage();
        echo json_encode($response);
    }
    
} else {
    $response['message'] = 'Metode request tidak valid.';
    echo json_encode($response);
}

mysqli_close($conn);
?>