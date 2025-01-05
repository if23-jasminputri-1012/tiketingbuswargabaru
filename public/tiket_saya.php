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
    <title>Tiket Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/jadwal_rute.css">
    <!--font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit&family=Poppins:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        .ticket-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .ticket-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .status-confirmed {
            color: green;
            font-weight: bold;
        }

        .status-cancelled {
            color: red;
            font-weight: bold;
        }

        .modal-content {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
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

    <!-- Riwayat Tiket -->
    <div class="container mt-5 pt-5">
        <h2 class="text-center text-primary  fw-bold mb-4">Riwayat Tiket</h2>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="ticket-card p-3 shadow-sm rounded" data-toggle="modal" data-target="#ticketModal" onclick="showTicketDetails('M. Fahri Salam', '21 November 2024', 'Jakarta - Bandung', 'BD-001', 'Terkonfirmasi')">
                    <h4>Nama Penumpang: <span>M. Fahri Salam</span></h4>
                    <p>Tanggal Perjalanan: <span>21 November 2024</span></p>
                    <p>Rute: <span>Jakarta - Bandung</span></p>
                    <p>Nomor Bus: <span>BD-001</span></p>
                    <p>Status: <span class="status-confirmed">Terkonfirmasi</span></p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="ticket-card p-3 shadow-sm rounded" data-toggle="modal" data-target="#ticketModal" onclick="showTicketDetails('Ahmad Putra', '20 November 2024', 'Surabaya - Malang', 'SB-013', 'Dibatalkan')">
                    <h4>Nama Penumpang: <span>Ahmad Putra</span></h4>
                    <p>Tanggal Perjalanan: <span>20 November 2024</span></p>
                    <p>Rute: <span>Surabaya - Malang</span></p>
                    <p>Nomor Bus: <span>SB-013</span></p>
                    <p>Status: <span class="status-cancelled">Dibatalkan</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Popup -->
    <div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticketModalLabel">Detail Tiket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 id="passengerName"></h4>
                    <p id="travelDate"></p>
                    <p id="route"></p>
                    <p id="busNumber"></p>
                    <p id="ticketStatus"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="printButton">Cetak Tiket</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
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

    <script>
        function showTicketDetails(name, date, route, busNumber, status) {
        document.getElementById('passengerName').innerText = "Nama Penumpang: " + name;
        document.getElementById('travelDate').innerText = "Tanggal Perjalanan: " + date;
        document.getElementById('route').innerText = "Rute: " + route;
        document.getElementById('busNumber').innerText = "Nomor Bus: " + busNumber;
        document.getElementById('ticketStatus').innerText = "Status: " + status;

        // Optional: Functionality for print button
        document.getElementById('printButton').onclick = function() {
            window.print(); // Trigger print dialog
        };
    }
    </script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>