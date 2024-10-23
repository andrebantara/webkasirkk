<?php
session_start();
include '../../server/config.php'; // Pastikan jalur ke config.php sudah benar

// Cek apakah ada item yang ingin dihapus
if (isset($_POST['remove_from_cart'])) {
    $productID = $_POST['product_id'];

    // Hapus item dari keranjang
    if (isset($_SESSION['cart'][$productID])) {
        // Ambil jumlah barang yang akan dihapus
        $quantityToRemove = $_SESSION['cart'][$productID]['quantity'];

        // Mengupdate stok di database
        $query = $conn->prepare("UPDATE produk SET Stok = Stok + ? WHERE ProdukID = ?");
        $query->bind_param("ii", $quantityToRemove, $productID);
        $query->execute();

        // Hapus item dari keranjang
        unset($_SESSION['cart'][$productID]);
        $_SESSION['notification'] = "Produk berhasil dihapus dari keranjang.";
    } else {
        $_SESSION['notification'] = "Produk tidak ditemukan di keranjang.";
    }
} else {
    $_SESSION['notification'] = "Tidak ada produk yang dipilih untuk dihapus.";
}

// Arahkan kembali ke halaman keranjang
header('Location: keranjang.php');
exit();
