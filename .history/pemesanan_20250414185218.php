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
<br>  <br>  
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Riwayat Pemesanan Anda</h3>
        </div>
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = $result->fetch_assoc()):
                                $badgeClass = match ($row['status_pemesanan']) {
                                    'pending'    => 'bg-warning text-dark',
                                    'confirmed'  => 'bg-info text-dark',
                                    'successful' => 'bg-success',
                                    'cancelled'  => 'bg-danger',
                                    default      => 'bg-secondary'
                                };
                            ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><strong><?= $row['nama_produk'] ?></strong></td>
                                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                    <td><?= $row['jumlah_pemesanan'] ?></td>
                                    <td><strong>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></strong></td>
                                    <td><?= date('d M Y', strtotime($row['tanggal_pemesanan'])) ?></td>
                                    <td><span class="badge rounded-pill <?= $badgeClass ?>"><?= ucfirst($row['status_pemesanan']) ?></span></td>
                                    <td>
                                        <?php if ($row['status_pemesanan'] !== 'cancelled' && $row['status_pemesanan'] !== 'successful'): ?>
                                            <a href='batalkan_pemesanan.php?pemesanan_id=<?= $row['pemesanan_id'] ?>' 
                                               class='btn btn-danger btn-sm'
                                               onclick='return confirm("Apakah Anda yakin ingin membatalkan pemesanan ini?");'>
                                               Batalkan
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php 
                            $no++;
                            endwhile; 
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Order Summary Section -->
                <div class="mt-4">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Ringkasan Pemesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Total Pemesanan</h6>
                                            <h4 class="card-text"><?= $result->num_rows ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Status Pending</h6>
                                            <h4 class="card-text">
                                                <?php 
                                                $pendingCount = 0;
                                                $result->data_seek(0);
                                                while ($row = $result->fetch_assoc()) {
                                                    if ($row['status_pemesanan'] === 'pending') {
                                                        $pendingCount++;
                                                    }
                                                }
                                                echo $pendingCount;
                                                ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Status Selesai</h6>
                                            <h4 class="card-text">
                                                <?php 
                                                $successCount = 0;
                                                $result->data_seek(0);
                                                while ($row = $result->fetch_assoc()) {
                                                    if ($row['status_pemesanan'] === 'successful') {
                                                        $successCount++;
                                                    }
                                                }
                                                echo $successCount;
                                                ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Information Section -->
                <div class="alert alert-info mt-4" role="alert">
                    <h5 class="alert-heading">Informasi Penting!</h5>
                    <p>Status pemesanan akan diperbarui oleh admin. Jika ada pertanyaan tentang pesanan Anda, silakan hubungi customer service kami.</p>
                    <hr>
                    <p class="mb-0">Pembatalan pesanan hanya dapat dilakukan untuk pesanan dengan status "Pending" atau "Confirmed".</p>
                </div>
                
            <?php else: ?>
                <div class="alert alert-warning text-center" role="alert">
                    <h5>Belum ada pemesanan yang tercatat</h5>
                    <p class="mb-0">Mulai belanja sekarang untuk melihat riwayat pemesanan Anda di sini.</p>
                </div>
                
                <!-- Recommendation for empty cart -->
                <div class="card mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Rekomendasi Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Produk Terlaris</h5>
                                        <p class="card-text">Lihat produk-produk populer kami</p>
                                        <a href="produk_terlaris.php" class="btn btn-primary">Lihat Produk</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Produk Baru</h5>
                                        <p class="card-text">Jelajahi produk terbaru kami</p>
                                        <a href="produk_baru.php" class="btn btn-primary">Lihat Produk</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Promo Spesial</h5>
                                        <p class="card-text">Dapatkan diskon menarik hari ini</p>
                                        <a href="promo.php" class="btn btn-primary">Lihat Promo</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
            <div>
                <?php if ($result->num_rows > 0): ?>
                    <span class="text-muted">Total <?= $result->num_rows ?> pemesanan</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="helpModalLabel">Bantuan Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Status Pemesanan:</h6>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Pending
                        <span class="badge bg-warning text-dark">Menunggu konfirmasi</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Confirmed
                        <span class="badge bg-info text-dark">Pesanan dikonfirmasi</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Successful
                        <span class="badge bg-success">Pesanan selesai</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Cancelled
                        <span class="badge bg-danger">Pesanan dibatalkan</span>
                    </li>
                </ul>
                <h6>Informasi Lainnya:</h6>
                <p>Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi customer service kami di nomor: <strong>0812-3456-7890</strong> atau email ke <strong>cs@tokosaya.com</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Floating help button -->
<div class="position-fixed bottom-0 end-0 p-3">
    <button type="button" class="btn btn-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#helpModal">
        <i class="bi bi-question-lg"></i>
    </button>
    <br><br><br>
</div>

<?php
include 'UserLayouts/footer.php';

$stmt->close();
$conn->close();
?>

