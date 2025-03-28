<?php
session_start();
include 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Pastikan `pemesanan_id` ada di URL
if (!isset($_GET['pemesanan_id'])) {
    echo "Pemesanan tidak ditemukan.";
    exit();
}

$pemesanan_id = intval($_GET['pemesanan_id']);

// Cek apakah pemesanan ini milik user yang sedang login
$query = "SELECT * FROM pemesanan WHERE pemesanan_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $pemesanan_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Pemesanan tidak ditemukan atau bukan milik Anda.";
    exit();
}

$pemesanan = $result->fetch_assoc();
$produk_id = $pemesanan['produk_id'];
$jumlah_pemesanan = $pemesanan['jumlah_pemesanan'];

// Mulai proses pembatalan (hapus pemesanan dan update stok produk)
$conn->begin_transaction();

try {
    // Hapus pemesanan
    $deleteQuery = "DELETE FROM pemesanan WHERE pemesanan_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $pemesanan_id);
    $stmt->execute();

    // Kembalikan stok produk
    $updateStokQuery = "UPDATE produk SET stok = stok + ? WHERE produk_id = ?";
    $stmt = $conn->prepare($updateStokQuery);
    $stmt->bind_param("ii", $jumlah_pemesanan, $produk_id);
    $stmt->execute();

    // Commit transaksi
    $conn->commit();

    echo "<script>
            alert('Pemesanan berhasil dibatalkan!');
            window.location.href = 'pemesanan.php';
          </script>";
} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('Gagal membatalkan pemesanan!');</script>";
}
?>
