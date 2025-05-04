<?php
include 'config.php'; // Database connection
session_start(); // Start session for user_id

// Ensure user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo '<div class="col-12 text-center"><div class="alert alert-danger">Please log in to view products.</div></div>';
    exit;
}

// Get the search term and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$stockFilter = isset($_GET['stockFilter']) ? $_GET['stockFilter'] : '';
$purchasedFilter = isset($_GET['purchasedFilter']) ? $_GET['purchasedFilter'] : '';

// Start the query
$query = "SELECT produk_id, nama_produk, harga, stok, gambar_produk FROM produk";

// Apply search condition
if ($search) {
    $search = mysqli_real_escape_string($conn, $search);
    $query .= " WHERE nama_produk LIKE '%$search%'";
}

// Apply stock filter
if ($stockFilter) {
    if ($stockFilter == '1') {
        $query .= ($search ? " ORDER BY stok DESC" : " ORDER BY stok DESC");
    } else if ($stockFilter == '2') {
        $query .= ($search ? " ORDER BY stok ASC" : " ORDER BY stok ASC");
    }
}

// Apply purchased filter (based on pemesanan count)
if ($purchasedFilter) {
    $query = "SELECT p.produk_id, p.nama_produk, p.harga, p.stok, p.gambar_produk, COUNT(pm.pemesanan_id) as purchase_count 
              FROM produk p 
              LEFT JOIN pemesanan pm ON p.produk_id = pm.produk_id" . ($search ? " WHERE p.nama_produk LIKE '%$search%'" : "") . 
              " GROUP BY p.produk_id";
    if ($purchasedFilter == '1') {
        $query .= " ORDER BY purchase_count DESC";
    } else if ($purchasedFilter == '2') {
        $query .= " ORDER BY purchase_count ASC";
    }
}

// Execute the query
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hovered-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="col-sm-5 col-md-3 me-3 mb-4">
                        <div class="card h-100 hovered-card">
                            <a href="#">
                                <img class="card-img-top" 
                                     src="./product_picture/<?php echo htmlspecialchars($row['gambar_produk']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" 
                                     style="height: 200px; object-fit: cover; border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;" />
                                <p class="card-title text-center mt-2">Toko Saya</p>
                                <div class="card-body">
                                    <p class="card-text fw-bold"><?php echo htmlspecialchars($row['nama_produk']); ?></p>
                                    <p class="card-price text-success">Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                    <p class="card-location">🚚 Tersedia</p>
                                    <p class="card-rating">⭐ 4.9 | Stok: <?php echo intval($row['stok']); ?> tersedia</p>
                                    <button type="button" 
                                            class="btn rounded-pill btn-primary w-100" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#orderModal"
                                            onclick="setOrderModal(<?php echo $row['produk_id']; ?>, <?php echo $row['stok']; ?>, '<?php echo htmlspecialchars(str_replace("'", "\'", $row['nama_produk'])); ?>')">
                                        Beli Sekarang
                                    </button>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center"><div class="alert alert-info">Belum ada produk yang ditemukan.</div></div>';
            }
            ?>
        </div>
    </div>

    <!-- Order Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="orderForm">
                        <input type="hidden" id="produk_id" name="produk_id">
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="nama_produk" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_pemesanan" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="jumlah_pemesanan" name="jumlah_pemesanan" min="1" required>
                            <small class="form-text text-muted">Available stock: <span id="stok_tersedia"></span></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitOrder()">Order Now</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let maxStock = 0;

        function setOrderModal(produk_id, stok, nama_produk) {
            document.getElementById('produk_id').value = produk_id;
            document.getElementById('nama_produk').value = nama_produk;
            document.getElementById('stok_tersedia').textContent = stok;
            document.getElementById('jumlah_pemesanan').max = stok;
            maxStock = stok;
        }

        function submitOrder() {
            const produk_id = document.getElementById('produk_id').value;
            const jumlah_pemesanan = document.getElementById('jumlah_pemesanan').value;

            if (!jumlah_pemesanan || jumlah_pemesanan <= 0 || jumlah_pemesanan > maxStock) {
                alert('Please enter a valid quantity within available stock.');
                return;
            }

            $.ajax({
                url: 'fetch_products.php',
                type: 'POST',
                data: {
                    action: 'place_order',
                    produk_id: produk_id,
                    jumlah_pemesanan: jumlah_pemesanan
                },
                success: function(response) {
                    try {
                        const res = JSON.parse(response);
                        if (res.success) {
                            alert('Order placed successfully!');
                            $('#orderModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Error: ' + res.message);
                        }
                    } catch (e) {
                        alert('Error processing response: ' + response);
                    }
                },
                error: function() {
                    alert('Error processing order.');
                }
            });
        }
    </script>

    <?php
    // Handle AJAX order submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
        $response = ['success' => false, 'message' => ''];

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $response['message'] = 'User not logged in.';
            echo json_encode($response);
            exit;
        }

        $produk_id = intval($_POST['produk_id']);
        $jumlah_pemesanan = intval($_POST['jumlah_pemesanan']);
        $user_id = intval($_SESSION['user_id']);
        $tanggal_pemesanan = date('Y-m-d');

        // Verify stock with prepared statement
        $stock_stmt = mysqli_prepare($conn, "SELECT stok FROM produk WHERE produk_id = ?");
        mysqli_stmt_bind_param($stock_stmt, 'i', $produk_id);
        mysqli_stmt_execute($stock_stmt);
        $stock_result = mysqli_stmt_get_result($stock_stmt);
        $stock_row = mysqli_fetch_assoc($stock_result);

        if (!$stock_row || $stock_row['stok'] < $jumlah_pemesanan) {
            $response['message'] = 'Insufficient stock or product not found.';
            echo json_encode($response);
            exit;
        }

        // Insert into pemesanan with prepared statement
        $insert_stmt = mysqli_prepare($conn, "INSERT INTO pemesanan (produk_id, user_id, tanggal_pemesanan, jumlah_pemesanan, status_pemesanan) VALUES (?, ?, ?, ?, 'pending')");
        mysqli_stmt_bind_param($insert_stmt, 'iisi', $produk_id, $user_id, $tanggal_pemesanan, $jumlah_pemesanan);

        if (mysqli_stmt_execute($insert_stmt)) {
            // Update stock with prepared statement
            $update_stmt = mysqli_prepare($conn, "UPDATE produk SET stok = stok - ? WHERE produk_id = ?");
            mysqli_stmt_bind_param($update_stmt, 'ii', $jumlah_pemesanan, $produk_id);
            mysqli_stmt_execute($update_stmt);

            $response['success'] = true;
        } else {
            $response['message'] = 'Failed to place order: ' . mysqli_error($conn);
        }

        mysqli_stmt_close($stock_stmt);
        mysqli_stmt_close($insert_stmt);
        mysqli_stmt_close($update_stmt);

        echo json_encode($response);
        exit;
    }
    ?>

</body>
</html>