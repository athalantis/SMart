<?php
include ".includes/header.php";
$title = "Users Management";
include ".includes/toast_notification.php";
include "config.php";

// Proses Hapus User
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $user_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    return redirect("users.php");
}

// Ambil data user
$users = $conn->query("SELECT * FROM users");
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manajemen Pengguna</h4>
            <a href="user_create.php" class="btn btn-primary">➕ Tambah Pengguna</a>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?= $row['user_id'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td class="text-center">
                                <span class="badge bg-label-info"><?= ucfirst($row['role']) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="user_edit.php?id=<?= $row['user_id'] ?>">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <form method="POST" class="dropdown-item p-0">
                                            <input type="hidden" name="delete_id" value="<?= $row['user_id'] ?>">
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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
