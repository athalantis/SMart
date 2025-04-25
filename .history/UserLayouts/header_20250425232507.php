<!doctype html>
<html lang="en">
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

    <!-- Lightbox -->
    <link rel="stylesheet" href="../dist/css/lightbox.min.css">

    <title>toko saya</title>
    <style>
        .main-color {
            background-color: #ff4400;
        }
        .footer-color {
            background-color: #ff4400;
        }
        .footer-color2 {
            background-color: #282828;
        }
        .banner {
            height: 80vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/img/element/banner-bg.jpg');
            background-size: cover;
            background-position: center;
        }
        .hovered-card:hover {
            transform: scale(1.05);
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .hovered-card-service:hover {
            transform: scale(1.05);
            transition: all 0.3s;
        }
        .hovered-social:hover {
            transform: scale(1.2);
            transition: all 0.3s;
            cursor: pointer;
        }
        .icon-service {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.2);
        }
        .card-title {
            font-weight: 600;
            margin: 10px 0 0 10px;
            color: #ff4400;
        }
        .card-price {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card-location, .card-rating {
            font-size: 0.8rem;
            margin-bottom: 5px;
            color: #666;
        }
        .card a {
            text-decoration: none;
            color: inherit;
        }
        
        nav{
            padding-top: 100px;
        }
    </style>
</head>
<body>
    <!-- Navbar awal -->
    <!-- Navbar awal -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark shadow main-color">
    <div class="container">
        <a class="navbar-brand" href="#">Toko Saya</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-4">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'page' : ''; ?>" href="../dashboard.php">Home</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'rekomendasi.php') ? 'active' : ''; ?>" aria-current="<?php echo (basename($_SERVER['PHP_SELF']) == 'rekomendasi.php') ? 'page' : ''; ?>" href="../rekomendasi.php">rekomendasi</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link" href="promo.html">Promo</a>
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
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-chat-dots fs-4"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">About Us</a></li>
                        <li><a class="dropdown-item" href="#">Contact Us</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">FAQ</a></li>
                    </ul>
                </li>
            </ul>   
        </div>
    </div>
</nav>
    <!-- content -->