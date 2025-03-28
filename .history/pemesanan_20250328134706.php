<?php
session_start();
include 'config.php';
include '.includes/header.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect jika belum login
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pemesanan berdasarkan user_id
$query = "SELECT p.pemesanan_id, pr.nama_produk, p.jumlah, p.total_harga, p.status, p.created_at 
          FROM pemesanan p
          JOIN produk pr ON p.produk_id = pr.produk_id
          WHERE p.user_id = ?
          ORDER BY p.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Riwayat Pemesanan</h4>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Pemesanan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Tanggal Pemesanan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['pemesanan_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                    <td><?php echo intval($row['jumlah']); ?></td>
                    <td>Rp. <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '.includes/footer.php'; ?>
