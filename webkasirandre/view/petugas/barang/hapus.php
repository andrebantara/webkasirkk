<?php 
include '../../../server/config.php';
if (isset($_GET["id"])){
    $id = $_GET["id"];
    deleteProduk($id);
}
    

?>