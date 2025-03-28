<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? 'insert';

    if ($action === "insert") {
        $nama = $_POST['nama'] ?? '';
        $kontak = $_POST['kontak'] ?? '';

        if (!empty($nama)) {
            $stmt = $conn->prepare("INSERT INTO distributor (nama, kontak) VALUES (?, ?)");
            $stmt->bind_param("ss", $nama, $kontak);
            echo $stmt->execute() ? "success" : "error: " . $stmt->error;
        } else echo "error: Nama kosong!";
    }

    if ($action === "update") {
        $id = $_POST['id'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $kontak = $_POST['kontak'] ?? '';

        if (!empty($id) && !empty($nama)) {
            $stmt = $conn->prepare("UPDATE distributor SET nama=?, kontak=? WHERE distributor_id=?");
            $stmt->bind_param("ssi", $nama, $kontak, $id);
            echo $stmt->execute() ? "success" : "error: " . $stmt->error;
        } else echo "error: Data tidak valid!";
    }

    if ($action === "delete") {
        $id = $_POST['id'] ?? '';

        if (!empty($id)) {
            $stmt = $conn->prepare("DELETE FROM distributor WHERE distributor_id=?");
            $stmt->bind_param("i", $id);
            echo $stmt->execute() ? "success" : "error: " . $stmt->error;
        } else echo "error: ID tidak valid!";
    }
}
?>
