<?php
session_start();
require '../config/db.php';  // Koneksi ke database

// Cek apakah ID Pemesanan ada di URL
if (!isset($_GET['id'])) {
    die("ID Pemesanan tidak ditemukan.");
}

$id_pesan = $_GET['id'];

// Query untuk menghapus pemesanan berdasarkan ID
$sql = "DELETE FROM Pesan_Tiket WHERE ID_Pesan = :id_pesan";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute(['id_pesan' => $id_pesan]);
    // Redirect setelah penghapusan
    header('Location: ../admin/data_pemesanan.php');
    exit;
} catch (PDOException $e) {
    die("Gagal menghapus pemesanan: " . $e->getMessage());
}
?>
