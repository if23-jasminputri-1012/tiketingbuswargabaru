<?php
// Memastikan session_start() dipanggil sebelum output apa pun
session_start();
require '../config/db.php';

// Cek apakah ID Profile ada di query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Profile tidak ditemukan.");
}

$idProfile = $_GET['id'];

// Ambil data profile berdasarkan ID
try {
    $stmt = $pdo->prepare("SELECT * FROM Profile WHERE ID_Profile = :id");
    $stmt->execute(['id' => $idProfile]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profile) {
        die("Profile tidak ditemukan.");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $dateOfBirth = $_POST['date_of_birth'];
    $profilePicture = $_FILES['profile_picture'];

    // Upload profile picture jika ada
    $profilePicturePath = $profile['Profile_Picture']; // Path default jika tidak diupdate
    if ($profilePicture['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";
        $profilePicturePath = $targetDir . basename($profilePicture['name']);
        move_uploaded_file($profilePicture['tmp_name'], $profilePicturePath);
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE Profile 
            SET First_Name = :first_name, 
                Last_Name = :last_name, 
                Date_Of_Birth = :date_of_birth, 
                Profile_Picture = :profile_picture 
            WHERE ID_Profile = :id
        ");
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'date_of_birth' => $dateOfBirth,
            'profile_picture' => $profilePicturePath,
            'id' => $idProfile,
        ]);

        // Redirect kembali ke halaman profile
        header("Location: ../admin/profile.php");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="container mt-4">
    <h2>Edit Profile</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="first_name" class="form-label">Nama Depanlabel>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($profile['First_Name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Nama Belakang</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($profile['Last_Name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= $profile['Date_Of_Birth'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="profile_picture" class="form-label">Foto Profile</label>
            <input type="file" class="form-control" id="profile_picture" name="profile_picture">
            <?php if ($profile['Profile_Picture']): ?>
                <img src="<?= htmlspecialchars($profile['Profile_Picture']) ?>" alt="Profile Picture" width="100">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="profile.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
