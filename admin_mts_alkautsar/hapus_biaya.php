<?php
include "config.php";

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM biaya WHERE Id='$id'");

header("Location:pembayaran.php");
?>