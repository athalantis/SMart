<?php
include ("../.includes/header.php");
$title = "Users Management";
include '../.includes/toast_notification.php';


// Ambil data users
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2 class="mb-3">Daftar Pengguna</h2>
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
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['user_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include ("../.includes/footer.php"); ?>
