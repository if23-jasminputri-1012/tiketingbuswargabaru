<?php
// Memastikan session_start() dipanggil sebelum output apa pun
session_start();
require '../config/db.php';
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <?php 
        // Cek apakah user sudah login
        $isLoggedIn = isset($_SESSION['username']);
        $username = $isLoggedIn ? $_SESSION['username'] : null;
        $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];

        // Ambil data dari tabel Profile (jika diperlukan)
        $profiles = [];
        if ($isLoggedIn) { // Hanya ambil data Profile jika user sudah login
            try {
                $stmt = $pdo->prepare("SELECT * FROM Profile WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $profile = $stmt->fetch(PDO::FETCH_ASSOC); 
                if ($profile) {
                    $profiles[] = $profile; 
                }
            } catch (PDOException $e) {
                die("Error fetching profile data: " . $e->getMessage());
            }
        }

        // Ambil data Rute
        $rutes = [];
        try {
            $stmt = $pdo->prepare("SELECT * FROM Rute");
            $stmt->execute();
            $rutes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error fetching route data: " . $e->getMessage());
        }

        // Ambil data Jadwal
        $schedules = [];
        try {
            $stmt = $pdo->prepare("SELECT j.*, r.Nama_Rute 
                                FROM Jadwal_Info j
                                JOIN Rute r ON j.ID_Rute = r.ID_Rute");
            $stmt->execute();
            $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error fetching schedule data: " . $e->getMessage());
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

        <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
            <div class="table-responsive">
                <h1 class="mb-4 fw-bold">Rute Bus</h1>
                <a href="../add/rute_bus.php" class="btn btn-primary mb-3">Tambah Data</a>
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>Nama Rute</th>
                            <th>Kota Asal</th>
                            <th>Kota Tujuan</th>
                            <th>Jarak (km)</th>
                            <th>Harga (Rp)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php foreach ($rutes as $rute): ?>
                            <tr>
                                <td><?= htmlspecialchars($rute['Nama_Rute']); ?></td>
                                <td><?= htmlspecialchars($rute['Kota_Asal']); ?></td>
                                <td><?= htmlspecialchars($rute['Kota_Tujuan']); ?></td>
                                <td><?= htmlspecialchars($rute['Jarak']); ?> km</td>
                                <td>Rp <?= number_format($rute['Harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <a href="../edit/rute_edit.php?idRute=<?= $rute['ID_Rute']; ?>" class="btn btn-sm btn-primary me-2">Edit</a>
                                    <a href="../edit/rute_delete.php=<?= $rute['ID_Rute']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this route?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h2 class="mb-4 fw-bold">Jadwal Bus</h2>
                <a href="../add/jadwal_bus.php" class="btn btn-primary mb-3">Tambah Data</a>
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>ID Jadwal</th>
                            <th>Nama Rute</th>
                            <th>Jam Take Off</th>
                            <th>Jam Pulang</th>
                            <th>Estimasi</th>
                            <th>Operation</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php foreach ($schedules as $schedule): ?>
                            <tr>
                                <td><?= htmlspecialchars($schedule['ID_Jadwal']); ?></td>
                                <td><?= htmlspecialchars($schedule['Nama_Rute']); ?></td>
                                <td><?= htmlspecialchars($schedule['Jam_Take_Off']); ?></td>
                                <td><?= htmlspecialchars($schedule['Jam_Pulang']); ?></td>
                                <td><?= htmlspecialchars($schedule['Estimasi']); ?></td>
                                <td><?= htmlspecialchars($schedule['Operation']); ?></td>
                                <td>
                                    <a href="edit_jadwal.php?idJadwal=<?= $schedule['ID_Jadwal']; ?>"class="btn btn-sm btn-primary me-2"">Edit</a>
                                    <a href="?deleteJadwal=<?= $schedule['ID_Jadwal']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>