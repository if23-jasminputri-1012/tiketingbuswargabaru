// JS PEMESANAN TIKET

document.getElementById("metodePembayaran").addEventListener("change", function () {
  var metodePembayaran = this.value;
  var paymentDetails = document.getElementById("paymentDetails");
  var creditCardDetails = document.getElementById("creditCardDetails");
  var bankTransferDetails = document.getElementById("bankTransferDetails");
  var eWalletDetails = document.getElementById("eWalletDetails");
  var orderButton = document.getElementById("orderButton");

  // Menampilkan div paymentDetails
  paymentDetails.style.display = "block";

  // Menyembunyikan semua detail pembayaran
  creditCardDetails.style.display = "none";
  bankTransferDetails.style.display = "none";
  eWalletDetails.style.display = "none";
  orderButton.style.display = "none";

  // Menampilkan detail sesuai pilihan
  if (metodePembayaran === "credit_card") {
    creditCardDetails.style.display = "block";
  } else if (metodePembayaran === "bank_transfer") {
    bankTransferDetails.style.display = "block";
  } else if (metodePembayaran === "e_wallet") {
    eWalletDetails.style.display = "block";
  }

  // Menampilkan tombol pesan sekarang jika ada metode yang dipilih
  if (metodePembayaran) {
    orderButton.style.display = "block";
  }
});
