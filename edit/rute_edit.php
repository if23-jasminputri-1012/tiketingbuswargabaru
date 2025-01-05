<?php
session_start();
require '../config/db.php';

// Cek apakah user sudah login dan memiliki akses
if (!isset($_SESSION['username']) || !isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header("Location: ../login.php");
    exit;
}

// Ambil ID Rute dari URL
if (isset($_GET['idRute'])) {
    $idRute = $_GET['idRute'];

    // Ambil data rute berdasarkan ID
    try {
        $stmt = $pdo->prepare("SELECT * FROM Rute WHERE ID_Rute = :idRute");
        $stmt->bindParam(':idRute', $idRute);
        $stmt->execute();
        $rute = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching route data: " . $e->getMessage());
    }

    // Jika rute tidak ditemukan
    if (!$rute) {
        header("Location: jadwal_rute.php");
        exit;
    }
} else {
    header("Location: jadwal_rute.php");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header">
                <h3>Edit Rute</h3>
            </div>
            <div class="card-body">
                <form action="proses_edit_rute.php" method="post">
                    <input type="hidden" name="idRute" value="<?= $rute['ID_Rute']; ?>">
                    <div class="mb-3">
                        <label for="namaRute" class="form-label">Nama Rute:</label>
                        <input type="text" class="form-control" id="namaRute" name="namaRute" value="<?= htmlspecialchars($rute['Nama_Rute']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kotaAsal" class="form-label">Kota Asal:</label>
                        <input type="text" class="form-control" id="kotaAsal" name="kotaAsal" value="<?= htmlspecialchars($rute['Kota_Asal']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kotaTujuan" class="form-label">Kota Tujuan:</label>
                        <input type="text" class="form-control" id="kotaTujuan" name="kotaTujuan" value="<?= htmlspecialchars($rute['Kota_Tujuan']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jarak" class="form-label">Jarak (km):</label>
                        <input type="number" class="form-control" id="jarak" name="jarak" value="<?= htmlspecialchars($rute['Jarak']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga (Rp):</label>
                        <input type="number" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($rute['Harga']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>