<?php
session_start();
require '../config/db.php';

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : null;


// Query untuk mengambil data jadwal rute dan bus
$sql = "SELECT j.ID_Jadwal, b.Nama_Bus, r.Nama_Rute, r.Kota_Asal, r.Kota_Tujuan, r.Jarak, r.Harga, j.Jam_Take_Off, j.Jam_Pulang, j.Estimasi, j.Operation
        FROM Jadwal_Info j
        JOIN Bus b ON j.ID_Bus = b.ID_Bus
        JOIN Rute r ON j.ID_Rute = r.ID_Rute";

try {
    // Menjalankan query
    $stmt = $pdo->query($sql);
} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/warga_baru_expres.jpg">
    <title>Jadwal & Rute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/jadwal_rute.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/jadwal_rute.css">
    <!--font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit&family=Poppins:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            padding-top: 20px; /* Tinggi navbar */
        }
        /* Styling tambahan untuk tabel */
        .fixed-table {
            table-layout: fixed;
            width: 100%;
        }
        .table-container {
            overflow-y: auto;
            max-height: 400px;
            margin-bottom: 20px;
        }
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Memastikan smooth scrolling di perangkat mobile */
        }
    </style>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../beranda.php">
                <img src="../image/warga_baru_expres-removebg-preview.png" alt="Warga Baru Express" width="42" height="42" class="me-2" />
                <span class="text-custom fs-4 fw-bolder">Warga Baru Express</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link mx-2" href="../beranda.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link mx-2" href="jadwal_rute.php">Jadwal & Rute</a></li>
                    <li class="nav-item"><a class="nav-link mx-2" href="tiket.php">Tiket</a></li>
                    <li class="nav-item"><a class="nav-link mx-2 active" href="tiket_saya.php">Tiket Saya</a></li>
                    
                    <!-- Dropdown Profil -->
                    <li class="nav-item dropdown">
                        <?php if ($isLoggedIn): ?>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($username); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="../profile.php">Profil</a></li>
                                <li><a class="dropdown-item text-danger" href="../logout.php">Log Out</a></li>
                            </ul>
                        <?php else: ?>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> Profil
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="../login.php">Login</a></li>
                            </ul>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--informasi bus-->
    <div class="container mt-4 pt-4">
        <h3 class="text-center mb-4 pt-5 text-primary fw-bolder">Informasi Keberangkatan Bus</h3>
        <!-- Tabel Keberangkatan Bus -->
        <div class="table-container table-responsive">
            <table class="table table-striped table-hover fixed-table">
                <thead class="table-dark text-center align-middle">
                    <tr>
                        <th scope="col">ID Bus</th>
                        <th scope="col">Rute</th>
                        <th scope="col">Berangkat</th>
                        <th scope="col">Jam Pulang</th>
                        <th scope="col">Estimasi</th>
                        <th scope="col">Operasi</th>
                    </tr>
                </thead>
                <tbody class="text-center align-middle">
                    <?php
                    // Query untuk mendapatkan data jadwal dengan nama kota asal dan kota tujuan
                    $query = "
                        SELECT j.ID_Bus, r.Nama_Rute, r.Kota_Asal, r.Kota_Tujuan, j.Jam_Take_Off, j.Jam_Pulang, j.Estimasi, j.Operation
                        FROM Jadwal_Info j
                        JOIN Rute r ON j.ID_Rute = r.ID_Rute
                    ";

                    // Menyiapkan dan mengeksekusi query
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();

                    // Mengecek apakah ada data jadwal
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Menampilkan nama rute berdasarkan kota asal dan kota tujuan
                            $namaRute = $row['Kota_Asal'] . " - " . $row['Kota_Tujuan'];

                            echo "<tr>";
                            echo "<td>" . $row['ID_Bus'] . "</td>";
                            echo "<td>" . $namaRute . "</td>"; // Menampilkan nama rute yang digabungkan
                            echo "<td>" . $row['Jam_Take_Off'] . "</td>";
                            echo "<td>" . $row['Jam_Pulang'] . "</td>";
                            echo "<td>" . $row['Estimasi'] . "</td>";
                            echo "<td>" . $row['Operation'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Tidak ada jadwal tersedia</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Informasi Bus Full Seat -->
        <h3 class="text-center mb-4 pt-5 text-primary fw-bolder">Informasi Bus</h3>
        <div class="table-container">
            <table class="table table-striped table-hover fixed-table">
                <thead class="table-dark text-center align-middle">
                    <tr>
                        <th scope="col">ID Bus</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Kapasitas</th>
                    </tr>
                </thead>
                <tbody class="text-center align-middle">
                    <?php
                    // Query untuk mengambil data bus
                    $sql_bus = "SELECT * FROM Bus";
                    $stmt_bus = $pdo->query($sql_bus);

                    // Mengecek apakah ada data bus
                    if ($stmt_bus->rowCount() > 0) {
                        while ($row_bus = $stmt_bus->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $row_bus['ID_Bus'] . "</td>";
                            echo "<td>" . $row_bus['Nama_Bus'] . "</td>";
                            echo "<td>" . $row_bus['Booking_Type'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>Tidak ada data bus tersedia</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- footer -->
    <footer class="footer bg-dark text-white mt-5 py-4">
        <div class="container mt-2 pt-2">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold">Tentang Warga Baru Express</h5>
                    <p>Warga Baru Express adalah layanan transportasi terpercaya yang menyediakan perjalanan nyaman dan aman ke berbagai kota tujuan.</p>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold">Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="../beranda.php" class="text-white text-decoration-none">Beranda</a></li>
                        <li><a href="jadwal_rute.php" class="text-white text-decoration-none">Jadwal & Rute</a></li>
                        <li><a href="tiket.php" class="text-white text-decoration-none">Pesan Tiket</a></li>
                        <li><a href="tiket_saya.php" class="text-white text-decoration-none">Tiket Saya</a></li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h5 class="fw-bold">Hubungi Kami</h5>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Suroto Kunto No. 207 RT. 001 RW 006 Kel. Adiarsa Timur Kec. Karawang Timur Kab. Karawang</p>
                    <p><i class="fas fa-phone-alt"></i> (0267) 403000</p>
                    <p><i class="fas fa-envelope"></i> info@wargabaruexpress.com</p>
                </div>
            </div>

            <!-- Social Media Icons -->
            <div class="text-center mt-4">
                <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
            </div>

            <!-- Footer Copyright -->
            <div class="text-center mt-4">
                <p style="margin: 0; text-align: center;">&copy; 2024 Warga Baru Express. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
