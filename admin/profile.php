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
        
        <!-- Konten Dasboard -->
        <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
            <h2 class="mb-4">Profile</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>ID Profile</th>
                            <th>ID User</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date Of Birth</th>
                            <th>Profile Picture</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php if (!empty($profiles)): ?>
                            <?php foreach ($profiles as $profile): ?>
                                <tr>
                                    <td><?= $profile['ID_Profile'] ?></td>
                                    <td><?= $profile['ID_User'] ?></td>
                                    <td><?= htmlspecialchars($profile['First_Name']) ?></td>
                                    <td><?= htmlspecialchars($profile['Last_Name']) ?></td>
                                    <td><?= $profile['Date_Of_Birth'] ?></td>
                                    <td>
                                        <?php if ($profile['Profile_Picture']): ?>
                                            <img alt="Profile picture" height="50" src="<?= htmlspecialchars($profile['Profile_Picture']) ?>" width="50"/>
                                        <?php else: ?>
                                            <img alt="Default profile picture" height="50" src="default-profile.png" width="50"/>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="../edit/edit_profile.php?id=<?= $profile['ID_Profile'] ?>" class="btn btn-sm btn-primary me-2">Edit</a>
                                        <a href="../edit/delete_profile.php?id=<?= $profile['ID_Profile'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus profil ini?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data profil yang tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
