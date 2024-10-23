<?php 

include '../../server/config.php';
session_start();

// cek session logged_in 

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"]!== true) {
    header("location:../../auth/login.php");
    exit; // Penting untuk menghentikan eksekusi setelah redirect
}

// Hitung data
$sql = "SELECT COUNT(*) AS jumlah_baris FROM Pengguna WHERE role = 'petugas'";
$query = "SELECT COUNT(*) AS jumlah_produk FROM Produk";  
$result_produk = mysqli_query($conn, $query);
$getRow = mysqli_fetch_assoc($result_produk);

$result = mysqli_query($conn, $sql);

$data = mysqli_fetch_assoc($result);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Admin || Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- animation -->
    <style>
    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 5s ease infinite;
    }
</style>
</head>
<body>
<div class="flex">
        <!-- Navbar -->
        <?php require 'navbar.php' ?>

        <!-- Main container -->
        <div id="content" class="flex-1 p-8 ml-64">

            <!-- Greeting  -->
            <div class="relative">
                <h1 class="text-3xl font-extrabold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-green-500 animate-gradient">
                    Selamat Datang, Administrator!
                </h1>
                <!-- <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 bg-yellow-400 w-24 h-1 rounded-full"></div> -->
            </div>

            <!-- card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card Data Penjualan -->

    <!-- Card Data Produk -->
            <div class="bg-white border border-gray-300 p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Data Produk</h2>
                        <p class="text-gray-600">Total produk terdaftar</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class="fa-solid fa-box text-4xl text-green-500"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-2xl font-semibold text-gray-700"><?= $getRow["jumlah_produk"] ?> Produk</p>
                </div>
                <a href="dataproduk.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">
                    Lihat Data
                </a>
            </div>

            <!-- Card Data Petugas Kasir -->
            <div class="bg-white border border-gray-300 p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Data Petugas Kasir</h2>
                        <p class="text-gray-600">Total petugas </p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class="fa-solid fa-user text-4xl text-yellow-500"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-2xl font-semibold text-gray-700"><?= $data["jumlah_baris"] ?> Petugas</p>
                </div>
                <a href="datapetugas.php" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition duration-200">
                    Lihat Data
                </a>
            </div>
        </div>


        </div>
</div>

</body>
</html>