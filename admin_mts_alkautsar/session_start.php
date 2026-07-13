<?php
session_start();

if(!isset($_SESSION['username'])){
    header("location:login.php");
    exit();
}

include "config.php";

/* hitung jumlah siswa */
$query = mysqli_query($conn,"SELECT COUNT(*) as total FROM siswa");
$data = mysqli_fetch_assoc($query);
$total_siswa = $data['total'];
?>

