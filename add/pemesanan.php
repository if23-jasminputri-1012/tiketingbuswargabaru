<?php
// Memastikan session_start() dipanggil sebelum output apa pun
session_start();
require '../config/db.php';

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: login.php');
    exit;
}

// Inisialisasi variabel untuk pesan error dan sukses
$error = "";
$success = "";

// Proses tambah data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Nama_User = $_POST['Nama_User'];
    $Jadwal = $_POST['Jadwal'];
    $Nama_Lengkap = $_POST['Nama_Lengkap'];
    $Email = $_POST['Email'];
    $Nomor_Telepon = $_POST['Nomor_Telepon'];
    $Booking_Type = $_POST['Booking_Type'];
    $Seat_Nomor = $_POST['Seat_Nomor'];
    $Metode_Pembayaran = $_POST['Metode_Pembayaran'];
    $Status_Pesanan = $_POST['Status_Pesanan'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Pesan_Tiket (Nama_User, Jadwal, Nama_Lengkap, Email, Nomor_Telepon, Booking_Type, Seat_Nomor, Metode_Pembayaran, Status_Pesanan) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$Nama_User, $Jadwal, $Nama_Lengkap, $Email, $Nomor_Telepon, $Booking_Type, $Seat_Nomor, $Metode_Pembayaran, $Status_Pesanan]);
        $success = "Data pemesanan berhasil ditambahkan.";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Tambah Data Pemesanan</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="Nama_User" class="form-label">Nama User</label>
            <input type="text" class="form-control" id="Nama_User" name="Nama_User" required>
        </div>
        <div class="mb-3">
            <label for="Jadwal" class="form-label">Jadwal</label>
            <input type="datetime-local" class="form-control" id="Jadwal" name="Jadwal" required>
        </div>
        <div class="mb-3">
            <label for="Nama_Lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="Nama_Lengkap" name="Nama_Lengkap" required>
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="email" class="form-control" id="Email" name="Email" required>
        </div>
        <div class="mb-3">
            <label for="Nomor_Telepon" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="Nomor_Telepon" name="Nomor_Telepon" required>
        </div>
        <div class="mb-3">
            <label for="Booking_Type" class="form-label">Booking Type</label>
            <input type="text" class="form-control" id="Booking_Type" name="Booking_Type" required>
        </div>
        <div class="mb-3">
            <label for="Seat_Nomor" class="form-label">Seat Nomor</label>
            <input type="text" class="form-control" id="Seat_Nomor" name="Seat_Nomor" required>
        </div>
        <div class="mb-3">
            <label for="Metode_Pembayaran" class="form-label">Metode Pembayaran</label>
            <input type="text" class="form-control" id="Metode_Pembayaran" name="Metode_Pembayaran" required>
        </div>
        <div class="mb-3">
            <label for="Status_Pesanan" class="form-label">Status Pesanan</label>
            <select class="form-select" id="Status_Pesanan" name="Status_Pesanan" required>
                <option value="Pending">Pending</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah Data</button>
        <a href="../admin/dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
