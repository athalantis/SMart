<?php
include (".includes/header.php");
$title = "Admin's Dashboard";
include '.includes/toast_notification.php';
?>

<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Chart Penjualan Bulanan -->
        <div class="bg-white shadow-md p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-3">Penjualan Bulanan</h2>
            <canvas id="salesChart"></canvas>
        </div>

        <!-- Chart Pendapatan -->
        <div class="bg-white shadow-md p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-3">Pendapatan Bulanan</h2>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dummy data penjualan
    const labels = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
    const salesData = [120, 190, 300, 500, 250, 400, 700, 900, 650, 800, 750, 950];
    const revenueData = salesData.map(sales => sales * 10000); // Pendapatan (contoh: harga 10.000 per unit)

    // Chart Penjualan Bulanan
    const ctx1 = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penjualan',
                data: salesData,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Chart Pendapatan Bulanan
    const ctx2 = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueData,
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
