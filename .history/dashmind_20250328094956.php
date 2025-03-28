<?php
include (".includes/header.php");
$title = "Admin's Dashboard";
include '.includes/toast_notification.php';
?>

<div class="container mt-4">
    <h2 class="mb-3 text-center">Dashboard Admin</h2>

    <div class="row">
        <!-- Chart -->
        <div class="col-md-6">
            <canvas id="salesChart"></canvas>
        </div>

        <!-- Dummy Data Produk -->
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

                // Loop untuk menampilkan daftar produk
                for ($i = 0; $i < count($products); $i++) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                    echo '<img src="https://via.placeholder.com/50" alt="Product Image" class="rounded me-2">';
                    echo '<span>' . $products[$i]["name"] . '</span>';
                    echo '<span class="badge bg-primary">' . $products[$i]["sold"] . ' terjual</span>';
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
    var ctx = document.getElementById('salesChart').getContext('2d');

    var productNames = <?php echo json_encode(array_column($products, 'name')); ?>;
    var productSales = <?php echo json_encode(array_column($products, 'sold')); ?>;

    var salesChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: productNames,
            datasets: [{
                label: 'Produk Terjual',
                data: productSales,
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

<?php include (".includes/footer.php"); ?>
