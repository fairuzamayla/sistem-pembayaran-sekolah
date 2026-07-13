<?php
$conn = mysqli_connect("localhost","root","","project_kp");

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM transaksi WHERE id='$id'");

echo "<script>alert('Data dihapus');window.location='pembayaran.php';</script>";