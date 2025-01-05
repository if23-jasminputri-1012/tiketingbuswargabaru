<!-- includes/sidebar.php -->
<div class="sidebar p-3">
    <h4 class="text-white">Dashboard</h4>
    <ul class="nav flex-column mt-4">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="user.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pengguna.php' ? 'active' : '' ?>">
                <i class="fas fa-users me-2"></i> Users
            </a>
        </li>
        <li class="nav-item">
            <a href="profile.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pengguna.php' ? 'active' : '' ?>">
                <i class="fas fa-id-card me-2"></i> Profile
            </a>
        </li>

        <li class="nav-item">
            <a href="data_pemesanan.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'data_pemesanan.php' ? 'active' : '' ?>">
                <i class="fas fa-ticket-alt me-2"></i> Data Pemesanan
            </a>
        </li>

        <li class="nav-item">
            <a href="pembatalan_tiket.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'jadwal_rute.php' ? 'active' : '' ?>">
                <i class="fas fa-ban me-2"></i> Pembatalan Tiket
            </a>
        </li>

        <li class="nav-item">
            <a href="riwayat_tiket.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'jadwal_rute.php' ? 'active' : '' ?>">
                <i class="fas fa-history me-2"></i> Riwayat Tiket
            </a>
        </li>
        

        <li class="nav-item">
            <a href="jadwal_rute.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'jadwal_rute.php' ? 'active' : '' ?>">
                <i class="fas fa-route me-2"></i> Jadwal & Rute
            </a>
        </li>

        <li class="nav-item">
            <a href="bus.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'jadwal_rute.php' ? 'active' : '' ?>">
                <i class="fas fa-bus me-2"></i> Bus
            </a>
        </li>

        <li class="nav-item">
            <a href="promo.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'jadwal_rute.php' ? 'active' : '' ?>">
                <i class="fas fa-image me-2"></i> Promo
            </a>
        </li>

        <li class="nav-item">
            <a href="transaksi.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'jadwal_rute.php' ? 'active' : '' ?>">
                <i class="fas fa-money-check-alt me-2"></i> Transaksi
            </a>
        </li>

        <li class="nav-item">
            <a href="invoice.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'jadwal_rute.php' ? 'active' : '' ?>">
                <i class="fas fa-file-invoice me-2"></i> Invoices
            </a>
        </li>

        
    </ul>
</div>
