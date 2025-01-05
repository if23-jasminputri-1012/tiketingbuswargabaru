<?php
// Memastikan session_start() dipanggil sebelum output apa pun
session_start();
require '../config/db.php';

// Cek apakah ID Profile ada di query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Profile tidak ditemukan.");
}

$idProfile = $_GET['id'];

try {
    // Hapus profile berdasarkan ID
    $stmt = $pdo->prepare("DELETE FROM Profile WHERE ID_Profile = :id");
    $stmt->execute(['id' => $idProfile]);

    // Redirect kembali ke halaman profile
    header("Location: ../admin/profile.php");
    exit;
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
