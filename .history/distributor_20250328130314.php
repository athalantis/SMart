<?php
include ("config.php"); // Koneksi database
include (".includes/header.php");

$title = "Admin's Dashboard";

// Notifikasi (jika ada)
include '.includes/toast_notification.php';

// Ambil data distributor
$query = $conn->query("SELECT * FROM distributor");
$distributors = $query->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card p-4">
                <h2 class="mb-3 text-center">Daftar Distributor</h2>
                <div class="d-flex justify-content-end mb-3">
                    <button onclick="openModal()" class="btn btn-soft-primary">➕ Tambah Distributor</button>
                </div>

                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($distributors as $d): ?>
                            <tr>
                                <td class="text-center"><?= $d['distributor_id'] ?></td>
                                <td><?= htmlspecialchars($d['nama']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($d['kontak']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Distributor -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Distributor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTambah">
                    <div class="mb-3">
                        <label class="form-label">Nama Distributor</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak</label>
                        <input type="text" name="kontak" class="form-control">
                    </div>
                    <div class="text-end">
                        <button type="button" onclick="tambahDistributor()" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                alert("✅ Distributor berhasil ditambahkan!");
                location.reload();
            } else {
                alert("❌ Gagal menambahkan distributor!");
            }
        });
    }
</script>

<?php include (".includes/footer.php"); ?>
