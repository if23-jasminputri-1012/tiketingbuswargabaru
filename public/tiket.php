<?php
session_start();
require '../config/db.php';

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : null;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/warga_baru_expres.jpg">
    <title>Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        /* Card Styles */
        .card {
            border-radius: 15px;  /* Rounded corners for the card */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
            overflow: hidden; /* Ensure no content overflows the rounded corners */
        }

        .card img {
            width: 100%; /* Make image fill the card width */
            height: 200px; /* Set a fixed height for the images */
            object-fit: cover; /* Ensure image is well-cropped */
            border-bottom: 2px solid #ddd; /* Add a border below the image */
        }

        .card-body {
            padding: 20px;  /* Add some space inside the card */
            background-color: #f8f9fa;  /* Light background color for body */
        }

        .card-title {
            font-size: 1.25rem;  /* Slightly larger title */
            font-weight: 600;  /* Make title bold */
            color: #333;  /* Dark text for the title */
            margin-bottom: 15px;  /* Add space between title and other elements */
        }

        .card-text {
            color: #555;  /* Slightly lighter color for text */
            margin-bottom: 10px;  /* Add space between text elements */
        }

        .card a.btn {
            width: 100%;  /* Make button fill the width of the card */
            background-color: #007bff;  /* Blue background for button */
            color: white;  /* White text */
            padding: 10px 0;  /* Add padding for button */
            text-align: center;  /* Center the text in the button */
            font-weight: bold;  /* Make button text bold */
            border-radius: 5px;  /* Slightly rounded button edges */
            text-decoration: none;  /* Remove underline */
            transition: background-color 0.3s ease;  /* Smooth hover transition */
        }

        /* Button Hover Effect */
        .card a.btn:hover {
            background-color: #0056b3;  /* Darker blue on hover */
        }

        /* Card Hover Effect */
        .card:hover {
            transform: translateY(-5px);  /* Lift the card slightly */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);  /* Increase shadow on hover */
        }

    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../beranda.php">
                <img src="../image/warga_baru_expres-removebg-preview.png" alt="Buss Lightyear" width="42" height="42" class="me-2" />
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

    <!-- Tiket -->
    <section class="container mt-5 pt-5">
        <h2 class="text-center text-primary mb-5 pt-1 fw-bold">Tiket yang tersedia</h2>
        <div class="row">
            <!-- Ticket Card -->
            <div class="col-md-4">
                <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                    <img src="../image/buss-exe.jpg" alt="Bus Image" class="card-img-top img-fluid" style="object-fit: cover; height: 200px;">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-primary fw-bold">Kp. Rambutan - Karawang</h5>
                        <p class="card-text text-muted">Bus: Hiba Utama</p>
                        <p class="card-text text-muted">Kursi Tersedia: 30</p>
                        <p class="card-text text-muted">Jam Berangkat: 08:00 AM</p>
                        <p class="card-text text-success fw-bold">Harga: Rp.50.000</p>
                        <a href="pemesanan_tiket.php" class="btn btn-primary mt-3 w-100 rounded-pill shadow-sm">Pesan Tiket</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                    <img src="../image/buss-exe.jpg" alt="Bus Image" class="card-img-top img-fluid" style="object-fit: cover; height: 200px;">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-primary fw-bold">Bekasi - Cikarang</h5>
                        <p class="card-text text-muted">Bus: Hiba Utama</p>
                        <p class="card-text text-muted">Kursi Tersedia: 40</p>
                        <p class="card-text text-muted">Jam Berangkat: 10:00 AM</p>
                        <p class="card-text text-success fw-bold">Harga: Rp.40.000</p>
                        <a href="pemesanan_tiket.php" class="btn btn-primary mt-3 w-100 rounded-pill shadow-sm">Pesan Tiket</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                    <img src="../image/buss-exe.jpg" alt="Bus Image" class="card-img-top img-fluid" style="object-fit: cover; height: 200px;">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-primary fw-bold">Badami - Subang</h5>
                        <p class="card-text text-muted">Bus: Hiba Utama</p>
                        <p class="card-text text-muted">Kursi Tersedia: 30</p>
                        <p class="card-text text-muted">Jam Berangkat: 09:00 AM</p>
                        <p class="card-text text-success fw-bold">Harga: Rp.60.000</p>
                        <a href="pemesanan_tiket.php" class="btn btn-primary mt-3 w-100 rounded-pill shadow-sm">Pesan Tiket</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>