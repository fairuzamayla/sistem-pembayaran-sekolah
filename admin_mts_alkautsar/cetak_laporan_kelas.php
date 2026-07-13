<?php

$conn = mysqli_connect("localhost","root","","project_kp");

/* FILTER */
$kelas = $_GET['kelas'] ?? '';
$id_biaya = $_GET['id_biaya'] ?? '';

$where = "WHERE 1=1";

if($kelas != ""){
    $where .= " AND siswa.Kelas='$kelas'";
}

/* QUERY */
$query = mysqli_query($conn,"
SELECT 
    siswa.Nama,
    siswa.Kelas,

    biaya.nama_biaya,
    biaya.jumlah as target,

    IFNULL(SUM(transaksi.jumlah),0) as total_bayar

FROM siswa

LEFT JOIN transaksi 
ON transaksi.id_siswa = siswa.Id
AND transaksi.id_biaya = '$id_biaya'

LEFT JOIN biaya 
ON biaya.Id = '$id_biaya'

$where

GROUP BY 
siswa.Id,
siswa.Nama,
siswa.Kelas,
biaya.nama_biaya,
biaya.jumlah

") or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html>
<head>

<title>Cetak Laporan Per Kelas</title>

<style>

body{
    font-family:Arial;
    padding:20px;
}

h2{
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th, td{
    border:1px solid black;
    padding:10px;
    text-align:center;
}

th{
    background:#008000;
    color:white;
}

.lunas{
    color:green;
    font-weight:bold;
}

.belum{
    color:red;
    font-weight:bold;
}

</style>

</head>

<body onload="window.print()">

<h2>Laporan Pembayaran Per Kelas</h2>

<hr>

<table>

<tr>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Total Bayar</th>
    <th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)){ 

$status = ($row['total_bayar'] >= $row['target']) 
? "Lunas" 
: "Belum";

?>

<tr>

<td><?= $row['Nama'] ?></td>

<td><?= $row['Kelas'] ?></td>

<td>
Rp <?= number_format($row['total_bayar']) ?>
</td>

<td class="<?= $status == 'Lunas' ? 'lunas' : 'belum' ?>">
    <?= $status ?>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>