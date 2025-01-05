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

        // Ambil data dari tabel Profile
        $profiles = [];
        try {
            $stmt = $pdo->prepare("SELECT * FROM Profile");
            $stmt->execute();
            $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        <!-- Konten Dashboard -->
        <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
            <h1 class="mb-4">Dashboard</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users Card -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold">Total Users</h2>
                            <p class="text-2xl font-bold">150</p>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 text-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold">Total Revenue</h2>
                            <p class="text-2xl font-bold">$12,345.67</p>
                        </div>
                    </div>
                </div>

                <!-- Total Bookings Card -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 text-yellow-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-ticket-alt text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold">Total Bookings</h2>
                            <p class="text-2xl font-bold">320</p>
                        </div>
                    </div>
                </div>

                <!-- Total Cancellations Card -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold">Total Cancellations</h2>
                            <p class="text-2xl font-bold">15</p>
                        </div>
                    </div>
                </div>

                <!-- Available Tickets Card (Full Bus) -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 text-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-bus text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold">Bus</h2>
                            <p class="text-2xl font-bold">
                                <?php
                                // Hitung jumlah bus dengan tiket tersedia
                                try {
                                    $stmt = $pdo->prepare("SELECT COUNT(*) AS available_buses FROM Buses WHERE tickets_available > 0");
                                    $stmt->execute();
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo $result['available_buses'] ?? 0;
                                } catch (PDOException $e) {
                                    echo "7";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Available Tickets Card (Seats) -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-teal-100 text-teal-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-chair text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold">Kursi</h2>
                            <p class="text-2xl font-bold">
                                <?php
                                // Hitung jumlah total seat yang tersedia
                                try {
                                    $stmt = $pdo->prepare("SELECT SUM(seats_available) AS total_seats FROM Buses WHERE seats_available > 0");
                                    $stmt->execute();
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    echo $result['total_seats'] ?? 0;
                                } catch (PDOException $e) {
                                    echo "60";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Users Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold mb-4">Users Growth</h2>
                    <canvas id="usersChart"></canvas>
                </div>

                <!-- Revenue Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold mb-4">Revenue Growth</h2>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <script>
        // Users Chart
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        const usersChart = new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [{
                    label: 'Users',
                    data: [50, 60, 70, 80, 90, 100, 150],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [{
                    label: 'Revenue',
                    data: [2000, 3000, 4000, 5000, 6000, 7000, 12345.67],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>
    </div>
</div>

<?php include 'includes/footer.php'; ?>