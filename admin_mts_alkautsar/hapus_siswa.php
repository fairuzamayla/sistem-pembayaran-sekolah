<?php
$conn = mysqli_connect("localhost","root","","project_kp");

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM siswa WHERE Id='$id'");

header("location:data_siswa.php");
?>