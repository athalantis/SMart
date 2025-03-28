<?php
session_start();
include 'config.php';
include '.includes/header.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Query untuk mendapatkan semua pemesanan
$query = "
    SELECT p.pemesanan_id, p.tanggal_pemesanan, p.jumlah_pemesanan, p.status_pemesanan, 
           pr.nama_produk, pr.harga, u.username, 
           (p.jumlah_pemesanan * pr.harga) AS total_harga
    FROM pemesanan p
    INNER JOIN produk pr ON p.produk_id = pr.produk_id
    INNER JOIN users u ON p.user_id = u.user_id
    ORDER BY p.tanggal_pemesanan DESC
";

$result = $conn->query($query);
?>

<div class="container mt-5">
    <h2 class="mb-4">Daftar Semua Pemesanan</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemesan</th>
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
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['nama_produk']}</td>";
                    echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                    echo "<td>{$row['jumlah_pemesanan']}</td>";
                    echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                    echo "<td>{$row['tanggal_pemesanan']}</td>";
                    echo "<td>
                            <span class='badge {$badgeClass}'>{$row['status_pemesanan']}</span>
                          </td>";
                    echo "<td>
                            <form method='post' action='update_status.php'>
                                <input type='hidden' name='pemesanan_id' value='{$row['pemesanan_id']}'>
                                <select name='status_pemesanan' class='form-select'>
                                    <option value='pending' " . ($row['status_pemesanan'] == 'pending' ? 'selected' : '') . ">Pending</option>
                                    <option value='confirmed' " . ($row['status_pemesanan'] == 'confirmed' ? 'selected' : '') . ">Confirmed</option>
                                    <option value='successful' " . ($row['status_pemesanan'] == 'successful' ? 'selected' : '') . ">Successful</option>
                                    <option value='cancelled' " . ($row['status_pemesanan'] == 'cancelled' ? 'selected' : '') . ">Cancelled</option>
                                </select>
                                <button type='submit' class='btn btn-primary btn-sm mt-1'>Update</button>
                            </form>
                          </td>";
                    echo "</tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>Belum ada pemesanan</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
</div>

<?php
$conn->close();
?>
