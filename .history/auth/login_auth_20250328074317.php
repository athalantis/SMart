<?php 
session_start();
require_once("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username dan password harus diisi!'
        ];
        header("Location: login.php");
        exit();
    }

    // Gunakan Prepared Statement untuk mencegah SQL Injection
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {
            session_regenerate_id(true); // Hindari session fixation

            $_SESSION["username"] = $username;
            $_SESSION["name"] = $row["name"];
            $_SESSION["role"] = $row["role"];
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION['notification'] = [
                'type' => 'primary',
                'message' => 'Selamat datang kembali!'
            ];

            // Redirect berdasarkan role
            if ($row["role"] === "admin") {
                header("Location: ../dashmind.php");
            } else {
                header("Location: ../dashboard.php");
            }
            exit();
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Username atau password salah'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username atau password salah'
        ];
    }

    // Redirect kembali ke halaman login jika gagal
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();
?>
