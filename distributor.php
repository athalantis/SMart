<?php
include("config.php"); // Koneksi ke database
include(".includes/header.php");

$title = "Admin's Dashboard";
include('.includes/toast_notification.php');

$result = $conn->query("SELECT * FROM distributor");
$distributors = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body"> 
                <h2 class="mb-3 text-center">Daftar Distributor</h2>
                <div class="d-flex justify-content-end mb-3">
                    <button onclick="openTambahModal()" class="btn btn-soft-primary">➕ Tambah Distributor</button>
                </div>

                <table id="datatable" class="table table-hover">
                    <thead class="table text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($distributors as $i => $d): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($d['nama']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($d['kontak']) ?></td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="#" class="dropdown-item"
                                            onclick="openEditModal(<?= $d['distributor_id'] ?>, '<?= htmlspecialchars($d['nama']) ?>', '<?= htmlspecialchars($d['kontak']) ?>')">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a href="#" class="dropdown-item" onclick="hapusDistributor(<?= $d['distributor_id'] ?>)">
                                            <i class="bx bx-trash me-1"></i> Hapus
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" onclick="tambahDistributor()" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" onclick="updateDistributor()" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>


<script>
    function openTambahModal() {
        new bootstrap.Modal(document.getElementById("modalTambah")).show();
    }

    function openEditModal(id, nama, kontak) {
        document.getElementById("editId").value = id;
        document.getElementById("editNama").value = nama;
        document.getElementById("editKontak").value = kontak;
        new bootstrap.Modal(document.getElementById("modalEdit")).show();
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
