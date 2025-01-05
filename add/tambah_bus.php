<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_bus = htmlspecialchars($_POST['nama_bus']);
    $booking_type = htmlspecialchars($_POST['booking_type']);

    $query = "INSERT INTO Bus (Nama_Bus, Booking_Type) VALUES (:nama_bus, :booking_type)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nama_bus', $nama_bus);
    $stmt->bindParam(':booking_type', $booking_type);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data bus berhasil ditambahkan.";
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan data bus.";
    }
    header("Location: ../admin/bus.php");
    exit();
}
?>
