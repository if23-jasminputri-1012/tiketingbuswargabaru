<?php
session_start();
require '../config/db.php'; // Pastikan path benar

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: login.php');
    exit;
}

// Ambil ID pembatalan dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $alasan = $_POST['alasan'];

    try {
        $query = "UPDATE Pembatalan SET Tanggal_Pembatalan = ?, Alasan_Pembatalan = ? WHERE ID_Pembatalan = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$tanggal, $alasan, $id]);
        $_SESSION['message'] = 'Data pembatalan berhasil diupdate.';
        header('Location: ../admin/pembatalan_tiket.php');
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Ambil data pembatalan untuk form
try {
    $query = "SELECT * FROM Pembatalan WHERE ID_Pembatalan = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Jika data tidak ditemukan
if (!$data) {
    die("Data pembatalan tidak ditemukan.");
}
?>

<?php include 'includes/header.php'; ?>
<div class="container mt-5">
    <h2>Edit Pembatalan Tiket</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Pembatalan</label>
            <input type="datetime-local" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($data['Tanggal_Pembatalan'])); ?>" required>
        </div>
        <div class="mb-3">
            <label for="alasan" class="form-label">Alasan Pembatalan</label>
            <textarea id="alasan" name="alasan" class="form-control" required><?= htmlspecialchars($data['Alasan_Pembatalan']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="dashboard_pembatalan.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
