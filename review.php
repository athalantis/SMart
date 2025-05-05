<!DOCTYPE html>
    <html lang="en">
        <head>
        <!-- Required meta tags -->
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />

            <!-- Bootstrap CSS -->
            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
                rel="stylesheet"
                integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
                crossorigin="anonymous"
            />

            <!-- CSS tambahan -->
            <link href="website.css" rel="stylesheet" />

            <!-- font -->
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
            <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet"
            />

            <!-- icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
        </head>

        <body>
        <!-- Navbar awal -->
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark shadow main-color">
            <div class="container">
                <a class="navbar-brand" href="#">Toko Saya</a>
                    <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item me-4">
                                <a class="nav-link" aria-current="page" href="dashboard.php">Home</a>
                            </li>
                            <li class="nav-item me-4">
                                <a class="nav-link" href="UserSemuaProduk.php">Semua Produk</a>
                            </li>
                            <li class="nav-item me-4">
                                <a href="pemesanan.php">
                                <div class="cart-icon position-relative nav-link" href="pemesanan.php">
                                    <i class="bi bi-cart2 fs-4"></i>
                                    <span class="icon position-absolute">0</span>
                                </div>
                                </a>
                            </li>
                            <li class="nav-item me-4">
                                <a class="nav-link active" href="review.php"><i class="bi bi-envelope-plus fs-4"></i></a>
                            </li>
                        </ul>
                    </div>
            </div>
        </nav>
        <!-- Navbar akhir -->

        <!-- decor awal -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path
                fill="#ff4400"
                fill-opacity="1"
                d="M0,256L48,229.3C96,203,192,149,288,160C384,171,480,245,576,245.3C672,245,768,171,864,133.3C960,96,1056,96,1152,117.3C1248,139,1344,181,1392,202.7L1440,224L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"
            ></path>
        </svg>
        <!-- decor akhir -->

        <!-- form awal -->
        <div class="form">
            <div class="container-fluid py-5">
                <div class="container">
                    <h2 class="text-center">Tulis Ulasan Anda</h2>
                    <hr class="col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-0" style="border: 1px solid" />
                    <form method="POST" action="dashboard.php" id="feedbackForm" class="col-sm-8 offset-sm-2 col-md-6 offset-md-3">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pengirim</label>
                        <input type="text" class="form-control" id="nama" placeholder="nama" name="nama" required />
                    </div>
                    <div class="mb-3">
                        <label for="pesan-singkat" class="pesan-singkat">Pesan Singkat</label>
                        <input type="text" class="form-control" id="pesan-singkat" name="pesan-singkat" placeholder="Pesan Singkat" />
                    </div>
                    <div class="mb-3">
                        <label for="pesan" class="form-label">Kritik dan Pesan</label>
                        <textarea class="form-control" id="pesan" rows="3" name="pesan" required></textarea>
                    </div>
                    <input class="btn btn-outline-warning w-100 mt-2" type="submit" value="kirim" />
                    </form>
                </div>
            </div>
        </div>
        <!-- form akhir -->

        <!-- decor awal -->
        <div class="bg">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#FF4400" fill-opacity="1" d="M0,32L40,80C80,128,160,224,240,234.7C320,245,400,171,480,117.3C560,64,640,32,720,48C800,64,880,128,960,128C1040,128,1120,64,1200,53.3C1280,43,1360,85,1400,106.7L1440,128L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path></svg>
        </div>
        <!-- decor akhir -->

        <!-- footer awal -->
        <div class="container-fluid py-3 footer-color">
            <div class="container">
                <h3 class="text-center text-white mb-2">Sosial Media</h3>
                <div class="row justify-content-center text-white">
                    <div class="col-sm-1 col-md-1 fs-4 d-flex justify-content-center hovered-social" style="margin-bottom: -5px">
                        <i class="bi bi-facebook"></i>
                    </div>
                    <div class="col-sm-1 col-md-1 fs-4 d-flex justify-content-center hovered-social" style="margin-bottom: -5px">
                        <i class="bi bi-instagram"></i>
                    </div>
                    <div class="col-sm-1 col-md-1 fs-4 d-flex justify-content-center hovered-social">
                        <i class="bi bi-youtube" style="margin-bottom: -5px"></i>
                    </div>
                    <div class="col-sm-1 col-md-1 fs-4 d-flex justify-content-center hovered-social">
                        <i class="bi bi-twitter-x" style="margin-bottom: -5px"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-3 footer-color2">
            <div class="container">
                <h6 class="text-center mt-2" style="color: #7a7a7a">Website Made With Group 14</h6>
            </div>
        </div>
        <!-- footer akhir -->

        <!-- JS Tambahan -->
        <script src="website.js"></script>

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"
        ></script>
    </body>
</html>