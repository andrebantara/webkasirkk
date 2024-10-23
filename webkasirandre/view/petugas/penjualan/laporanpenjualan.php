<?php 
include '../../../server/config.php';
session_start();

// Memeriksa apakah form telah disubmit
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : null;
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;

// Query untuk mengambil data penjualan
$query = "
    SELECT d.DetailID, d.PenjualanID, d.ProdukID, d.JumlahProduk, p.NamaProduk, 
           pen.TanggalPenjualan, pen.TotalHarga, pl.NamaPelanggan
    FROM detailpenjualan d
    JOIN produk p ON d.ProdukID = p.ProdukID
    JOIN penjualan pen ON d.PenjualanID = pen.PenjualanID
    JOIN pelanggan pl ON pen.PelangganID = pl.PelangganID
";

// Menambahkan kondisi jika tanggal mulai dan akhir diinputkan
if ($startDate && $endDate) {
    $query .= " WHERE pen.TanggalPenjualan BETWEEN '$startDate' AND '$endDate'";
}

$query .= " ORDER BY pen.PenjualanID DESC";

$row = query($query);

// Hitung total pendapatan dalam rentang waktu yang ditentukan
$totalPendapatanQuery = "
    SELECT SUM(pen.TotalHarga) AS TotalPendapatan
    FROM penjualan pen
    WHERE pen.TanggalPenjualan BETWEEN '$startDate' AND '$endDate'
";

$totalPendapatanResult = query($totalPendapatanQuery);
$totalPendapatan = $totalPendapatanResult ? $totalPendapatanResult[0]['TotalPendapatan'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama || Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../image/logo.png" type="image/png">
</head>

<body>
    <div class="flex">
        <!-- Navbar -->
        <?php require 'navbar.php' ?>

        <!-- Main container -->
        <div id="content" class="flex-1 p-8 ml-64">
            <h1 class="text-center text-green-700 text-4xl uppercase font-extrabold mb-4">Laporan Penjualan</h1>
            
            <!-- Form filter tanggal -->
            <form method="POST" class="mb-4">
                <div class="flex justify-center">
                    <input type="date" name="start_date" value="<?php echo $startDate; ?>" class="mr-2 p-2 border border-gray-300 rounded" required>
                    <input type="date" name="end_date" value="<?php echo $endDate; ?>" class="mr-2 p-2 border border-gray-300 rounded" required>
                    <button type="submit" class="bg-green-500 text-white p-2 rounded">Filter</button>
                    <button type="button" onclick="clearFilter()" class="ml-2 bg-red-500 text-white p-2 rounded">Clear Filter</button>
                </div>
            </form>

            <!-- Menampilkan total pendapatan -->
            <div class="text-center text-xl font-bold mb-4">
                Total Pendapatan: <?php echo number_format($totalPendapatan, 2); ?>
            </div>

            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                        <th class="py-3 px-6 text-left">ID Penjualan</th>
                        <th class="py-3 px-6 text-left">Nama Produk</th>
                        <th class="py-3 px-6 text-left">Jumlah Produk</th>
                        <th class="py-3 px-6 text-left">Tanggal Penjualan</th>
                        <th class="py-3 px-6 text-left">Total Harga</th>
                        <th class="py-3 px-6 text-left">Nama Pelanggan</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php foreach ($row as $data) :?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left"><?php echo $data['PenjualanID'];?></td>
                            <td class="py-3 px-6 text-left"><?php echo $data['NamaProduk'];?></td>
                            <td class="py-3 px-6 text-left"><?php echo $data['JumlahProduk'];?></td>
                            <td class="py-3 px-6 text-left"><?php echo $data['TanggalPenjualan'];?></td>
                            <td class="py-3 px-6 text-left"><?php echo $data['TotalHarga'];?></td>
                            <td class="py-3 px-6 text-left"><?php echo $data['NamaPelanggan'];?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function clearFilter() {
            // Menghapus nilai dari input tanggal dan refresh halaman
            document.querySelector('input[name="start_date"]').value = '';
            document.querySelector('input[name="end_date"]').value = '';
            // Mengirim form untuk refresh halaman
            document.querySelector('form').submit();
        }
    </script>
</body>
</html>
