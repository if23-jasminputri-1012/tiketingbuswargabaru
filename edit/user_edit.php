<?php
session_start();
require '../config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID User tidak ditemukan.");
}

$idUser = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE ID_User = :id");
    $stmt->execute(['id' => $idUser]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User tidak ditemukan.");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_user'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $role = $_POST['identify_as'];

    try {
        $stmt = $pdo->prepare("UPDATE Users SET Nama_User = :nama, Email = :email, Phone_Number = :phone, Identify_As = :role WHERE ID_User = :id");
        $stmt->execute([
            'nama' => $nama,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'id' => $idUser,
        ]);
        
        // Menyimpan pesan sukses dalam sesi
        $_SESSION['success'] = "Data pengguna berhasil diperbarui!";
        header("Location: ../admin/users.php");
        exit;
    } catch (PDOException $e) {
        // Menyimpan pesan error dalam sesi
        $_SESSION['error'] = "Terjadi kesalahan saat memperbarui data: " . $e->getMessage();
        header("Location: user_edit.php?id=" . $idUser);
        exit;
    }
}
?>

<?php include '../admin/includes/header.php'; ?>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit User</h2>

    <!-- Tampilkan pesan success atau error jika ada -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); // Hapus pesan sukses setelah ditampilkan ?>
        </div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); // Hapus pesan error setelah ditampilkan ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="shadow p-4 rounded bg-light">
        <div class="mb-3">
            <label for="nama_user" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama_user" name="nama_user" value="<?= htmlspecialchars($user['Nama_User']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['Email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Nomor HP</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['Phone_Number']); ?>">
        </div>
        <div class="mb-3">
            <label for="identify_as" class="form-label">Role</label>
            <select class="form-select" id="identify_as" name="identify_as">
                <option value="Admin" <?= $user['Identify_As'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="Cust" <?= $user['Identify_As'] === 'Cust' ? 'selected' : ''; ?>>Cust</option>
            </select>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="../admin/users.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
<?php include '../admin/includes/footer.php'; ?>
