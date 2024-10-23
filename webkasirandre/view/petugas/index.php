<?php
session_start();
include '../../server/config.php';


// Cek apakah user sudah login
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // Jika belum login, arahkan ke halaman login
    header("Location: ../../auth/login.php");
    exit; // Penting untuk menghentikan eksekusi setelah redirect
}


// menghitung jumlah baris dalam table mysql 
$stmt = "SELECT COUNT(*) AS jumlah_baris FROM Produk";
$query = mysqli_query($conn,$stmt);
$row = mysqli_fetch_assoc($query);

// Count row of table 
$stmt2 = "SELECT COUNT(*) AS jumlah_penjualan FROM penjualan ";
$query2 = mysqli_query($conn,$stmt2);
$row2 = mysqli_fetch_assoc($query2);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama || Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" href="../image/DALL·E 2024-10-09 11.51.38 - A simple logo for a cashier service. The design features a minimalist cash register with a small display and keypad. The cash register is stylized wit.webp" type="image/webp">
</head>

<body>
    <div class="flex">
        <!-- Navbar -->
        <?php require '../../asset/navbar.php' ?>

        <!-- Main container -->
        <div id="content" class="flex-1 p-8 ml-64">
             
        
            <div class="mb-4">
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-500 via-teal-500 to-blue-600 drop-shadow-lg">
                    Selamat Datang, <?= $_SESSION["namapengguna"] ?>!
                </h1>
                <p class="text-lg mt-2 text-gray-700 font-light italic">
                    Senang bertemu Anda kembali. Siap melayani pelanggan hari ini?
                </p>
            </div>


            <!-- Card data -->
            <div class="flex flex-wrap justify-between space-x-4">
    <!-- Data Barang -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-6 flex-1 basis-1/4 rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:shadow-xl">
        <div class="flex flex-col items-start gap-4 text-white">
            <div class="flex items-center justify-between w-full">
                <div>
                    <h1 class="text-5xl font-bold"><?= $row["jumlah_baris"] ?></h1>
                    <h2 class="text-2xl font-semibold">Data Barang</h2>
                </div>
                <div>
                    <i class="fa-solid fa-database text-8xl"></i>
                </div>
            </div>
            <!-- Tombol Lihat Selengkapnya -->
            <a href="barang/databarang.php" class="bg-white text-blue-700 px-4 py-2 rounded hover:bg-gray-100 transition duration-200">
                Lihat Selengkapnya
            </a>
        </div>
    </div>

    <!-- Transaksi di Keranjang -->
    <div class="bg-gradient-to-r from-green-500 to-green-700 p-6 flex-1 basis-1/4 rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:shadow-xl">
        <div class="flex flex-col items-start gap-4 text-white">
            <div class="flex items-center justify-between w-full">
                <div>
                    <!-- <h1 class="text-5xl font-bold">10</h1> -->
                    <h2 class="text-2xl font-semibold">Transaksi di Keranjang</h2>
                </div>
                <div>
                    <i class="fa-solid fa-shopping-cart text-8xl"></i>
                </div>
            </div>
            <!-- Tombol Arahkan ke Keranjang -->
            <a href="keranjang.php" class="bg-white text-green-700 px-4 py-2 rounded hover:bg-gray-100 transition duration-200 w-full text-center font-semibold">
                Arahkan ke Keranjang
            </a>
        </div>
    </div>

    <!-- Data Penjualan -->
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-700 p-6 flex-1 basis-1/4 rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:shadow-xl">
        <div class="flex flex-col items-start gap-4 text-white">
            <div class="flex items-center justify-between w-full">
                <div>
                    <h1 class="text-5xl font-bold"><?= $row2["jumlah_penjualan"] ?></h1>
                    <h2 class="text-2xl font-semibold">Data Penjualan</h2>
                </div>
                <div>
                    <i class="fa-solid fa-money-bill text-8xl"></i>
                </div>
            </div>
            <!-- Tombol Lihat Selengkapnya -->
            <a href="penjualan/laporanpenjualan.php" class="bg-white text-yellow-700 px-4 py-2 rounded hover:bg-gray-100 transition duration-200">
                Lihat Selengkapnya
            </a>
        </div>
    </div>
</div>


            </div>
</body>
<script>

</script>

</html>