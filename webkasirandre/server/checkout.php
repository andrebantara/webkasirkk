<?php
session_start();
include 'config.php'; // Pastikan Anda memiliki koneksi database yang benar di sini

// Pastikan keranjang tidak kosong
if (empty($_SESSION['cart'])) {
    $_SESSION['notification'] = "Keranjang Anda kosong!";
    header('Location: ../view/petugas/keranjang.php');
    exit();
}

// Mengambil data dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data pelanggan dari form
    $customer_name = $_POST['customer_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment = $_POST['payment'];

    // Hitung subtotal
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    // Validasi pembayaran
    if ($payment < $subtotal) {
        $_SESSION['notification'] = "Jumlah bayar tidak cukup.";
        header('Location: ../view/petugas/keranjang.php');
        exit();
    }

    // Cek apakah pelanggan sudah ada
    $query = $conn->prepare("SELECT PelangganID FROM pelanggan WHERE NomorTelepon = ?");
    $query->bind_param("s", $phone);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Jika pelanggan sudah ada, ambil ID
        $row = $result->fetch_assoc();
        $customerID = $row['PelangganID'];
    } else {
        // Jika pelanggan baru, masukkan ke database
        $query = $conn->prepare("INSERT INTO pelanggan (NamaPelanggan, Alamat, NomorTelepon) VALUES (?, ?, ?)");
        $query->bind_param("sss", $customer_name, $address, $phone);
        
        if (!$query->execute()) {
            $_SESSION['notification'] = "Terjadi kesalahan saat menyimpan data pelanggan.";
            header('Location: ../view/petugas/keranjang.php');
            exit();
        }
        
        // Ambil ID pelanggan yang baru ditambahkan
        $customerID = $conn->insert_id;
    }

    // Simpan data penjualan
    $query = $conn->prepare("INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) VALUES (NOW(), ?, ?)");
    $query->bind_param("di", $subtotal, $customerID);
    
    if ($query->execute()) {
        $penjualanID = $conn->insert_id; // ambil ID penjualan yang baru dibuat

        // Simpan detail penjualan
        foreach ($_SESSION['cart'] as $productID => $item) {
            $queryDetail = $conn->prepare("INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) VALUES (?, ?, ?, ?)");
            $subtotalItem = $item['price'] * $item['quantity']; // hitung subtotal item
            $queryDetail->bind_param("iiid", $penjualanID, $productID, $item['quantity'], $subtotalItem);
            $queryDetail->execute();

            // Update stok barang setelah transaksi
            $newStock = $item['quantity'];
            $queryStock = $conn->prepare("UPDATE produk SET Stok = Stok - ? WHERE ProdukID = ?");
            $queryStock->bind_param("ii", $newStock, $productID);
            $queryStock->execute();
        }

        // Kosongkan keranjang setelah berhasil checkout
        unset($_SESSION['cart']);
        $_SESSION['notification'] = "Transaksi berhasil! Terima kasih telah berbelanja.";
    } else {
        $_SESSION['notification'] = "Terjadi kesalahan saat melakukan transaksi.";
    }

    header('Location: ../view/petugas/keranjang.php');
    exit();
}
?>
