<?php
include 'UserLayouts/header.php';

// Use existing config.php for database connection
include 'config.php';

// Fetch products from the database
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk WHERE recommendations = 1";
$result = mysqli_query($conn, $query);
?>

<br> <br>
<div class="container-xxl flex-grow-1 container-p-y mt-5">
    <div class="col-md-6 col-lg-12 mb-3">
        <div class="card">
            <h5 class="card-header">Quote</h5>
            <div class="card-body">
                <blockquote class="blockquote mb-0">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.Lorem
                        ipsum dolor sit amet, consectetur.
                    </p>
                    <footer class="blockquote-footer">
                        Someone famous in
                        <cite title="Source Title">Source Title</cite>
                    </footer>
                </blockquote>
            </div>
        </div>
    </div>
</div>