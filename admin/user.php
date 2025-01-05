<?php
session_start();
require '../config/db.php';
include 'includes/header.php';

// Koneksi ke database menggunakan PDO
try {
   $pdo = new PDO("mysql:host=localhost;dbname=tiket_bus", "root", "");
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
   die("Koneksi gagal: " . $e->getMessage());
}

?>

<div class="d-flex">
   <?php include 'includes/sidebar.php'; ?>

   <div class="container-fluid p-4">
      <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm">
            <div class="container-fluid">
               <span class="navbar-brand fw-bold">Admin Warga Baru Express</span>
               <ul class="navbar-nav ms-auto">
                  <li class="nav-item">
                        <a href="notifikasi.php" class="nav-link"><i class="fas fa-bell"></i> Notifikasi</a>
                  </li>
                  <li class="nav-item dropdown">
                        <?php if (isset($_SESSION['username'])): ?>
                           <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="fas fa-user-circle"></i> <?= $_SESSION['username']; ?>
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

         <!-- Konten Utama -->
         <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
            <h2 class="mb-4">Users</h2>
               <div class="table-responsive">
                  <table class="table table-bordered table-striped shadow-sm">
                        <thead class="table-dark text-center align-middle">
                           <tr>
                              <th>ID User</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone Number</th>
                              <th>Role</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                           <?php
                           // Query untuk mengambil data pengguna
                           $sql = "SELECT * FROM Users";
                           $stmt = $pdo->prepare($sql);
                           $stmt->execute();
                           $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                           if ($users):
                              foreach ($users as $user): 
                           ?>
                              <tr>
                                    <td><?= htmlspecialchars($user['ID_User']) ?></td>
                                    <td><?= htmlspecialchars($user['Nama_User']) ?></td>
                                    <td><?= htmlspecialchars($user['Email']) ?></td>
                                    <td><?= htmlspecialchars($user['Phone_Number']) ?></td>
                                    <td><?= htmlspecialchars($user['Identify_As']) ?></td>
                                    <td>
                                       <a href="../edit/edit_user.php?id=<?= $user['ID_User'] ?>" class="btn btn-sm btn-primary me-2">Edit</a>
                                       <a href="../edit/delete_user.php?id=<?= $user['ID_User'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Delete</a>
                                    </td>
                              </tr>
                           <?php 
                              endforeach;
                           else: 
                           ?>
                              <tr>
                                 <td colspan="6" class="text-center">Tidak ada data pengguna yang tersedia.</td>
                              </tr>
                           <?php endif; ?>
                        </tbody>
                     </table>
                  </div>
            </div>
      </div>
</div>

<?php include 'includes/footer.php'; ?>
