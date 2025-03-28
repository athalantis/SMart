<?php
include(".includes/header.php");
include("config.php");

$title = "Distributor Management";
include('.includes/toast_notification.php');

// Fetch all distributors
$query = "SELECT * FROM distributor ORDER BY distributor_id DESC";
$result = mysqli_query($connection, $query);
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Distributor</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDistributorModal">
                <i class="bx bx-plus me-1"></i> Tambah Distributor
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Distributor</th>
                        <th>Kontak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['distributor_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['kontak'] ?? '-'); ?></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);" 
                                       onclick="editDistributor(
                                           <?php echo $row['distributor_id']; ?>, 
                                           '<?php echo htmlspecialchars($row['nama']); ?>', 
                                           '<?php echo htmlspecialchars($row['kontak'] ?? ''); ?>'
                                       )">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" 
                                       onclick="hapusDistributor(<?php echo $row['distributor_id']; ?>)">
                                        <i class="bx bx-trash me-1"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tambah Distributor Modal -->
<div class="modal fade" id="tambahDistributorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Distributor Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="tambahDistributorForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nama" class="form-label">Nama Distributor</label>
                            <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama distributor">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="kontak" class="form-label">Nomor Kontak</label>
                            <input type="text" class="form-control" id="kontak" name="kontak" placeholder="Masukkan nomor kontak">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Distributor Modal -->
<div class="modal fade" id="editDistributorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Distributor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDistributorForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_distributor_id" name="distributor_id">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="edit_nama" class="form-label">Nama Distributor</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required placeholder="Masukkan nama distributor">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="edit_kontak" class="form-label">Nomor Kontak</label>
                            <input type="text" class="form-control" id="edit_kontak" name="kontak" placeholder="Masukkan nomor kontak">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include(".includes/footer.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tambah Distributor
    document.getElementById('tambahDistributorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        fetch('distributor_crud.php?action=tambah', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#tambahDistributorModal').modal('hide');
                location.reload();
            } else {
                alert('Gagal menambah distributor: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });

    // Edit Distributor - Fungsi untuk mengisi modal edit
    window.editDistributor = function(id, nama, kontak) {
        document.getElementById('edit_distributor_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_kontak').value = kontak;
        new bootstrap.Modal(document.getElementById('editDistributorModal')).show();
    };

    // Submit Edit Distributor
    document.getElementById('editDistributorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        fetch('distributor_crud.php?action=edit', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#editDistributorModal').modal('hide');
                location.reload();
            } else {
                alert('Gagal mengubah distributor: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });

    // Hapus Distributor
    window.hapusDistributor = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus distributor ini?')) {
            fetch('distributor_crud.php?action=hapus&id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus distributor: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }
    };
});
</script>