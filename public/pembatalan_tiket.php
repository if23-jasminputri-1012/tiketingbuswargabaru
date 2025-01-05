<?php
require '../config/db.php'; // Menyertakan file koneksi

// Ambil ID pengguna yang sudah login atau yang disimpan dalam session
$userId = 1; // Gantilah dengan ID pengguna yang sesuai

// Mengambil data tiket yang dipesan oleh pengguna dari database
$sql = "SELECT * FROM tickets WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$tiket = $stmt->fetchAll(); 

// Menangani pembatalan tiket
if (isset($_GET['cancel_id'])) {
    $cancelId = $_GET['cancel_id'];

    // Menghapus tiket yang dibatalkan dari database
    $deleteSql = "DELETE FROM tickets WHERE id = :id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindParam(':id', $cancelId);
    if ($deleteStmt->execute()) {
        echo "Pesanan tiket berhasil dibatalkan.";
        header("Location: tiket_saya.php"); // Redirect setelah membatalkan
        exit();
    } else {
        echo "Gagal membatalkan tiket.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4 pt-5">Tiket Saya</h2>

        <?php if (count($tiket) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Kota Asal</th>
                    <th>Kota Tujuan</th>
                    <th>Tanggal Keberangkatan</th>
                    <th>Jumlah Penumpang</th>
                    <th>Metode Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tiket as $index => $ticket): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $ticket['nama']; ?></td>
                    <td><?php echo $ticket['kota_Asal']; ?></td>
                    <td><?php echo $ticket['kota_Tujuan']; ?></td>
                    <td><?php echo $ticket['tanggal_Keberangkatan']; ?></td>
                    <td><?php echo $ticket['jumlah_Penumpang']; ?></td>
                    <td><?php echo $ticket['metode_Pembayaran']; ?></td>
                    <td>
                        <!-- Tombol untuk membatalkan tiket -->
                        <a href="tiket_saya.php?cancel_id=<?php echo $ticket['id']; ?>" class="btn btn-danger"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan tiket ini?');">
                            Batalkan
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Anda belum memesan tiket.</p>
        <?php endif; ?>
    </div>

</body>

</html>