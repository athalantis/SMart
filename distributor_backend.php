<?php
include('config.php');

// Handling different CRUD actions
if (!isset($_GET['action'])) {
    die(json_encode(['success' => false, 'message' => 'Aksi tidak valid']));
}

$action = $_GET['action'];

// Tambah Distributor
if ($action == 'tambah') {
    $nama = mysqli_real_escape_string($connection, $_POST['nama']);
    $kontak = mysqli_real_escape_string($connection, $_POST['kontak'] ?? '');

    $query = "INSERT INTO distributor (nama, kontak) VALUES ('$nama', '$kontak')";
    
    if (mysqli_query($connection, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Gagal menambah distributor: ' . mysqli_error($connection)
        ]);
    }
}

// Edit Distributor
elseif ($action == 'edit') {
    $id = mysqli_real_escape_string($connection, $_POST['distributor_id']);
    $nama = mysqli_real_escape_string($connection, $_POST['nama']);
    $kontak = mysqli_real_escape_string($connection, $_POST['kontak'] ?? '');

    $query = "UPDATE distributor SET nama='$nama', kontak='$kontak' WHERE distributor_id='$id'";
    
    if (mysqli_query($connection, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Gagal mengubah distributor: ' . mysqli_error($connection)
        ]);
    }
}

// Hapus Distributor
elseif ($action == 'hapus') {
    $id = mysqli_real_escape_string($connection, $_GET['id']);

    $query = "DELETE FROM distributor WHERE distributor_id='$id'";
    
    if (mysqli_query($connection, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Gagal menghapus distributor: ' . mysqli_error($connection)
        ]);
    }
}

// Close connection
mysqli_close($connection);
?>