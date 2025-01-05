<?php
session_start();
require '../config/db.php';
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="container-fluid p-4">
        <?php
        $isLoggedIn = isset($_SESSION['username']);
        $username = $isLoggedIn ? $_SESSION['username'] : null;
        $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];

        $promoData = [];
        try {
            $stmt = $pdo->prepare("SELECT * FROM Tambah_Foto");
            $stmt->execute();
            $promoData = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
            <h2 class="mb-4">Kelola Promo</h2>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPromoModal">Tambah Promo</button>
            <!-- Tabel Promo -->
            <div class="table-responsive mb-4">
                <h4>Daftar Promo</h4>
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>ID Promo</th>
                            <th>Foto Promo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php foreach ($promoData as $promo): ?>
                            <tr>
                                <td><?= $promo['ID_Promo']; ?></td>
                                <td><img src="../uploads/<?= htmlspecialchars($promo['Foto_Promo']); ?>" alt="Foto Promo" style="width: 100px; height: auto;"></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPromoModal<?= $promo['ID_Promo']; ?>">Edit</button>
                                    <form action="delete_promo.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $promo['ID_Promo']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus promo ini?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editPromoModal<?= $promo['ID_Promo']; ?>" tabindex="-1" aria-labelledby="editPromoModalLabel<?= $promo['ID_Promo']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPromoModalLabel<?= $promo['ID_Promo']; ?>">Edit Promo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="edit_promo.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $promo['ID_Promo']; ?>">
                                                <div class="mb-3">
                                                    <label for="promoImage<?= $promo['ID_Promo']; ?>" class="form-label">Foto Promo Baru</label>
                                                    <input type="file" class="form-control" id="promoImage<?= $promo['ID_Promo']; ?>" name="promoImage">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($promoData)): ?>
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data promo yang tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Promo -->
<div class="modal fade" id="addPromoModal" tabindex="-1" aria-labelledby="addPromoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPromoModalLabel">Tambah Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="add_promo.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="promoImage" class="form-label">Foto Promo</label>
                        <input type="file" class="form-control" id="promoImage" name="promoImage" required>
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
