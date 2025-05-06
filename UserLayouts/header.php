<!doctype html>
<html lang="en" class="background-color">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- CSS tambahan -->
    <link href="website.css" rel="stylesheet">

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"/>
    
    <!-- icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Lightbox -->
    <link rel="stylesheet" href="../dist/css/lightbox.min.css">

    <title>toko saya</title>
</head>

<body class="background-color">
    <!-- Navbar awal -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark shadow main-color">
        <div class="container">
            <a class="navbar-brand" href="#">SMart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-4">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'page' : ''; ?>" href="./dashboard.php">Home</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'UserSemuaProduk.php') ? 'active' : ''; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'UserSemuaProduk.php') ? 'page' : ''; ?>" href="./UserSemuaProduk.php">Semua Produk</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'pemesanan.php') ? 'active' : ''; ?>" href="pemesanan.php">
                            <i class="bi bi-cart2 fs-4"></i>
                            <?php if (basename($_SERVER['PHP_SELF']) == 'pemesanan.php'): ?>
                                <span class="position-absolute top-50 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                    <span class="visually-hidden">Order History</span>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li> 
                    <li class="nav-item me-4">
                <a class="nav-link" href="review.php"
                    ><i class="bi bi-envelope-plus fs-4"></i
                ></a>
                </li>
                    <a href="auth/logout.php" style="color: white;">Logout</a>
                </ul>   
            </div>
        </div>
    </nav>
    <!-- content -->