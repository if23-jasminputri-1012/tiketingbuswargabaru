<?php
session_start();
require '../config/db.php'; 
include 'includes/header.php'; 
?>

<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>

    <div class="container-fluid p-4">
        <!-- Header -->
        <?php
        $isLoggedIn = isset($_SESSION['username']);
        $username = $isLoggedIn ? htmlspecialchars($_SESSION['username']) : null;
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
                                <i class="fas fa-user-circle"></i> <?= $isAdmin ? 'Admin' : $username; ?>
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

        <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
            <h2 class="mb-4">Daftar Bus</h2>
            <!-- Tombol Tambah Data -->
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBusModal">Tambah Bus</button>

            <!-- Tabel Data Bus -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>ID Bus</th>
                            <th>Nama Bus</th>
                            <th>Booking Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php
                        $query = "SELECT * FROM Bus";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['ID_Bus'] . "</td>";
                                echo "<td>" . htmlspecialchars($row['Nama_Bus']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Booking_Type']) . "</td>";
                                echo "<td>
                                        <a href='../edit/edit_bus.php?id=" . $row['ID_Bus'] . "' class='btn btn-sm btn-primary me-2'>Edit</a>
                                        <a href='../delete/delete_bus.php?id=" . $row['ID_Bus'] . "' class='btn btn-sm btn-danger'>Delete</a>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>Tidak ada data bus</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Bus -->
<div class="modal fade" id="addBusModal" tabindex="-1" aria-labelledby="addBusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBusModalLabel">Tambah Data Bus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="add_bus.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_bus" class="form-label">Nama Bus</label>
                        <input type="text" class="form-control" id="nama_bus" name="nama_bus" required>
                    </div>
                    <div class="mb-3">
                        <label for="booking_type" class="form-label">Booking Type</label>
                        <select class="form-select" id="booking_type" name="booking_type" required>
                            <option value="Full Bus">Full Bus</option>
                            <option value="Seat Event">Seat Event</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
