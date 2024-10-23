<?php 
include '../../../server/config.php';
if (isset($_GET["id"])){
    $id = $_GET["id"];
    $query = "DELETE FROM Produk WHERE ProdukID = $id";

    mysqli_query($conn, $query);
    header("Location: ../dataproduk.php?");
}
    

?>