<?php
// Memastikan session_start() dipanggil sebelum output apa pun
session_start();
require '../config/db.php'; // Pastikan ini sudah benar

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];

// Query untuk mengambil data pembatalan tiket
$query = "SELECT p.ID_Pembatalan, p.Tanggal_Pembatalan, p.Alasan_Pembatalan, t.ID_Pesan 
        FROM Pembatalan p 
        JOIN Pesan_Tiket t ON p.ID_Pesan = t.ID_Pesan";

try {
    // Menjalankan query menggunakan PDO
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex">
<?php include 'includes/sidebar.php'; ?>

<!-- Konten Utama -->
<div class="container-fluid p-4">
    <!-- Header -->
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

    <!-- Konten Dashboard -->
    <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
        <div class="table-responsive">
            <h2 class="mb-4">Pembatalan Tiket</h2>
            <table class="table table-bordered table-striped shadow-sm">
                <thead class="table-dark text-center align-middle">
                    <tr>
                        <th>ID Pembatalan</th>
                        <th>ID Pesan</th>
                        <th>Tanggal Pembatalan</th>
                        <th>Alasan Pembatalan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center align-middle">
                    <?php if (count($result) > 0): ?>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td><?= $row['ID_Pembatalan']; ?></td>
                                <td><?= $row['ID_Pesan']; ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($row['Tanggal_Pembatalan'])); ?></td>
                                <td><?= htmlspecialchars($row['Alasan_Pembatalan']); ?></td>
                                <td>
                                    <a href="edit_pembatalan.php?id=<?= $row['ID_Pembatalan']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_pembatalan.php?id=<?= $row['ID_Pembatalan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pembatalan ini?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data pembatalan tiket.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<?php include 'includes/footer.php'; ?>
