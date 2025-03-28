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

// Query untuk mendapatkan daftar pemesanan berdasarkan user_id
$query = "
    SELECT p.pemesanan_id, p.tanggal_pemesanan, p.jumlah_pemesanan, 
           pr.nama_produk, pr.harga, (p.jumlah_pemesanan * pr.harga) AS total_harga
    FROM pemesanan p
    INNER JOIN produk pr ON p.produk_id = pr.produk_id
    WHERE p.user_id = ?
    ORDER BY p.tanggal_pemesanan DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemesanan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Daftar Pemesanan Anda</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal Pemesanan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$no}</td>";
                        echo "<td>{$row['nama_produk']}</td>";
                        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                        echo "<td>{$row['jumlah_pemesanan']}</td>";
                        echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                        echo "<td>{$row['tanggal_pemesanan']}</td>";
                        echo "</tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Belum ada pemesanan</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
