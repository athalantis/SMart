<?php
include("config.php"); // Koneksi ke database
include(".includes/header.php");

$title = "Admin's Dashboard";
include('.includes/toast_notification.php');

// Ambil data distributor dari database
$result = $conn->query("SELECT * FROM distributor");
$distributors = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card p-4">
                <h2 class="mb-3 text-center">Daftar Distributor</h2>
                <div class="d-flex justify-content-end mb-3">
                    <button onclick="openTambahModal()" class="btn btn-soft-primary">➕ Tambah Distributor</button>
                </div>

                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($distributors as $d): ?>
                            <tr>
                                <td class="text-center"><?= $d['distributor_id'] ?></td>
                                <td><?= htmlspecialchars($d['nama']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($d['kontak']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $d['distributor_id'] ?>, '<?= htmlspecialchars($d['nama']) ?>', '<?= htmlspecialchars($d['kontak']) ?>')">✏️ Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="hapusDistributor(<?= $d['distributor_id'] ?>)">🗑️ Hapus</button>
                                </td>
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

<!-- Modal Edit Distributor -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Distributor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEdit">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Nama Distributor</label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak</label>
                        <input type="text" name="kontak" id="editKontak" class="form-control">
                    </div>
                    <div class="text-end">
                        <button type="button" onclick="updateDistributor()" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openTambahModal() {
        let modal = new bootstrap.Modal(document.getElementById("modalTambah"));
        modal.show();
    }

    function openEditModal(id, nama, kontak) {
        document.getElementById("editId").value = id;
        document.getElementById("editNama").value = nama;
        document.getElementById("editKontak").value = kontak;

        let modal = new bootstrap.Modal(document.getElementById("modalEdit"));
        modal.show();
    }

    function tambahDistributor() {
        let form = new FormData(document.getElementById("formTambah"));

        fetch("backend_distributor.php", {
            method: "POST",
            body: form
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") location.reload();
            else alert("Error: " + data);
        });
    }

    function updateDistributor() {
        let form = new FormData(document.getElementById("formEdit"));
        form.append("action", "update");

        fetch("backend_distributor.php", {
            method: "POST",
            body: form
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") location.reload();
            else alert("Error: " + data);
        });
    }

    function hapusDistributor(id) {
        if (!confirm("Yakin ingin menghapus?")) return;

        fetch("backend_distributor.php", {
            method: "POST",
            body: new URLSearchParams({ action: "delete", id })
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") location.reload();
            else alert("Error: " + data);
        });
    }
</script>

<?php include(".includes/footer.php"); ?>
