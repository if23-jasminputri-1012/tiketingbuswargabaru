<?php
session_start();
require '../config/db.php'; // Pastikan path benar

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: login.php');
    exit;
}

// Ambil ID pembatalan dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // Hapus data berdasarkan ID
    $query = "DELETE FROM Pembatalan WHERE ID_Pembatalan = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $_SESSION['message'] = 'Data pembatalan berhasil dihapus.';
    header('Location: ../admin/pembatalan_tiket.php');
    exit;
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
