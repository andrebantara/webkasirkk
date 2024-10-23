<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'kasir';

$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


// Query function 
function query($query)
{
    global $conn;
    $rows = [];  // Array untuk menyimpan semua hasil
    $myquery = mysqli_query($conn, $query);

    // Loop untuk memasukkan setiap hasil query ke dalam array
    while ($result = mysqli_fetch_assoc($myquery)) {
        $rows[] = $result;  // Menambahkan hasil ke array
    }

    return $rows;  // Mengembalikan array yang berisi semua hasil
}


// ADD Function 
function AddProduct($data)
{
    global $conn;

    $namabarang = htmlspecialchars($data["namabarang"]);
    $harga = htmlspecialchars($data["hargabarang"]);
    $stok = htmlspecialchars($data["stok"]);

    // Notification check
    $notif = "";

    // Cek apakah namabarang sudah ada di dalam database (tanpa memperhatikan huruf besar/kecil)
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE LOWER(NamaProduk) = LOWER('$namabarang')");
    if (mysqli_fetch_assoc($result)) {
        // Jika barang sudah ada
        $notif = '<div class="bg-red-300 border p-2 border-red-600 w-full font-sans font-semibold">
                    Barang Sudah Ada! 
                  </div>';
    } else {
        // Jika barang belum ada, tambahkan ke database
        $query = "INSERT INTO produk VALUES ('','$namabarang', '$harga', '$stok')";
        if (mysqli_query($conn, $query)) {
            // Jika berhasil
            $notif = '<div class="bg-green-300 border p-2 border-green-600 w-full font-sans font-semibold">
                        Barang Berhasil Ditambahkan! <a href="databarang.php" class="text-blue-500 hover:underline">Lihat</a>
                      </div>';
        } else {
            // Jika gagal
            $notif = '<div class="bg-red-300 border p-2 border-red-600 w-full font-sans font-semibold">
                        Gagal Menambahkan Barang!
                      </div>';
        }
    }

    return $notif;
}
function AddProduct2($data)
{
    global $conn;   
    $idBarang = htmlspecialchars($data["IDBarang"]);

    $namabarang = htmlspecialchars($data["namabarang"]);
    $harga = htmlspecialchars($data["hargabarang"]);
    $stok = htmlspecialchars($data["stok"]);

    // Notification check
    $notif = "";

    // Cek apakah namabarang sudah ada di dalam database (tanpa memperhatikan huruf besar/kecil)
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE ProdukID = '$idBarang'");
    if (mysqli_fetch_assoc($result)) {
        // Jika barang sudah ada
        $notif = '<div class="bg-red-300 border p-2 border-red-600 w-full font-sans font-semibold">
                    ID Produk Sudah Ada! 
                  </div>';
    } else {
        // Jika barang belum ada, tambahkan ke database
        $query = "INSERT INTO produk VALUES ('$idBarang','$namabarang', '$harga', '$stok')";
        if (mysqli_query($conn, $query)) {
            // Jika berhasil
            $notif = '<div class="bg-green-300 border p-2 border-green-600 w-full font-sans font-semibold">
                        Barang Berhasil Ditambahkan! <a href="../dataproduk.php" class="text-blue-500 hover:underline">Lihat</a>
                      </div>';
        } else {
            // Jika gagal
            $notif = '<div class="bg-red-300 border p-2 border-red-600 w-full font-sans font-semibold">
                        Gagal Menambahkan Barang!
                      </div>';
        }
    }

    return $notif;
}

function deleteProduk($id)
{

    global $conn;
    $query = "DELETE FROM Produk WHERE ProdukID = $id";

    mysqli_query($conn, $query);
    header('location: databarang.php');
}

function updateProduk($data, $id){

    global $conn;
    $namabarang = htmlspecialchars($data["namabarang"]);
    $harga = htmlspecialchars($data["hargabarang"]);
    $stok = htmlspecialchars($data["stok"]);
    $notif = "";

    $query = "UPDATE PRODUK SET NamaProduk = '$namabarang', Harga = '$harga', Stok = '$stok' WHERE ProdukID = '$id'";
    $execute = mysqli_query($conn, $query);

    if ($execute) {
        # code...
        $notif = '<div class="bg-green-300 border p-2 border-green-600 w-full font-sans font-semibold">
                        Barang Berhasil Diupdate! <a href="databarang.php" class="text-blue-500 hover:underline">Lihat</a>
                      </div>';
        
        
    }else{
        # code...
        $notif = '<div class="bg-red-300 border p-2 border-red-600 w-full font-sans font-semibold">
                        Gagal Edit Barang!
                      </div>';
    }

    return $notif;

}
function updateProdukAdmin($data, $id){

    global $conn;
    $namabarang = htmlspecialchars($data["namabarang"]);
    $harga = htmlspecialchars($data["hargabarang"]);
    $stok = htmlspecialchars($data["stok"]);
    $notif = "";

    $query = "UPDATE PRODUK SET NamaProduk = '$namabarang', Harga = '$harga', Stok = '$stok' WHERE ProdukID = '$id'";
    $execute = mysqli_query($conn, $query);

    if ($execute) {
        # code...
        $notif = '<div class="bg-green-300 border p-2 border-green-600 w-full font-sans font-semibold">
                        Barang Berhasil Diupdate! <a href="../dataproduk.php" class="text-blue-500 hover:underline">Lihat</a>
                      </div>';
        
        
    }else{
        # code...
        $notif = '<div class="bg-red-300 border p-2 border-red-600 w-full font-sans font-semibold">
                        Gagal Edit Barang!
                      </div>';
    }

    return $notif;

}

function searchData($query, $keywords){
    global $conn;
    $result = query($query);

    return $result;




}
