<?php 
session_start();
include '../../server/config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart logic
if (isset($_POST['add_to_cart'])) {
    $productID = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Cek stok sebelum menambah ke keranjang
    $query = $conn->query("SELECT Stok FROM produk WHERE ProdukID = '$productID'");
    $row = $query->fetch_assoc();
    
    if ($row['Stok'] >= $quantity) {
        // Check if product already in cart, update quantity if so
        $cart_item = [
            'product_id' => $productID,
            'product_name' => $productName,
            'price' => $price,
            'quantity' => $quantity
        ];

        if (isset($_SESSION['cart'][$productID])) {
            $_SESSION['cart'][$productID]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productID] = $cart_item;
        }

        // Update stock in database
        $new_stock = $row['Stok'] - $quantity;
        $conn->query("UPDATE produk SET Stok = '$new_stock' WHERE ProdukID = '$productID'");

        $_SESSION['notification'] = "Produk '$productName' berhasil ditambahkan ke keranjang.";
    } else {
        $_SESSION['notification'] = "Stok untuk produk '$productName' tidak cukup.";
    }
}

// Handle updating quantity in cart
if (isset($_POST['update_cart'])) {
    $productID = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    
    // Update stock in database if quantity is changed
    $query = $conn->query("SELECT Stok FROM produk WHERE ProdukID = '$productID'");
    $row = $query->fetch_assoc();
    $current_stock = $row['Stok'];
    
    if ($new_quantity <= 0) {
        unset($_SESSION['cart'][$productID]);
    } else {
        $old_quantity = $_SESSION['cart'][$productID]['quantity'];
        
        // Update stock based on quantity change
        $quantity_difference = $new_quantity - $old_quantity;
        $new_stock = $current_stock - $quantity_difference;
        
        if ($new_stock >= 0) {
            $_SESSION['cart'][$productID]['quantity'] = $new_quantity;
            $conn->query("UPDATE produk SET Stok = '$new_stock' WHERE ProdukID = '$productID'");
        } else {
            $_SESSION['notification'] = "Stok untuk produk tidak cukup.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Utama || Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" href="../image/icon.webp" type="image/webp">
</head>

<body class="bg-gray-100">
    <div class="flex">
        <!-- Navbar -->
        <?php require '../../asset/navbar.php' ?>

        <!-- Main container -->
        <div id="content" class="flex-1 p-8 ml-64">
            <h1 class="text-2xl font-bold mb-4">Keranjang Kasir</h1>

            <!-- Menampilkan notifikasi jika ada -->
            <?php if (isset($_SESSION['notification'])): ?>
                <div class="bg-green-500 text-white p-4 rounded-lg mb-4 transition duration-300">
                    <strong>Berhasil!</strong> <?= $_SESSION['notification'] ?>
                </div>
                <?php unset($_SESSION['notification']); // Hapus notifikasi setelah ditampilkan ?>
            <?php endif; ?>

            <!-- Search Bar -->
            <form method="GET" action="">
                <div class="mb-4">
                    <input id="search" name="search" type="text" placeholder="Cari barang..." class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </form>

            <!-- Tabel Produk Ditemukan -->
            <?php if (isset($_GET['search']) && $_GET['search'] != ""): ?>
            <table id="productTable" class="w-full bg-white rounded-lg shadow-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-4 text-left">Nama Produk</th>
                        <th class="p-4 text-right">Harga</th>
                        <th class="p-4 text-center">Stok</th>
                        <th class="p-4 text-center">Jumlah</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = $_GET['search'];
                    $query = $conn->prepare("SELECT * FROM produk WHERE NamaProduk LIKE ?");
                    $likeSearch = "$search%";
                    $query->bind_param("s", $likeSearch);
                    $query->execute();
                    $result = $query->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-b'>";
                            echo "<td class='p-4'>" . $row['NamaProduk'] . "</td>";
                            echo "<td class='p-4 text-right'>Rp " . number_format($row['Harga'], 2) . "</td>";
                            echo "<td class='p-4 text-center'>" . $row['Stok'] . "</td>";
                            echo "<td class='p-4 text-center'>
                                    <form method='POST' action=''>
                                        <input type='hidden' name='product_id' value='" . $row['ProdukID'] . "'>
                                        <input type='hidden' name='product_name' value='" . $row['NamaProduk'] . "'>
                                        <input type='hidden' name='price' value='" . $row['Harga'] . "'>
                                        <input type='number' name='quantity' value='1' min='1' class='w-16 p-2 border'>
                                  </td>";
                            echo "<td class='p-4 text-center'>
                                        <button type='submit' name='add_to_cart' class='bg-blue-500 text-white px-4 py-2 rounded-lg'>Tambah</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='p-4 text-center'>Produk tidak ditemukan</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php endif; ?>

            <!-- Tabel Keranjang -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Keranjang Belanja</h2>
                <table id="cartTable" class="w-full bg-white rounded-lg shadow-lg">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-4 text-left">Nama Produk</th>
                            <th class="p-4 text-right">Harga</th>
                            <th class="p-4 text-center">Jumlah</th>
                            <th class="p-4 text-right">Total</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $subtotal = 0;
                        if (!empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $key => $item) {
                                $total = $item['price'] * $item['quantity'];
                                $subtotal += $total;
                                echo "<tr class='border-b'>";
                                echo "<td class='p-4'>" . $item['product_name'] . "</td>";
                                echo "<td class='p-4 text-right'>Rp " . number_format($item['price'], 2) . "</td>";
                                echo "<td class='p-4 text-center'>
                                        <form method='POST' action=''>
                                            <input type='hidden' name='product_id' value='" . $item['product_id'] . "'>
                                            <input type='number' name='quantity' value='" . $item['quantity'] . "' min='1' class='w-16 p-2 border'>
                                            <button type='submit' name='update_cart' class='bg-yellow-500 text-white px-2 py-1 ml-2 rounded-lg'>Update</button>
                                        </form>
                                      </td>";
                                echo "<td class='p-4 text-right'>Rp " . number_format($total, 2) . "</td>";
                                echo "<td class='p-4 text-center'>
                                        <form method='POST' action='remove.php'>
                                            <input type='hidden' name='product_id' value='" . $item['product_id'] . "'>
                                            <button type='submit' name='remove_from_cart' class='bg-red-500 text-white px-4 py-2 rounded-lg'>Hapus</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='p-4 text-center'>Keranjang kosong</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Subtotal -->
                <div class="mt-4 text-right">
                    <h3 class="text-xl font-bold">Subtotal: Rp <?= number_format($subtotal, 2) ?></h3>
                </div>
            </div>

            <!-- Form Data Pembeli -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Data Pembeli</h2>
                <form method="POST" action="../../server/checkout.php">
                    <div class="mb-4">
                        <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                        <input type="text" id="customer_name" name="customer_name" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <input type="text" id="address" name="address" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="payment" class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                        <input type="number" id="payment" name="payment" required min="<?= $subtotal ?>" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Selesaikan Transaksi</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Simple search functionality
        document.getElementById('search').addEventListener('keyup', function () {
            let searchValue = this.value.toLowerCase();
            let rows = document.querySelectorAll('#productTable tbody tr');

            rows.forEach(row => {
                let productName = row.querySelector('td:first-child').textContent.toLowerCase();
                if (productName.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>
