<?php
session_start();
if (!isset($_SESSION['snap_token'])) {
    header('Location: pemesanan_tiket.php');
    exit;
}

$snap_token = $_SESSION['snap_token'];
$order_id = $_SESSION['order_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar Tiket</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="YOUR_CLIENT_KEY"></script>
</head>
<body>
    <h1>Bayar Tiket</h1>
    <button id="pay-button">Bayar Sekarang</button>

    <script>
        document.getElementById('pay-button').onclick = function () {
            snap.pay('<?= $snap_token ?>', {
                onSuccess: function (result) {
                    console.log(result);
                    window.location.href = 'konfirmasi_pembayaran.php?order_id=<?= $order_id ?>';
                },
                onPending: function (result) {
                    console.log(result);
                },
                onError: function (result) {
                    console.log(result);
                }
            });
        };
    </script>
</body>
</html>
