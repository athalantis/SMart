<?php
include("config.php"); // Pastikan ini koneksi database yang benar

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST['nama'] ?? '';
    $kontak = $_POST['kontak'] ?? '';

    if (!empty($nama)) {
        $stmt = $conn->prepare("INSERT INTO distributor (nama, kontak) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $kontak);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo "error: Nama tidak boleh kosong!";
        exit;
    }
}

// Jika diakses langsung, tampilkan error
http_response_code(400);
echo "Invalid Request!";
exit;
?>
