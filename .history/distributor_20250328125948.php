<?php
include "config.php";
include ".includes/header.php";

$title = "Admin's Dashboard";
include ".includes/toast_notification.php";

// Handle request tambah distributor
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST["nama"];
    $kontak = $_POST["kontak"];

    $query = "INSERT INTO distributor (nama, kontak) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nama, $kontak);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Distributor berhasil ditambahkan!";
        header("Location: distributor.php");
        exit;
    } else {
        $_SESSION['message'] = "Gagal menambahkan distributor!";
    }
}

// Ambil data distributor
$result = $conn->query("SELECT * FROM distributor");
$distributors = [];
while ($row = $result->fetch_assoc()) {
    $distributors[] = $row;
}
?>

<h2 class="text-xl font-bold mt-4">Daftar Distributor</h2>

<button onclick="openModal()" class="px-4 py-2 mt-3 bg-blue-500 text-white rounded">Tambah Distributor</button>

<table class="w-full mt-4 bg-white shadow-md">
    <thead>
        <tr class="bg-gray-200">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Nama</th>
            <th class="px-4 py-2">Kontak</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($distributors as $d): ?>
            <tr class="border-t">
                <td class="px-4 py-2"><?= $d['distributor_id'] ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($d['nama']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($d['kontak']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-bold">Tambah Distributor</h3>
        <form id="formTambah">
            <input type="text" name="nama" placeholder="Nama Distributor" class="block w-full p-2 border rounded mt-2">
            <input type="text" name="kontak" placeholder="Kontak" class="block w-full p-2 border rounded mt-2">
            <button type="button" onclick="tambahDistributor()" class="bg-blue-500 text-white px-4 py-2 mt-3 rounded">Simpan</button>
        </form>
        <button onclick="closeModal()" class="text-red-500 mt-2">Tutup</button>
    </div>
</div>

<script>
function openModal() {
    document.getElementById("modal").classList.remove("hidden");
}

function closeModal() {
    document.getElementById("modal").classList.add("hidden");
}

function tambahDistributor() {
    let formData = new FormData(document.getElementById("formTambah"));

    fetch("distributor.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            alert("Distributor berhasil ditambahkan!");
            location.reload();
        } else {
            alert("Gagal menambahkan distributor!");
        }
    });
}
</script>

</body>
</html>
