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
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
    <h2 class="text-center mb-3">Manajemen Pengguna</h2>
    <a href="user_create.php" class="btn btn-primary mb-3">Tambah Pengguna</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $users->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= ucfirst($row['role']) ?></td>
                    <td>
                        <a href="user_edit.php?id=<?= $row['user_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        
                        <!-- Form untuk hapus user -->
                        <form method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= $row['user_id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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
