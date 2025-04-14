<?php
session_start();
include 'config.php';
include 'UserLayouts/header.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect jika belum login
    exit();
}

$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan daftar pemesanan berdasarkan user_id
$query = "
    SELECT p.pemesanan_id, p.tanggal_pemesanan, p.jumlah_pemesanan, p.status_pemesanan, 
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
        <th>Status Pemesanan</th>
        <th>Aksi</th>
    </tr>
</thead>

            <tbody>
    <?php 
    if ($result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            $badgeClass = match ($row['status_pemesanan']) {
                'pending'    => 'bg-warning',
                'confirmed'  => 'bg-primary',
                'successful' => 'bg-success',
                'cancelled'  => 'bg-danger',
                default      => 'bg-secondary'
            };

            echo "<tr>";
            echo "<td>{$no}</td>";
            echo "<td>{$row['nama_produk']}</td>";
            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
            echo "<td>{$row['jumlah_pemesanan']}</td>";
            echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
            echo "<td>{$row['tanggal_pemesanan']}</td>";
            echo "<td><span class='badge {$badgeClass}'>{$row['status_pemesanan']}</span></td>";

            if ($row['status_pemesanan'] !== 'cancelled' && $row['status_pemesanan'] !== 'successful') {
                echo "<td>
                        <a href='batalkan_pemesanan.php?pemesanan_id={$row['pemesanan_id']}' 
                           class='btn btn-danger btn-sm'
                           onclick='return confirm(\"Apakah Anda yakin ingin membatalkan pemesanan ini?\");'>
                           Batalkan Pemesanan
                        </a>
                      </td>";
            } else {
                echo "<td>-</td>"; // Tidak ada tombol jika sudah cancelled atau successful
            }
            
            echo "</tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'>Belum ada pemesanan</td></tr>";
    }
    ?>
</tbody>


        </table>
        <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
    </div>

<?php

$stmt->close();
$conn->close();
?>

