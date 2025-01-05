<?php
session_start();
require '../config/db.php';

// Mengecek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Proses menambah data jadwal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_jadwal'])) {
    $idBus = $_POST['ID_Bus'];
    $idRute = $_POST['ID_Rute'];
    $jamTakeOff = $_POST['Jam_Take_Off'];
    $jamPulang = $_POST['Jam_Pulang'];
    $estimasi = $_POST['Estimasi'];
    $operation = $_POST['Operation'];

    try {
        // Query untuk menambahkan data jadwal
        $stmt = $pdo->prepare("INSERT INTO Jadwal_Info (ID_Bus, ID_Rute, Jam_Take_Off, Jam_Pulang, Estimasi, Operation) 
                            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idBus, $idRute, $jamTakeOff, $jamPulang, $estimasi, $operation]);

        // Menampilkan SweetAlert2 setelah berhasil
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Jadwal berhasil ditambahkan!',
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

<!-- Form untuk menambahkan jadwal bus -->
<div class="container py-5">
    <h2 class="text-center mb-4 animated fadeInUp">Tambah Jadwal Bus</h2>
    <form action="" method="POST" class="p-4 border rounded shadow-lg animated fadeInUp">
        <div class="form-group">
            <label for="ID_Bus">Nama Bus:</label>
            <select name="ID_Bus" class="form-control" required>
                <option value="">Pilih Bus</option>
                <?php
                // Ambil data bus dari database
                $stmt = $pdo->prepare("SELECT * FROM Bus");
                $stmt->execute();
                $buses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($buses as $bus) {
                    echo "<option value='{$bus['ID_Bus']}'>" . htmlspecialchars($bus['Nama_Bus']) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="ID_Rute">Nama Rute:</label>
            <select name="ID_Rute" class="form-control" required>
                <option value="">Pilih Rute</option>
                <?php
                // Ambil data rute dari database
                $stmt = $pdo->prepare("SELECT * FROM Rute");
                $stmt->execute();
                $rutes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rutes as $rute) {
                    echo "<option value='{$rute['ID_Rute']}'>" . htmlspecialchars($rute['Nama_Rute']) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="Jam_Take_Off">Jam Take Off:</label>
            <input type="time" name="Jam_Take_Off" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Jam_Pulang">Jam Pulang:</label>
            <input type="time" name="Jam_Pulang" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Estimasi">Estimasi Waktu (menit):</label>
            <input type="text" name="Estimasi" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Operation">Operation:</label>
            <input type="text" name="Operation" class="form-control" required>
        </div>
        <button type="submit" name="add_jadwal" class="btn btn-primary mt-3 animated bounceIn">Tambah Jadwal</button>
    </form>
</div>

<?php include '../admin/includes/footer.php'; ?>
