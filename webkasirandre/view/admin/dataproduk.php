<?php 
session_start();

include '../../server/config.php';

$query = query("SELECT * FROM Produk ORDER BY ProdukID desc");

// Jika ada tombol cari ditekan
// Jika ada tombol cari ditekan
if (isset($_POST["cari"])) {
    $keyword = $_POST["keyword"];
    $query = "SELECT * FROM produk 
              WHERE NamaProduk LIKE '$keyword%' 
              ORDER BY ProdukID DESC";
}

// Jika ada tombol sort ditekan
if (isset($_POST['sort'])) {
    $sort = $_POST['sort'];

    // Ubah query berdasarkan pilihan sortir
    if ($sort == 'stok_asc') {
        $query = "SELECT * FROM produk ORDER BY Stok ASC";
    } elseif ($sort == 'stok_desc') {
        $query = "SELECT * FROM produk ORDER BY Stok DESC";
    } elseif ($sort == 'harga_asc') {
        $query = "SELECT * FROM produk ORDER BY Harga ASC";
    } elseif ($sort == 'harga_desc') {
        $query = "SELECT * FROM produk ORDER BY Harga DESC";
    }
}

// Eksekusi query
$row = query($query);

// Cek jika hasil pencarian kosong
if (isset($_POST["cari"]) && empty($row)) {
    $empty = true;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang || Admin</title>
    <!-- Tambahkan SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openModal(nama, harga, stok) {
            // Set data into modal
            document.getElementById('modalNama').textContent = nama;
            document.getElementById('modalHarga').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
            document.getElementById('modalStok').textContent = stok;

            // Show modal with smooth transition
            const modal = document.getElementById('myModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'scale-95');
                modal.classList.add('opacity-100', 'scale-100');
            }, 50); // Small delay to apply transition smoothly
        }

        function closeModal() {
            const modal = document.getElementById('myModal');
            modal.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('opacity-100', 'scale-100');
            }, 300); // Time to match the transition duration
        }

        function confirmDelete(produkID) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data ini tidak dapat dikembalikan setelah dihapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, arahkan ke URL penghapusan
                    window.location.href = "dataproduk/hapus.php?id=" + produkID;
                }
            });
        }
    </script>
</head>

<body>
    <div class="flex">
        <?php require 'navbar.php' ?>

        <div class="flex-1 p-8 ml-64">
            <!-- Tabel Barang -->
            <h1 class="text-4xl font-bold text-center text-blue-700 mb-10">Daftar Barang</h1>
        

            <!-- Search Data-->
            <form action="" method="post" class="mb-6">
                <div class="flex space-x-2">
                    <!-- Input Search Field -->
                    <input 
                        autofocus
                        autocomplete="off"
                        type="text" 
                        name="keyword" 
                        placeholder="Cari produk..." 
                        class="w-full bg-gray-100 p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                    >
                    <!-- Search Button -->
                    <button 
                        type="submit" 
                        name="cari"
                        class="w-1/5 bg-sky-500 text-white font-semibold rounded-lg p-3 hover:bg-sky-600 transition duration-200 ease-in-out"
                    >
                        Cari
                    </button>
                </div>
            </form>

            <div class="flex justify-between">

                
                <!-- Form Sorting -->
                <form action="" method="post">
                    <select id="sort" name="sort" onchange="this.form.submit()" class="w-1/2 bg-gray-100 border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="" disabled selected hidden>Sort</option> <!-- Placeholder -->
                        <option value="stok_asc">Stok (Terendah)</option>
                        <option value="stok_desc">Stok (Terbanyak)</option>
                        <option value="harga_asc">Harga (Rendah ke Tinggi)</option>
                        <option value="harga_desc">Harga (Tinggi ke Rendah)</option>
                    </select>
                </form>
                
                <a href="dataproduk/tambah.php" class="bg-sky-800 text-white font-bold font-sans py-2 px-2 rounded-sm">Tambah Barang</a>
            </div>

            <div class="overflow-x-auto mt-3">
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead class="bg-blue-700 text-white">
                        <tr>
                            <th class="p-4 text-left text-lg font-semibold">ID Barang</th>
                            <th class="p-4 text-left text-lg font-semibold">Nama Barang</th>
                            <th class="p-4 text-left text-lg font-semibold">Harga</th>
                            <th class="p-4 text-left text-lg font-semibold">Stok</th>
                            <th class="p-4 text-center text-lg font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Looping data dan tampilkan dalam baris tabel -->
                        <?php foreach ($query as $item): ?>
                            <tr class="border-b border-gray-200 hover:bg-blue-50">
                                <td class="p-4 text-gray-700"><?= $item['ProdukID']; ?></td>
                                <td class="p-4 text-gray-700"><?= $item['NamaProduk']; ?></td>
                                <td class="p-4 text-gray-700">Rp <?= number_format($item['Harga'], 0, ',', '.'); ?></td>
                                <td class="p-4 text-gray-700"><?= $item['Stok']; ?></td>
                                <td class="p-4 text-gray-700 flex justify-around">
                                    <a href="dataproduk/edit.php?id=<?= $item["ProdukID"] ?>" class="bg-yellow-300 px-2 py-1 rounded-sm hover:bg-yellow-400"><i class="fa-solid fa-pen"></i></a>
                                    <a href="javascript:void(0)" onclick="confirmDelete(<?= $item['ProdukID'] ?>)" class="bg-red-600 px-2 py-1 rounded-md hover:bg-red-800 text-gray-50">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                    <a href="javascript:void(0)" onclick="openModal('<?= $item['NamaProduk']; ?>', <?= $item['Harga']; ?>, <?= $item['Stok']; ?>)" class="bg-sky-500 px-2 py-1 rounded-sm hover:bg-sky-600"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>

                <!-- Cek apakah ada variabel empty -->
                <?php if(empty($row)): ?>
                    <h1 class="w-full text-center bg-slate-200 p-3">Data Tidak Ditemukan</h1>
                <?php endif; ?> 
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center transition-opacity duration-300 opacity-0 scale-95 transform">
        <div class="bg-white rounded-lg shadow-lg w-1/3 transition-transform duration-300 transform scale-95">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">Detail Produk</h2>
                <p><strong>Nama Barang:</strong> <span id="modalNama"></span></p>
                <p><strong>Harga:</strong> <span id="modalHarga"></span></p>
                <p><strong>Stok:</strong> <span id="modalStok"></span></p>
            </div>
            <div class="flex justify-end p-6">
                <button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Tutup</button>
            </div>
        </div>
    </div>
</body>

</html>
