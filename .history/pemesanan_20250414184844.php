<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Pemesanan Anda</h3>
        </div>
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
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
                                               class='btn btn-outline-danger btn-sm'
                                               onclick='return confirm("Apakah Anda yakin ingin membatalkan pemesanan ini?");'>
                                               <i class="fas fa-times-circle me-1"></i>Batalkan
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
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>Belum ada pemesanan yang tercatat
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <a href="dashboard.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
                <?php if ($result->num_rows > 0): ?>
                <span class="text-muted">Total <?= $result->num_rows ?> pemesanan</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>