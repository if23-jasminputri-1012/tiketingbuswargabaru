<?php
// Memastikan session_start() dipanggil sebelum output apa pun
session_start();
require '../config/db.php';
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex">
<?php include 'includes/sidebar.php'; ?>

<!-- Konten Utama -->
<div class="container-fluid p-4">
    <!-- Header -->
    <?php 
      // Cek apakah user sudah login
    $isLoggedIn = isset($_SESSION['username']);
    $username = $isLoggedIn ? $_SESSION['username'] : null;
    $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
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
                        <li><a class="dropdown-item text-danger" href="logout.php">Log Out</a></li>
                    </ul>
                <?php else: ?>
                    <a class="nav-link" href="login.php"><i class="fas fa-user-circle"></i> Login</a>
                <?php endif; ?>
            </li>
            </ul>
        </div>
    </nav>

        <!-- Konten Dashboard -->
        <!-- (Kode konten utama lainnya dapat ditempatkan di sini) -->
        <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
        <div class="table-responsive">
        <h2 class="mb-4">Transaksi</h2>

        </div>
    </div>
        
    </div>
</div>

<?php include 'includes/footer.php'; ?>
