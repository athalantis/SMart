<?php
include ".includes/header.php";
$title = "Edit Produk";
include ".includes/toast_notification.php";
include "config.php"; // Koneksi database

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID tidak valid.</div>";
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM produk WHERE produk_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$stmt->close();

if (!$produk) {
    echo "<div class='alert alert-danger'>Produk tidak ditemukan.</div>";
    exit;
}

// Ambil daftar distributor
$distributor_query = "SELECT distributor_id, nama FROM distributor";
$distributor_result = $conn->query($distributor_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $distributor_id = $_POST['distributor_id'];

    if (!empty($_FILES['gambar_produk']['name'])) {
        $gambar = $_FILES['gambar_produk']['name'];
        $gambar_tmp = $_FILES['gambar_produk']['tmp_name'];
        $upload_dir = "product_picture/";
        $gambar_path = time() . "_" . basename($gambar);

        if (move_uploaded_file($gambar_tmp, $upload_dir . $gambar_path)) {
            if (!empty($produk['gambar_produk'])) {
                unlink($upload_dir . $produk['gambar_produk']);
            }
            $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, stok=?, gambar_produk=?, distributor_id=? WHERE produk_id=?");
            $stmt->bind_param("siisii", $nama_produk, $harga, $stok, $gambar_path, $distributor_id, $id);
        }
    } else {
        $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, stok=?, distributor_id=? WHERE produk_id=?");
        $stmt->bind_param("siiii", $nama_produk, $harga, $stok, $distributor_id, $id);
    }

    if ($stmt->execute()) {
        redirect("products.php");
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui produk.</div>";
    }
    $stmt->close();
}
?>

<div class="container mt-4">
    <h2>Edit Produk</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $produk['harga'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $produk['stok'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Distributor</label>
            <select name="distributor_id" class="form-control" required>
                <option value="" disabled>Pilih Distributor</option>
                <?php while ($row = $distributor_result->fetch_assoc()): ?>
                    <option value="<?= $row['distributor_id']; ?>" <?= ($row['distributor_id'] == $produk['distributor_id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($row['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Gambar Produk (Kosongkan jika tidak diubah)</label>
            <div id="drop-area" class="border border-2 border-secondary p-4 text-center rounded">
                <p>Drag & Drop gambar di sini atau klik untuk memilih</p>
                <input type="file" name="gambar_produk" id="gambar_produk" class="form-control d-none" accept="image/*">
                <img id="preview" src="product_picture/<?= htmlspecialchars($produk['gambar_produk']) ?>" alt="Preview" class="img-fluid mt-3" style="max-height: 200px;">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="products.php" class="btn btn-secondary">Batal</a>
    </form>
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
