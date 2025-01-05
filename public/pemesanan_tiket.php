<?php
session_start();
require '../config/db.php';

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Mendapatkan user_id dari session

// Mendapatkan daftar jadwal dan rute untuk ditampilkan pada form
$query = "SELECT * FROM Jadwal_Info 
        JOIN Bus ON Jadwal_Info.ID_Bus = Bus.ID_Bus
        JOIN Rute ON Jadwal_Info.ID_Rute = Rute.ID_Rute";
$stmt = $pdo->query($query);
$jadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses pemesanan tiket
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_jadwal = $_POST['id_jadwal'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $booking_type = $_POST['booking_type'];
    $seat_nomor = $_POST['seat_nomor'];

    // Hitung harga tiket
    $stmt = $pdo->prepare("SELECT Harga FROM Rute
                        JOIN Jadwal_Info ON Rute.ID_Rute = Jadwal_Info.ID_Rute
                        WHERE ID_Jadwal = :id_jadwal");
    $stmt->execute(['id_jadwal' => $id_jadwal]);
    $harga = $stmt->fetchColumn();

    // Data untuk Midtrans
    $order_id = 'ORDER-' . time();
    $transaction_details = [
        'order_id' => $order_id,
        'gross_amount' => $harga
    ];
    $customer_details = [
        'first_name' => $nama_lengkap,
        'email' => $email,
        'phone' => $nomor_telepon
    ];
    $item_details = [
        [
            'id' => $id_jadwal,
            'price' => $harga,
            'quantity' => 1,
            'name' => 'Tiket Bus'
        ]
    ];

    // Simpan pesanan ke database
    $stmt = $pdo->prepare("INSERT INTO Pesan_Tiket (ID_User, ID_Jadwal, Nama_Lengkap, Email, Nomor_Telepon, Booking_Type, Seat_Nomor, Status_Pesanan)
                        VALUES (:id_user, :id_jadwal, :nama_lengkap, :email, :nomor_telepon, :booking_type, :seat_nomor, 'Pending')");
    $stmt->execute([
        'id_user' => $user_id,  // Gunakan user_id dari sesi
        'id_jadwal' => $id_jadwal,
        'nama_lengkap' => $nama_lengkap,
        'email' => $email,
        'nomor_telepon' => $nomor_telepon,
        'booking_type' => $booking_type,
        'seat_nomor' => $seat_nomor
    ]);

    // Konfigurasi Midtrans
    require '../vendor/autoload.php'; // Pastikan path sesuai dengan lokasi vendor/autoload.php
    \Midtrans\Config::$serverKey = 'YOUR_SERVER_KEY';
    \Midtrans\Config::$isProduction = false; // Ubah ke true jika sudah di production
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // Buat transaksi Snap
    $snap_token = \Midtrans\Snap::getSnapToken([
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
        'item_details' => $item_details
    ]);

    // Kirim token ke halaman frontend
    $_SESSION['snap_token'] = $snap_token;
    $_SESSION['order_id'] = $order_id;
    header('Location: pembayaran.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/warga_baru_expres.jpg">
    <title>Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/jadwal_rute.css">
    <!--font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit&family=Poppins:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            padding-top: 20px; /* Tinggi navbar */
        }

    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../beranda.php">
                <img src="../image/warga_baru_expres-removebg-preview.png" alt="Buss Lightyear" width="42" height="42" class="me-2" />
                <span class="text-custom fs-4 fw-bolder">Warga Baru Express</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link mx-2" href="../beranda.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link mx-2" href="jadwal_rute.php">Jadwal & Rute</a></li>
                    <li class="nav-item"><a class="nav-link mx-2" href="tiket.php">Tiket</a></li>
                    <li class="nav-item"><a class="nav-link mx-2 active" href="tiket_saya.php">Tiket Saya</a></li>
                    <li class="nav-item dropdown">
                        <?php if ($isLoggedIn): ?>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($username); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="../profile.php">Profil</a></li>
                                <li><a class="dropdown-item text-danger" href="../logout.php">Log Out</a></li>
                            </ul>
                        <?php else: ?>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> Profil
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="../login.php">Login</a></li>
                            </ul>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Tiket -->
    <?php
        // Inisialisasi variabel $successMessage dengan string kosong jika belum ada
        $successMessage = isset($successMessage) ? $successMessage : '';
    ?>

    <div class="container pt-4">
        <?php if ($successMessage): ?>
            <div class="alert alert-success text-center mt-5 p-5 shadow rounded">
                <h4 class="fw-bold"><?= $successMessage ?></h4>
                <p class="mt-3">Pesanan Anda sedang diproses. Kami akan mengonfirmasi pembayaran Anda segera.</p>
                <a href="index.php" class="btn btn-primary btn-lg mt-4 px-5">Kembali ke Beranda</a>
            </div>
        <?php else: ?>
            <div class="card shadow-lg mt-5">
                <div class="card-header bg-primary text-white text-center">
                    <h1 class="fw-bold">Pemesanan Tiket</h1>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="" class="row g-3">
                        <!-- Nama Lengkap -->
                        <div class="col-md-6">
                            <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control border-primary shadow-sm" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap Anda" required>
                        </div>
                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control border-primary shadow-sm" id="email" name="email" placeholder="contoh@email.com" required>
                        </div>
                        <!-- Nomor Telepon -->
                        <div class="col-md-6">
                            <label for="nomor_telepon" class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" class="form-control border-primary shadow-sm" id="nomor_telepon" name="nomor_telepon" placeholder="Masukkan nomor telepon Anda" required>
                        </div>
                        <!-- Pilihan Jadwal -->
                        <div class="col-md-6">
                            <label for="id_jadwal" class="form-label fw-semibold">Jadwal</label>
                            <select class="form-select border-primary shadow-sm" id="id_jadwal" name="id_jadwal" required>
                                <option value="" selected>Pilih Jadwal</option>
                                <?php foreach ($jadwal as $row): ?>
                                    <option value="<?= $row['ID_Jadwal'] ?>"><?= $row['Nama_Bus'] ?> - <?= $row['Nama_Rute'] ?> (<?= $row['Kota_Asal'] ?> → <?= $row['Kota_Tujuan'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Jenis Pemesanan -->
                        <div class="col-md-6">
                            <label for="booking_type" class="form-label fw-semibold">Jenis Pemesanan</label>
                            <select class="form-select border-primary shadow-sm" id="booking_type" name="booking_type" required>
                                <option value="Full Bus">Full Bus</option>
                                <option value="Seat Event">Seat Event</option>
                            </select>
                        </div>
                        <!-- Nomor Kursi -->
                        <div class="col-md-6">
                            <label for="seat_nomor" class="form-label fw-semibold">Nomor Kursi</label>
                            <input type="text" class="form-control border-primary shadow-sm" id="seat_nomor" name="seat_nomor" placeholder="Masukkan nomor kursi" required>
                        </div>
                        <!-- Metode Pembayaran -->
                        <div class="col-md-6">
                            <label for="metode_pembayaran" class="form-label fw-semibold">Metode Pembayaran</label>
                            <select class="form-select border-primary shadow-sm" id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="Cash">Cash</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <!-- Tombol Submit -->
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">Pesan Tiket</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <!-- footer -->
    <footer class="footer bg-dark text-white mt-5 py-4">
        <div class="container mt-2 pt-2">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold">Tentang Warga Baru Express</h5>
                    <p>Warga Baru Express adalah layanan transportasi terpercaya yang menyediakan perjalanan nyaman dan aman ke berbagai kota tujuan.</p>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold">Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="../beranda.php" class="text-white text-decoration-none">Beranda</a></li>
                        <li><a href="jadwal_rute.php" class="text-white text-decoration-none">Jadwal & Rute</a></li>
                        <li><a href="tiket.php" class="text-white text-decoration-none">Pesan Tiket</a></li>
                        <li><a href="tiket_saya.php" class="text-white text-decoration-none">Tiket Saya</a></li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h5 class="fw-bold">Hubungi Kami</h5>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Suroto Kunto No. 207 RT. 001 RW 006 Kel. Adiarsa Timur Kec. Karawang Timur Kab. Karawang</p>
                    <p><i class="fas fa-phone-alt"></i> (0267) 403000</p>
                    <p><i class="fas fa-envelope"></i> info@wargabaruexpress.com</p>
                </div>
            </div>

            <!-- Social Media Icons -->
            <div class="text-center mt-4">
                <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
            </div>

            <!-- Footer Copyright -->
            <div class="text-center mt-4">
                <p style="margin: 0; text-align: center;">&copy; 2024 Warga Baru Express. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>