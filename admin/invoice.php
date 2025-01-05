<?php
session_start();
require '../config/db.php';

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : null;
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
?>

<?php include 'includes/header.php'; ?>

<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>

    <!-- Konten Utama -->
    <div class="container-fluid p-4">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand fw-bold">Admin Warga Baru Express</span>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="notifikasi.php" class="nav-link"><i class="fas fa-bell"></i> Notifikasi</a>
                    </li>
                    <li class="nav-item dropdown">
                        <?php if ($isLoggedIn): ?>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?= $isAdmin ? 'Admin' : htmlspecialchars($username); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item text-danger" href="logout.php">Log Out</a></li>
                            </ul>
                        <?php else: ?>
                            <a class="nav-link" href="login.php"><i class="fas fa-user-circle"></i> Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Notifikasi -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Konten Dashboard -->
        <div class="content ms-250" style="margin-left: 250px; padding: 20px; width: calc(100% - 250px);">
            <div class="table-responsive">
                <h2 class="mb-4">Daftar Invoice</h2>
                <!-- Tabel Data Invoice -->
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>ID Invoice</th>
                            <th>ID Transaksi</th>
                            <th>Nomor Invoice</th>
                            <th>Tanggal Invoice</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        <?php
                        $query = "SELECT * FROM Invoices";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['ID_Invoice'] . "</td>";
                                echo "<td>" . $row['ID_Transaction'] . "</td>";
                                echo "<td>" . htmlspecialchars($row['Invoice_Number']) . "</td>";
                                echo "<td>" . $row['Invoice_Date'] . "</td>";
                                echo "<td>Rp " . number_format($row['Total_Amount'], 2, ',', '.') . "</td>";
                                echo "<td>" . $row['Payment_Status'] . "</td>";
                                echo "<td>
                                        <a href='admin_invoice.php?edit=" . $row['ID_Invoice'] . "' class='btn btn-sm btn-primary me-2'>Edit</a>
                                        <a href='delete_invoice.php?id=" . $row['ID_Invoice'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Delete</a>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>Tidak ada data invoice</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Edit Invoice -->
        <?php if (isset($_GET['edit'])): ?>
            <?php
            $id_invoice = $_GET['edit'];
            $query = "SELECT * FROM Invoices WHERE ID_Invoice = :id_invoice";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id_invoice', $id_invoice, PDO::PARAM_INT);
            $stmt->execute();
            $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>

            <?php if ($invoice): ?>
                <div class="mt-5">
                    <h3>Edit Invoice</h3>
                    <form method="POST" action="admin_invoice.php">
                        <input type="hidden" name="id_invoice" value="<?= $invoice['ID_Invoice']; ?>">
                        <div class="mb-3">
                            <label for="invoice_number" class="form-label">Nomor Invoice</label>
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="<?= htmlspecialchars($invoice['Invoice_Number']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" value="<?= htmlspecialchars($invoice['Total_Amount']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="Pending" <?= $invoice['Payment_Status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Paid" <?= $invoice['Payment_Status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                <option value="Cancelled" <?= $invoice['Payment_Status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="invoice.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            <?php else: ?>
                <p>Data invoice tidak ditemukan.</p>
            <?php endif; ?>
        <?php endif; ?>

        <?php
        // Proses update data jika form disubmit
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_invoice'])) {
            $id_invoice = $_POST['id_invoice'];
            $invoice_number = htmlspecialchars($_POST['invoice_number']);
            $total_amount = htmlspecialchars($_POST['total_amount']);
            $payment_status = htmlspecialchars($_POST['payment_status']);

            $update_query = "UPDATE Invoices 
                            SET Invoice_Number = :invoice_number, 
                                Total_Amount = :total_amount, 
                                Payment_Status = :payment_status 
                            WHERE ID_Invoice = :id_invoice";
            $stmt = $pdo->prepare($update_query);
            $stmt->bindParam(':invoice_number', $invoice_number);
            $stmt->bindParam(':total_amount', $total_amount);
            $stmt->bindParam(':payment_status', $payment_status);
            $stmt->bindParam(':id_invoice', $id_invoice, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: invoice.php?message=Data berhasil diupdate");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Terjadi kesalahan saat mengupdate data.</div>";
            }
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
