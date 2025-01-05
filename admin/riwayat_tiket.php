<?php
// Memulai session
session_start();

// Menghubungkan ke database menggunakan PDO
require '../config/db.php';

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];

try {
    // Menjalankan query untuk mengambil data riwayat tiket
    $query = "SELECT * FROM riwayat_tiket"; // Sesuaikan dengan nama tabel Anda
    $stmt = $pdo->query($query); // Menggunakan query PDO untuk mendapatkan hasil

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
            <h2 class="mb-4">Riwayat Tiket</h2>
            <table class="table table-bordered table-striped shadow-sm">
                <thead class="table-dark text-center align-middle">
                    <tr>
                        <th>ID Riwayat</th>
                        <th>ID Pesan</th>
                        <th>Nama Pemesan</th>
                        <th>Tanggal Beli</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center align-middle">
                    <?php
                    // Cek apakah ada hasil dari query dan menampilkan data
                    if ($stmt->rowCount() > 0): // Cek apakah ada hasil
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?= $row['ID_Riwayat']; ?></td>
                                <td><?= $row['ID_Pesan']; ?></td>
                                <td><?= htmlspecialchars($row['Nama_Pemesan']); ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($row['Tanggal_Beli'])); ?></td>
                                <td>
                                    <span class="badge 
                                    <?php
                                        // Menambahkan class CSS sesuai dengan status pembayaran
                                        if ($row['Status_Pembayaran'] == 'Pending') {
                                            echo 'bg-warning';
                                        } elseif ($row['Status_Pembayaran'] == 'Paid') {
                                            echo 'bg-success';
                                        } else {
                                            echo 'bg-danger';
                                        }
                                    ?>">
                                        <?= htmlspecialchars($row['Status_Pembayaran']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_riwayat.php?id=<?= $row['ID_Riwayat']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_riwayat.php?id=<?= $row['ID_Riwayat']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile;
                    else:
                        echo "<tr><td colspan='6' class='text-center'>Tidak ada data riwayat tiket</td></tr>";
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>   
</div>
</div>

<?php include 'includes/footer.php'; ?>
