<?php
include ".includes/header.php";
$title = "Tambah Produk";
include ".includes/toast_notification.php";
include "config.php"; // Koneksi database

// Ambil daftar distributor dari database
$distributor_query = "SELECT distributor_id, nama FROM distributor";
$distributor_result = $conn->query($distributor_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $distributor_id = $_POST['distributor_id'];
    $gambar = $_FILES['gambar_produk']['name'];
    $gambar_tmp = $_FILES['gambar_produk']['tmp_name'];
    $kategori = $_POST['id_kategori'];
    
    $upload_dir = "product_picture/";
    $gambar_path = time() . "_" . basename($gambar);
    
    if (move_uploaded_file($gambar_tmp, $upload_dir . $gambar_path)) {
        $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, stok, gambar_produk, distributor_id, id_kategori) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisii", $nama_produk, $harga, $stok, $gambar_path, $distributor_id, $kategori);

        if ($stmt->execute()) {
            redirect("products.php");
        } else {
            echo "<div class='alert alert-danger'>Gagal menambahkan produk.</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Gagal mengupload gambar.</div>";
    }
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body"></div>
<div class="container">
    <h2>Tambah Produk</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Distributor</label>
            <select name="distributor_id" class="form-control" required>
                <option value="" disabled selected>Pilih Distributor</option>
                <?php while ($row = $distributor_result->fetch_assoc()): ?>
                    <option value="<?= $row['distributor_id']; ?>"><?= htmlspecialchars($row['nama']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <select class="form-select" name="id_kategori" required>
                <!-- Mengambil data kategori dari database untuk mengisi opsi dropdown -->
                    <option value="" disabled selected>Pilih salah satu</option>
                    <?php
                        $query = "SELECT * FROM kategori"; // Sesuai nama tabel kamu
                        $result = $conn->query($query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["id_kategori"] . "'>" . $row["nama_kategori"] . "</option>";
                            }
                        }
                    ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Gambar Produk</label>
            <div id="drop-area" class="border border-2 border-secondary p-4 text-center rounded">
                <p>Drag & Drop gambar di sini atau klik untuk memilih</p>
                <input type="file" name="gambar_produk" id="gambar_produk" class="form-control d-none" accept="image/*" required>
                <img id="preview" src="#" alt="Preview" class="img-fluid mt-3 d-none" style="max-height: 200px;">
            </div>
        </div>
        <div class="mb-4">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="products.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
</div>
</div>
</div>
</div>

<?php include ".includes/footer.php"; ?>

<?php
function redirect($url) {
    echo "<script>window.location.href='$url';</script>";
    exit;
}
?>

<!-- Drag & Drop Script -->
<script>
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('gambar_produk');
    const previewImage = document.getElementById('preview');

    dropArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function () {
        handleFiles(this.files);
    });

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.classList.add('bg-light');
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.classList.remove('bg-light');
    });

    dropArea.addEventListener('drop', e => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFiles(files);
    });

    function handleFiles(files) {
        const file = files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
