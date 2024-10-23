<?php 
session_start();
include '../../../server/config.php'; 


if(isset($_GET["id"])){
    $id = $_GET["id"];
    $delete = "DELETE FROM Pengguna WHERE PenggunaID = $id";
    mysqli_query($conn, $delete);

    // cek apakah berhasil di hapus
    if(mysqli_affected_rows($conn)){
       $_SESSION["deleteMsg"] = true;
       header("Location: ../datapetugas.php");
    }
}

?>