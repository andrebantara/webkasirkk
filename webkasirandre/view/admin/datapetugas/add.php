<?php 
session_start();
include '../../../server/config.php';

$notif = "";

if(isset($_POST["add-btn"])){
    $nama = mysqli_real_escape_string($conn,$_POST["nama"]);
    $username = mysqli_real_escape_string($conn,$_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $role = "petugas";

    // Cek apakah username sudah ada atau belum 
    $result = mysqli_query($conn, "SELECT * FROM pengguna WHERE Username = '$username'");
    if(mysqli_fetch_assoc($result)){
        $notif = '<div class="bg-red-300 border p-2 border-red-600 w-full font-sans font-semibold">
                    Username Sudah Ada! 
                  </div>';
    }else{
        $query = "INSERT INTO pengguna (NamaPengguna, Username, Password, Role) VALUES ('$nama', '$username', '$password', '$role')";
        mysqli_query($conn, $query);
        $notif = '<div class="bg-green-300 border p-2 border-green-600 w-full font-sans font-semibold">
                    Petugas Berhasil Ditambahkan! 
                  </div>';
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
    <title>ADD || Admin</title>
</head>
<body>
    <div class="flex">
        <?php require 'navbar.php' ?>

        <!-- Main container -->
        <div id="content" class="flex-1 p-8 ml-64">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4 text-gray-800">Tambah Petugas Baru</h1>

          <!-- Display Notif -->
                    <?php 
                        echo $notif;

                    ?>
                <form action="" method="POST">
                    <div class="mb-4">
                        <label for="nama" class="block text-gray-700 font-semibold mb-2">Nama Pengguna</label>
                        <input type="text" id="nama" name="nama" required class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring focus:ring-blue-300" placeholder="Masukkan Nama Pengguna">
                    </div>
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
                        <input type="text" id="username" name="username" required class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring focus:ring-blue-300" placeholder="Masukkan Username">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                        <input type="password" id="password" name="password" required class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring focus:ring-blue-300" placeholder="Masukkan Password">
                    </div>
                    <div class="flex justify-end">
                        <button name="add-btn" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                            Tambah Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>
</html>