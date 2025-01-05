<?php
session_start();
require '../config/db.php';

// Mengecek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Proses menambah data rute
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_rute'])) {
    $namaRute = $_POST['Nama_Rute'];
    $kotaAsal = $_POST['Kota_Asal'];
    $kotaTujuan = $_POST['Kota_Tujuan'];
    $jarak = $_POST['Jarak'];
    $harga = $_POST['Harga'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Rute (Nama_Rute, Kota_Asal, Kota_Tujuan, Jarak, Harga) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$namaRute, $kotaAsal, $kotaTujuan, $jarak, $harga]);

        // Menampilkan SweetAlert2
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Rute berhasil ditambahkan!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = '../admin/jadwal_rute.php';
                });
            </script>";
    } catch (PDOException $e) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan!',
                    text: '" . $e->getMessage() . "'
                });
            </script>";
    }
}
?>

<?php include '../admin/includes/header.php'; ?>

<!-- Form untuk menambahkan rute -->
<div class="container py-5">
    <h2 class="text-center mb-4 animated fadeInUp">Tambah Rute Bus</h2>
    <form action="" method="POST" class="p-4 border rounded shadow-lg animated fadeInUp">
        <div class="form-group">
            <label for="Nama_Rute">Nama Rute:</label>
            <input type="text" name="Nama_Rute" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Kota_Asal">Kota Asal:</label>
            <input type="text" name="Kota_Asal" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Kota_Tujuan">Kota Tujuan:</label>
            <input type="text" name="Kota_Tujuan" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Jarak">Jarak (km):</label>
            <input type="number" name="Jarak" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Harga">Harga (Rp):</label>
            <input type="number" name="Harga" class="form-control" required>
        </div>
        <button type="submit" name="add_rute" class="btn btn-primary mt-3 animated bounceIn">Tambah Rute</button>
    </form>
</div>

<?php include '../admin/includes/footer.php'; ?>
