<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST
    $produk_id = isset($_POST['produk_id']) ? intval($_POST['produk_id']) : 0;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0; // Pastikan kamu mendapatkan ini dari sesi/login
    $jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 1;

    // Validasi dasar
    if ($produk_id > 0 && $user_id > 0 && $jumlah > 0) {
        $tanggal = date('Y-m-d');
        $status = 'pending';

        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisds", $produk_id, $user_id, $tanggal, $jumlah, $status);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Pesanan berhasil ditambahkan.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan pesanan.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
}

$conn->close();
?>
