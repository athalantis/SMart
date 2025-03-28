<?php
include 'config.php';
include '.includes/header.php';
$title = "Admin's Dashboard";
include '.includes/toast_notification.php';

// Query untuk menghitung jumlah pemesanan berdasarkan status
$query_status = "
    SELECT status_pemesanan, COUNT(*) as jumlah 
    FROM pemesanan 
    GROUP BY status_pemesanan
";
$result_status = mysqli_query($conn, $query_status);

// Menyusun data untuk Chart.js (Pie Chart Status)
$status_labels = [];
$status_counts = [];

while ($row = mysqli_fetch_assoc($result_status)) {
    $status_labels[] = ucfirst($row['status_pemesanan']); // Capitalize status
    $status_counts[] = $row['jumlah'];
}

// Query untuk mendapatkan produk terlaris berdasarkan jumlah pemesanan
$query_top_products = "
    SELECT pr.nama_produk, SUM(p.jumlah_pemesanan) AS total_terjual
    FROM pemesanan p
    INNER JOIN produk pr ON p.produk_id = pr.produk_id
    GROUP BY pr.nama_produk
    ORDER BY total_terjual DESC
    LIMIT 5
";
$result_top_products = mysqli_query($conn, $query_top_products);

mysqli_close($conn);
?>

<div class="container mt-4">
    <h2 class="mb-3 text-center">Dashboard Admin</h2>

    <div class="row">
        <!-- Pie Chart: Status Pemesanan -->
        <div class="col-md-6">
            <h4 class="text-center">Statistik Pemesanan</h4>
            <canvas id="orderChart"></canvas>
        </div>

        <!-- Produk Terlaris -->
        <div class="col-md-6">
            <h4 class="text-center">Produk Terlaris</h4>
            <div class="mt-3 p-3 border rounded shadow-sm">
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($result_top_products)) {
                    echo "<p class='mb-2'><strong>{$no}. " . htmlspecialchars($row['nama_produk']) . "</strong> - {$row['total_terjual']} terjual</p>";
                    $no++;
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Pie Chart - Status Pemesanan
    var ctx1 = document.getElementById('orderChart').getContext('2d');
    var statusLabels = <?php echo json_encode($status_labels); ?>;
    var statusCounts = <?php echo json_encode($status_counts); ?>;
    
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                label: 'Jumlah Pemesanan',
                data: statusCounts,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#8E44AD'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
});
</script>

<?php include '.includes/footer.php'; ?>
