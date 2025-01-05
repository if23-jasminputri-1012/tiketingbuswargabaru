<?php
    // Koneksi ke database
    $host = 'localhost';
    $dbname = 'tiket_bus';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Koneksi berhasil!";
    } catch (PDOException $e) {
        die("Koneksi Gagal: " . $e->getMessage());
    }
?>