<?php
include 'config.php'; // Menggunakan koneksi dari config.php
include '.includes/header.php';

$title = "Admin's Dashboard";
include '.includes/toast_notification.php';

// Handle form tambah distributor
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST["nama"];
    $kontak = $_POST["kontak"];

    $query = "INSERT INTO distributor (nama, kontak) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nama, $kontak);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// Ambil data distributor
$result = $conn->query("SELECT * FROM distributor");
$distributors = [];
while ($row = $result->fetch_assoc()) {
    $distributors[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function openModal() {
            let modal = new bootstrap.Modal(document.getElementById("modalTambah"));
            modal.show();
        }

        function tambahDistributor() {
            let formData = new FormData(document.getElementById("formTambah"));

            fetch("distributor.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "success") {
                    alert("Distributor berhasil ditambahkan!");
                    location.reload();
                } else {
                    alert("Gagal menambahkan distributor!");
                }
            });
        }
    </script>
</head>
<body class="container mt-4">
    <h2 class="mb-3">Daftar Distributor</h2>
    
    <button onclick="openModal()" class="btn btn-primary">Tambah Distributor</button>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kontak</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($distributors as $d): ?>
                <tr>
                    <td><?= $d['distributor_id'] ?></td>
                    <td><?= htmlspecialchars($d['nama']) ?></td>
                    <td><?= htmlspecialchars($d['kontak']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal Tambah Distributor -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Distributor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambah">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kontak</label>
                            <input type="text" name="kontak" class="form-control">
                        </div>
                        <button type="button" onclick="tambahDistributor()" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
