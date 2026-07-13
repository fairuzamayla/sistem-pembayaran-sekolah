<?php
$conn = mysqli_connect("localhost","root","","project_kp");

$data = mysqli_query($conn,"
SELECT t.*, s.Nama, s.Kelas, b.nama_biaya
FROM transaksi t
JOIN siswa s ON t.id_siswa = s.Id
JOIN biaya b ON t.id_biaya = b.Id
ORDER BY t.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Cetak Riwayat Pembayaran</title>

<style>
body{
    font-family:Arial;
}

h2{
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid black;
    padding:8px;
    text-align:center;
}

th{
    background:#ddd;
}
</style>

</head>

<body onload="window.print()">

<h2>Riwayat Pembayaran Siswa</h2>

<table>
<tr>
    <th>Tanggal</th>
    <th>Nama Siswa</th>
    <th>Kelas</th>
    <th>Biaya</th>
    <th>Jumlah</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)){ ?>

<tr>
    <td><?= $row['tanggal']; ?></td>
    <td><?= $row['Nama']; ?></td>
    <td><?= $row['Kelas']; ?></td>
    <td><?= $row['nama_biaya']; ?></td>
    <td>Rp <?= number_format($row['jumlah']); ?></td>
</tr>

<?php } ?>

</table>

</body>
</html>