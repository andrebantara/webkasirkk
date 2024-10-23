<?php
session_start();
include '../../../server/config.php';

$notif = "";
if (isset($_GET["id"])){

    $id = $_GET["id"];
    $getValue = query("SELECT * FROM Produk WHERE ProdukID = $id ")[0];
    
    if (isset($_POST['submit'])) {
        # code...
        $notif = updateProduk($_POST, $id );
        $getValue = query("SELECT * FROM Produk WHERE ProdukID = $id ")[0];
    }
}
    



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang || Petugas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <div class="flex">
        <?php require '../../../asset/navbar2.php' ?>

        <div class="flex-1 p-8 ml-64">
            <div class="mt-5 bg-gray-50 p-4 border border-gray-300 rounded-sm shadow-sm ">
                <h1 class="text-center text-2xl font-bold  text-green-700 mb-4">Edit Barang</h1>
                <form action="" method="POST" class="space-y-2">
                    
                    <!-- Display Notif -->
                    <?php 
                        echo $notif;

                    ?>
                    



                    <div class="flex flex-col ">
                        <input
                            required
                            type="text"
                            name="namabarang"
                            value="<?= $getValue["NamaProduk"] ?>"
                            class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-slate-500 hover:border-slate-300 shadow-lg shadow-gray-100 ring-4 ring-transparent focus:ring-slate-100" />
                    </div>
                    <div class="flex flex-col">
                        <input
                            required
                            type="number"
                            name="hargabarang"
                            value="<?= $getValue["Harga"] ?>"
                            class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-slate-500 hover:border-slate-300 shadow-lg shadow-gray-100 ring-4 ring-transparent focus:ring-slate-100" />
                    </div>
                    <div class="flex flex-col">
                        <input
                            required
                            type="number"
                            name="stok"
                            value="<?= $getValue["Stok"] ?>"
                            class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-3 transition duration-300 ease focus:outline-none focus:border-slate-500 hover:border-slate-300 shadow-lg shadow-gray-100 ring-4 ring-transparent focus:ring-slate-100" />
                    </div>

                    <div>
                        <button type="submit" name="submit" class="w-full bg-green-500 py-2 rounded-sm hover:bg-green-700 font-bold text-white duration-75">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>