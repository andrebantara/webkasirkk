<?php 
include '../../../server/config.php';
session_start();
 // get data from database


if($_GET["id"]){

    $id = $_GET["id"];

    // get data
    $data = "SELECT * FROM pengguna WHERE PenggunaID = '$id' ";
    $getData = mysqli_query($conn, $data);
    $row = mysqli_fetch_assoc($getData);


    if(isset($_POST["edit-btn"])){
        $nama = mysqli_real_escape_string($conn,$_POST["nama"]);
        $username = mysqli_real_escape_string($conn,$_POST["username"]);
        $role = "petugas"; // Ambil role dari input selection

        
        // hash password
        
       




    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit || Admin</title>
</head>
<body>
    <div class="flex">
        <?php require 'navbar.php' ?>

        <!-- Main container -->
        <div id="content" class="flex-1 p-8 ml-64">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4 text-gray-800">Edit Petugas </h1>

                <form action="" method="POST">
                    <div class="mb-4">
                        <label for="nama" class="block text-gray-700 font-semibold mb-2">Nama Pengguna</label>
                        <input type="text" id="nama" name="nama" value="<?= $row["NamaPengguna"] ?>" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring focus:ring-blue-300" placeholder="Masukkan Nama Pengguna">
                    </div>
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
                        <input type="text" id="username" name="username" value="<?= $row["Username"] ?>" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring focus:ring-blue-300" placeholder="Masukkan Username">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                        <input type="text" id="password" name="password"  class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring focus:ring-blue-300" placeholder="Buat Password Baru">
                    </div>
                    <div class="flex justify-end">
                        <button name="edit-btn" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                            Edit Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>
</html>