<?php
include "config.php"; // Koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['produk_id'], $_POST['recommendations'])) {
    $produk_id = $_POST['produk_id'];
    $recommendations = $_POST['recommendations'];

    // Update status rekomendasi
    $query = "UPDATE produk SET recommendations = ? WHERE produk_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $recommendations, $produk_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Status rekomendasi berhasil diperbarui."]);
    } else {
        echo json_encode(["message" => "Gagal memperbarui status rekomendasi."]);
    }

    $stmt->close();
}
?>
