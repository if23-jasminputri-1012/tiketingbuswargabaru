<?php
session_start();
require '../config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID User tidak ditemukan.");
}

$idUser = $_GET['id'];

try {
    // Hapus user dari database
    $stmt = $pdo->prepare("DELETE FROM Users WHERE ID_User = :id");
    $stmt->execute(['id' => $idUser]);

    $_SESSION['success'] = "User berhasil dihapus!";
    header("Location: ../admin/users.php");
    exit;
} catch (PDOException $e) {
    $_SESSION['error'] = "Terjadi kesalahan saat menghapus data: " . $e->getMessage();
    header("Location: ../admin/users.php");
    exit;
}
