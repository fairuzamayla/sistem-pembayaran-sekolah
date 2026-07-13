<?php

$conn = mysqli_connect("localhost","root","","project_kp");

/* =========================
   TOTAL PER KELAS
========================= */
$kelas = mysqli_query($conn,"
SELECT 
    siswa.Kelas,
    SUM(transaksi.jumlah) as total

FROM transaksi

JOIN siswa
ON transaksi.id_siswa = siswa.Id

GROUP BY siswa.Kelas
");

/* =========================
   TOTAL PER BULAN
========================= */
$bulan = mysqli_query($conn,"
SELECT 
    MONTH(tanggal) as bulan,
    SUM(jumlah) as total

FROM transaksi

GROUP BY MONTH(tanggal)
");

/* =========================
   TOTAL PER TAHUN
========================= */
$tahun = mysqli_query($conn,"
SELECT 
    YEAR(tanggal) as tahun,
    SUM(jumlah) as total

FROM transaksi

GROUP BY YEAR(tanggal)
");

?>

<!DOCTYPE html>
<html>
<head>

<title>Cetak Laporan</title>

<style>

body{
    font-family:Arial;
    padding:20px;
}

h2{
    text-align:center;
}

h3{
    margin-top:30px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:30px;
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

</style>

</head>

<body onload="window.print()">

<h2>Laporan Pembayaran</h2>

<hr>

<!-- =========================
     PEMBAYARAN PER KELAS
========================= -->

<h3>Pembayaran per Kelas</h3>

<table>

<tr>
    <th>Kelas</th>
    <th>Total</th>
</tr>

<?php while($k = mysqli_fetch_assoc($kelas)){ ?>

<tr>

    <td><?= $k['Kelas']; ?></td>

    <td>
        Rp <?= number_format($k['total']); ?>
    </td>

</tr>

<?php } ?>

</table>

<!-- =========================
     TOTAL PER BULAN
========================= -->

<h3>Total per Bulan</h3>

<table>

<tr>
    <th>Bulan</th>
    <th>Total</th>
</tr>

<?php while($b = mysqli_fetch_assoc($bulan)){ ?>

<tr>

    <td><?= $b['bulan']; ?></td>

    <td>
        Rp <?= number_format($b['total']); ?>
    </td>

</tr>

<?php } ?>

</table>

<!-- =========================
     TOTAL PER TAHUN
========================= -->

<h3>Total per Tahun</h3>

<table>

<tr>
    <th>Tahun</th>
    <th>Total</th>
</tr>

<?php while($t = mysqli_fetch_assoc($tahun)){ ?>

<tr>

    <td><?= $t['tahun']; ?></td>

    <td>
        Rp <?= number_format($t['total']); ?>
    </td>

</tr>

<?php } ?>

</table>

</body>
</html>