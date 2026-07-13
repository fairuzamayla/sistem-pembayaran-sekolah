<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "project_kp";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>