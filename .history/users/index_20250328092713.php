<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "your_database");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hapus user jika ada request
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE user_id = $user_id");
    header("Location: index.php");
}

// Ambil data pengguna
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Users Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-3">Users List</h2>
    <a href="create.php" class="btn btn-success mb-3">Tambah User</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th><th>Username</th><th>Nama</th><th>Role</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= ucfirst($row['role']) ?></td>
                    <td>
                        <a href="edit.php?user_id=<?= $row['user_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="index.php?delete=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php $conn->close(); ?>
