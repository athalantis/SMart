<?php
include("config.php"); // Koneksi ke database
include(".includes/header.php");

$title = "Admin's Dashboard";
include('.includes/toast_notification.php');

// Handle hapus produk
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus_produk'])) {
    $id = $_POST['produk_id'];

    // Ambil gambar
    $stmt = $conn->prepare("SELECT gambar_produk FROM produk WHERE produk_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();
    $stmt->close();

    if ($produk && !empty($produk['gambar_produk'])) {
        @unlink("product_picture/" . $produk['gambar_produk']);
    }

    // Hapus produk
    $stmt = $conn->prepare("DELETE FROM produk WHERE produk_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Produk berhasil dihapus.'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus produk.</div>";
    }
    $stmt->close();
}

// Query produk + distributor
$query = "SELECT p.produk_id, p.nama_produk, p.harga, p.stok, p.gambar_produk, d.nama AS nama_distributor, p.recommendations
          FROM produk p
          LEFT JOIN distributor d ON p.distributor_id = d.distributor_id";
$result = $conn->query($query);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                <h2>Daftar Produk</h2>
                    <a href="product_create.php" class="btn btn-primary mb-3">Tambah Produk</a>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th >ID</th>
                                    <th>Nama Produk</th>
                                    <th>Distributor</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Gambar</th>
                                    <th>Rekomendasi</th> 
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><?= $row['produk_id'] ?></td>
                                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_distributor'] ?? 'Tidak Ada') ?></td>
                                        <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                                        <td><?= $row['stok'] ?></td>
                                        <td>
                                            <?php if (!empty($row['gambar_produk'])) : ?>
                                                <img src="product_picture/<?= htmlspecialchars($row['gambar_produk']) ?>" width="80" alt="Produk">
                                            <?php else : ?>
                                                <span class="text-muted">Tidak ada gambar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="recommendation-toggle" data-id="<?= $row['produk_id'] ?>" <?= $row['recommendations'] ? 'checked' : '' ?>>
                                        </td>
                                        <td>
                                            <a href="product_edit.php?id=<?= $row['produk_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="produk_id" value="<?= $row['produk_id'] ?>">
                                                <button type="submit" name="hapus_produk" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?');">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // AJAX toggle rekomendasi
    document.querySelectorAll('.recommendation-toggle').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const produk_id = this.dataset.id;
            const recommendations = this.checked ? 1 : 0;

            fetch('update_recommendation.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({produk_id, recommendations})
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
            })
            .catch(() => {
                alert("Gagal memperbarui status rekomendasi.");
            });
        });
    });
</script>

<?php include(".includes/footer.php"); ?>
