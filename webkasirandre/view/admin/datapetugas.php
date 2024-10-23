<?php 
include '../../server/config.php';
session_start();

$data = query("SELECT * FROM Pengguna WHERE role = 'petugas' ORDER BY PenggunaID DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Data Petugas</title>
    <style>
        .fade-out {
            opacity: 0;
            transition: all 1s ease-out;
        }
    </style>
</head>
<body>
<div class="flex">
        <!-- Navbar -->
        <?php require 'navbar.php' ?>

        <!-- Main container -->

        <div id="content" class="flex-1 p-8 ml-64">
        <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Petugas</h1>
        <a href="datapetugas/add.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
            Tambah Petugas Baru
        </a>
    </div>

    <?php if(isset($_SESSION["deleteMsg"])):?>
        <div id="notification" class="w-full bg-red-500 border border-red-700 p-3 rounded-sm mb-3 ">Petugas Berhasil Di Hapus</div>
        <?php unset($_SESSION["deleteMsg"])?>
    <?php endif;?>

    <!-- Tabel Petugas -->
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full bg-white duration-100">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-6 text-left text-gray-600 uppercase font-semibold">Nama Petugas</th>
                    <th class="py-3 px-6 text-left text-gray-600 uppercase font-semibold">Username</th>
                    <th class="py-3 px-6 text-center text-gray-600 uppercase font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">

            <?php foreach($data as $row):?>
                <tr class="border-b">
                    <td class="py-4 px-6"><?= $row["NamaPengguna"] ?></td>
                    <td class="py-4 px-6"><?= $row["Username"] ?></td>
                    <td class="py-4 px-6 text-center">
                        <a href="datapetugas/update.php?id=<?= $row["PenggunaID"] ?>" class="text-green-500 hover:text-green-600 font-semibold mr-2">
                            Update
                        </a>
                        <a href="datapetugas/delete.php?id=<?= $row["PenggunaID"] ?>" onclick="return(confirm('yakin Hapus'))" class="text-red-500 hover:text-red-600 font-semibold">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach;?>
                <!-- Tambahkan data petugas lain di sini -->
            </tbody>
        </table>
    </div>
        </div>
</div>
</body>
<script>
        // Fungsi untuk menyembunyikan notifikasi setelah 3 detik
        setTimeout(function() {
            var notification = document.getElementById('notification');
            if (notification) {
                notification.classList.add('fade-out'); // Tambahkan kelas fade-out
                setTimeout(function() {
                    notification.style.display = 'none'; // Sembunyikan elemen
                }, 1000); // Durasi transisi fade-out
            }
        }, 3000); // Durasi tampil notifikasi (3000 ms = 3 detik)
    </script>
</html>