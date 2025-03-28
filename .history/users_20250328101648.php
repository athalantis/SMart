<?php
include (".includes/header.php");
$title = "Users Management";
include '.includes/toast_notification.php';
include "config.php"; // Koneksi ke database

// Ambil data pengguna dari database
$users = $conn->query("SELECT * FROM users");
?>

<div class="container mt-4">
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
                        <a href="backend/user_delete.php?id=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include (".includes/footer.php"); ?>
