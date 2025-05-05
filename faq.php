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
    <div class="col-md-6 col-lg-12 mb-3 hovered-card-service">
        <div class="card">
            <h4 class="card-header">Q. Apa itu SMART?</h4>
            <div class="card-body">
                <blockquote class="blockquote mb-0">
                    <p>
                        SMART adalah aplikasi supermarket daring yang memungkinkan pengguna untuk belanja kebutuhan harian secara praktis, cepat, dan aman langsung dari ponsel atau perangkat mereka.
                    </p>
                    <footer class="blockquote-footer">
                        Admin, 2019
                        <cite title="Source Title">Pemuda Biru Corp.</cite>
                    </footer>
                </blockquote>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-12 mb-3 hovered-card-service">
        <div class="card">
            <h4 class="card-header">Q. Bagaimana cara mendaftar di SMART?</h4>
            <div class="card-body">
                <blockquote class="blockquote mb-0">
                    <p>
                    Kamu dapat mendaftar menggunakan email atau nomor ponsel. Setelah itu, cukup ikuti langkah verifikasi dan mulai berbelanja.
                    </p>
                    <footer class="blockquote-footer">
                        Admin, 2019
                        <cite title="Source Title">Pemuda Biru Corp.</cite>
                    </footer>
                </blockquote>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-12 mb-3 hovered-card-service">
        <div class="card">
            <h4 class="card-header">Q. Apa saja metode pembayaran yang tersedia?</h4>
            <div class="card-body">
                <blockquote class="blockquote mb-0">
                    <p>
                    SMART menerima pembayaran melalui transfer bank, e-wallet (OVO, DANA, GoPay, dsb.), kartu kredit/debit, dan metode bayar di tempat (COD) untuk area tertentu.
                    </p>
                    <footer class="blockquote-footer">
                        Admin, 2019
                        <cite title="Source Title">Pemuda Biru Corp.</cite>
                    </footer>
                </blockquote>
            </div>
        </div>
    </div>
</div>