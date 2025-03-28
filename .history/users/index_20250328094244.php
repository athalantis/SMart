<?php
include '../config.php';
include '../.includes/init_session.php';
include '../.includes/header.php';
include '../.includes/navbar.php';
include '../.includes/sidemenu.php';
include '../.includes/toast_notification.php';

$title = "Admin's Dashboard";

$result = $conn->query("SELECT * FROM users");
?>

<div class="container mt-5">
    <h2>Daftar Users</h2>
    <a href="create.php" class="btn btn-primary mb-3">Tambah User</a>
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
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['role'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['user_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../.includes/footer.php'; ?>
