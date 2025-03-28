<?php
include ".includes/header.php";
$title = "Create User";
include ".includes/toast_notification.php";
include "config.php"; // Koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, name, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $name, $password, $role);

    if ($stmt->execute()) {
        return redirect("users.php");
    } else {
        echo "<div class='alert alert-danger'>Gagal menambahkan pengguna.</div>";
    }
    $stmt->close();
}
?>

<div class="container mt-4">
    <h2>Tambah Pengguna</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="users.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include ".includes/footer.php"; ?>

<?php
function redirect($url) {
    echo "<script>window.location.href='$url';</script>";
    exit;
}
?>
