<?php
session_start();
include 'config.php';
include '.includes/header.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect jika belum login
    exit();
}

// Proses update status pemesanan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pemesanan_id'], $_POST['status_pemesanan'])) {
    $pemesanan_id = $_POST['pemesanan_id'];
    $status_pemesanan = $_POST['status_pemesanan'];

    $query_update = "UPDATE pemesanan SET status_pemesanan = ? WHERE pemesanan_id = ?";
    $stmt = $conn->prepare($query_update);
    $stmt->bind_param("si", $status_pemesanan, $pemesanan_id);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = ["type" => "success", "message" => "Status pemesanan berhasil diperbarui."];
    } else {
        $_SESSION['flash_message'] = ["type" => "error", "message" => "Gagal memperbarui status pemesanan."];
    }

    $stmt->close();
}

// Query untuk mendapatkan semua pemesanan
$query = "
SELECT p.pemesanan_id, p.tanggal_pemesanan, p.jumlah_pemesanan, p.status_pemesanan, 
       pr.nama_produk, pr.harga, pr.recommendations, pr.produk_id,
       u.username, 
       (p.jumlah_pemesanan * pr.harga) AS total_harga
FROM pemesanan p
INNER JOIN produk pr ON p.produk_id = pr.produk_id
INNER JOIN users u ON p.user_id = u.user_id
ORDER BY p.tanggal_pemesanan DESC
";
$result = mysqli_query($conn, $query);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Semua Pemesanan</h5>
        </div>

        <!-- Notifikasi Flash Message -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?> mt-3">
                <?= $_SESSION['flash_message']['message'] ?>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <div class="table-responsive text-nowrap">
            <table class="table">
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
                    if (mysqli_num_rows($result) > 0) {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $badgeClass = match ($row['status_pemesanan']) {
                                'pending'    => 'bg-warning',
                                'confirmed'  => 'bg-primary',
                                'successful' => 'bg-success',
                                'cancelled'  => 'bg-danger',
                                default      => 'bg-secondary'
                            };

                            echo "<tr>";
                            echo "<td>{$no}</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_produk']) . "</td>";
                            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                            echo "<td>{$row['jumlah_pemesanan']}</td>";
                            echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                            echo "<td>{$row['tanggal_pemesanan']}</td>";
                            echo "<td>
                                    <span class='badge {$badgeClass}'>" . htmlspecialchars($row['status_pemesanan']) . "</span>
                                  </td>";
                            echo "<td>
                                    <form method='post'>
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
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // AJAX untuk mengupdate status rekomendasi
    $(document).on('change', '.recommendation-toggle', function() {
        var productId = $(this).data('id');
        var recommendationStatus = $(this).prop('checked') ? 1 : 0;

        $.ajax({
            url: 'update_recommendation.php',
            type: 'POST',
            data: {
                produk_id: productId,
                recommendations: recommendationStatus
            },
            success: function(response) {
                var data = JSON.parse(response);  // Parsing the response from update_recommendation.php
                if (data.success) {
                    // Reload page to reflect updated data
                    window.location.reload();
                } else {
                    alert('Gagal memperbarui status rekomendasi.');
                }
            },
            error: function() {
                alert('Gagal menghubungi server.');
            }
        });
    });
</script>

<?php
mysqli_close($conn);
include '.includes/footer.php';
?>
