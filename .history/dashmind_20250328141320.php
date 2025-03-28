<?php
include 'config.php';
include '.includes/header.php';
$title = "Admin's Dashboard";
include '.includes/toast_notification.php';

// Query untuk menghitung jumlah pemesanan berdasarkan status
$query = "
    SELECT status_pemesanan, COUNT(*) as jumlah 
    FROM pemesanan 
    GROUP BY status_pemesanan
";

$result = mysqli_query($conn, $query);

// Menyusun data untuk Chart.js
$status_labels = [];
$status_counts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $status_labels[] = ucfirst($row['status_pemesanan']); // Capitalize status
    $status_counts[] = $row['jumlah'];
}

mysqli_close($conn);
?>

<div class="container mt-4">
    <h2 class="mb-3 text-center">Dashboard Admin</h2>

    <div class="row">
        <!-- Pie Chart -->
        <div class="col-md-6">
            <h4 class="text-center">Statistik Pemesanan</h4>
            <canvas id="orderChart"></canvas>
        </div>

        <!-- Dummy Data Produk Terlaris -->
        <div class="col-md-6">
            <h4 class="text-center">Produk Terlaris</h4>
            <ul class="list-group">
                <?php
                // Dummy data produk
                $products = [
                    ["name" => "Laptop", "sold" => 120],
                    ["name" => "Smartphone", "sold" => 200],
                    ["name" => "Headphone", "sold" => 80],
                    ["name" => "Smartwatch", "sold" => 50],
                    ["name" => "Tablet", "sold" => 90]
                ];

                foreach ($products as $product) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                    echo '<img src="https://via.placeholder.com/50" alt="Product Image" class="rounded me-2">';
                    echo '<span>' . $product["name"] . '</span>';
                    echo '<span class="badge bg-primary">' . $product["sold"] . ' terjual</span>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('orderChart').getContext('2d');

    var statusLabels = <?php echo json_encode($status_labels); ?>;
    var statusCounts = <?php echo json_encode($status_counts); ?>;

    var orderChart = new Chart(ctx, {
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
