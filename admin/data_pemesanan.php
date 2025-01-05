<?php
// Memastikan session_start() dipanggil sebelum output apa pun
session_start();
require '../config/db.php';
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <!-- Header -->
        <?php 
        // Cek apakah user sudah login
        $isLoggedIn = isset($_SESSION['username']);
        $username = $isLoggedIn ? $_SESSION['username'] : null;
        $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];

        // Ambil data pemesanan tiket dari tabel Pemesanan_Tiket
        $orders = [];
        try {
            $stmt = $pdo->prepare("SELECT * FROM Pesan_Tiket"); // Sesuaikan nama tabel dengan yang ada di database
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
        ?>
        
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand fw-bold">Admin Warga Baru Express</span>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="notifikasi.php" class="nav-link"><i class="fas fa-bell"></i> Notifikasi</a>
                    </li>
                    <li class="nav-item dropdown">
                        <?php if ($isLoggedIn): ?>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?= $isAdmin ? 'Admin' : htmlspecialchars($username); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item text-danger" href="../logout.php">Log Out</a></li>
                            </ul>
                        <?php else: ?>
                            <a class="nav-link" href="../login.php"><i class="fas fa-user-circle"></i> Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Konten Dasboard -->
        <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
        <div class="table-responsive">
            <h1 class="mb-4">Data Pemesanan Tiket</h1>
            <a href="../add/pemesanan.php" class="btn btn-primary mb-3">Tambah Data</a>
            <div class="table-responsive">
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>ID Pesan</th>
                            <th>Nama User</th>
                            <th>Jadwal</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Nomor Telepon</th>
                            <th>Booking Type</th>
                            <th>Seat Nomor</th>
                            <th>Metode Pembayaran</th>
                            <th>Status Pesanan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                    <?php 
                        // ... (rest of your code before the loop) ...
                        if (count($orders) > 0) {
                            foreach ($orders as $order) {
                                ?>
                                <tr>
                                    <td><?= isset($order['ID_Pesan']) ? htmlspecialchars($order['ID_Pesan']) : '-'; ?></td>
                                    <td><?= isset($order['Nama_User']) ? htmlspecialchars($order['Nama_User']) : '-'; ?></td>
                                    <td><?= isset($order['Jadwal']) ? htmlspecialchars($order['Jadwal']) : '-'; ?></td>
                                    <td><?= isset($order['Nama_Lengkap']) ? htmlspecialchars($order['Nama_Lengkap']) : '-'; ?></td>
                                    <td><?= isset($order['Email']) ? htmlspecialchars($order['Email']) : '-'; ?></td>
                                    <td><?= isset($order['Nomor_Telepon']) ? htmlspecialchars($order['Nomor_Telepon']) : '-'; ?></td>
                                    <td><?= isset($order['Booking_Type']) ? htmlspecialchars($order['Booking_Type']) : '-'; ?></td>
                                    <td><?= isset($order['Seat_Nomor']) ? htmlspecialchars($order['Seat_Nomor']) : '-'; ?></td>
                                    <td><?= isset($order['Metode_Pembayaran']) ? htmlspecialchars($order['Metode_Pembayaran']) : '-'; ?></td>
                                    <td><?= isset($order['Status_Pesanan']) ? htmlspecialchars($order['Status_Pesanan']) : '-'; ?></td>
                                    <td>
                                        <?php if (isset($order['ID_Pesan'])) { ?>
                                            <a href="../edit/edit_pemesanan.php?id=<?= $order['ID_Pesan']; ?>" class="btn btn-sm btn-primary me-2"">Edit</a>
                                            <a href="../edit/delete_pemesanan.php?id=<?= $order['ID_Pesan']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pemesanan ini?')">Delete</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data pemesanan</td>
                            </tr>
                            <?php
                        }
                        // ... (rest of your code after the loop) ...
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
