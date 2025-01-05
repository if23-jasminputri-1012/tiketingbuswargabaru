<?php
session_start();
require '../config/db.php';  // Koneksi ke database

// Cek apakah ID Pemesanan ada di URL
if (!isset($_GET['id'])) {
    die("ID Pemesanan tidak ditemukan.");
}

$id_pesan = $_GET['id'];

// Ambil data pemesanan berdasarkan ID
$sql = "SELECT pt.*, u.Nama_User FROM Pesan_Tiket pt 
        JOIN Users u ON pt.ID_User = u.ID_User 
        WHERE pt.ID_Pesan = :id_pesan";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_pesan' => $id_pesan]);
$pemesanan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pemesanan) {
    die("Pemesanan tidak ditemukan.");
}

// Proses edit pemesanan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = $_POST['Nama_Lengkap'];
    $email = $_POST['Email'];
    $nomor_telepon = $_POST['Nomor_Telepon'];
    $booking_type = $_POST['Booking_Type'];
    $seat_nomor = $_POST['Seat_Nomor'];
    $metode_pembayaran = $_POST['Metode_Pembayaran'];
    $status_pesanan = $_POST['Status_Pesanan'];

    // Update data pemesanan
    $update_sql = "UPDATE Pesan_Tiket SET 
                   Nama_Lengkap = :nama_lengkap, 
                   Email = :email, 
                   Nomor_Telepon = :nomor_telepon, 
                   Booking_Type = :booking_type, 
                   Seat_Nomor = :seat_nomor, 
                   Metode_Pembayaran = :metode_pembayaran, 
                   Status_Pesanan = :status_pesanan
                   WHERE ID_Pesan = :id_pesan";

    $stmt = $pdo->prepare($update_sql);
    $stmt->execute([
        'nama_lengkap' => $nama_lengkap,
        'email' => $email,
        'nomor_telepon' => $nomor_telepon,
        'booking_type' => $booking_type,
        'seat_nomor' => $seat_nomor,
        'metode_pembayaran' => $metode_pembayaran,
        'status_pesanan' => $status_pesanan,
        'id_pesan' => $id_pesan
    ]);

    // Redirect setelah update
    header('Location: ../admin/data_pemesanan.php');
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid p-4">
    <h2>Edit Pemesanan</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="Nama_Lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="Nama_Lengkap" name="Nama_Lengkap" value="<?= htmlspecialchars($pemesanan['Nama_Lengkap']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="email" class="form-control" id="Email" name="Email" value="<?= htmlspecialchars($pemesanan['Email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="Nomor_Telepon" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="Nomor_Telepon" name="Nomor_Telepon" value="<?= htmlspecialchars($pemesanan['Nomor_Telepon']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="Booking_Type" class="form-label">Jenis Pemesanan</label>
            <select class="form-select" id="Booking_Type" name="Booking_Type" required>
                <option value="Full Bus" <?= $pemesanan['Booking_Type'] == 'Full Bus' ? 'selected' : '' ?>>Full Bus</option>
                <option value="Seat Event" <?= $pemesanan['Booking_Type'] == 'Seat Event' ? 'selected' : '' ?>>Seat Event</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="Seat_Nomor" class="form-label">Nomor Kursi</label>
            <input type="text" class="form-control" id="Seat_Nomor" name="Seat_Nomor" value="<?= htmlspecialchars($pemesanan['Seat_Nomor']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="Metode_Pembayaran" class="form-label">Metode Pembayaran</label>
            <select class="form-select" id="Metode_Pembayaran" name="Metode_Pembayaran" required>
                <option value="Cash" <?= $pemesanan['Metode_Pembayaran'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
                <option value="E-Wallet" <?= $pemesanan['Metode_Pembayaran'] == 'E-Wallet' ? 'selected' : '' ?>>E-Wallet</option>
                <option value="Bank Transfer" <?= $pemesanan['Metode_Pembayaran'] == 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="Status_Pesanan" class="form-label">Status Pesanan</label>
            <select class="form-select" id="Status_Pesanan" name="Status_Pesanan" required>
                <option value="Pending" <?= $pemesanan['Status_Pesanan'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Confirmed" <?= $pemesanan['Status_Pesanan'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="Cancelled" <?= $pemesanan['Status_Pesanan'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
