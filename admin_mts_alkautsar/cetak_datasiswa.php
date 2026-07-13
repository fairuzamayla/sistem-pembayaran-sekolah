<?php
$conn = mysqli_connect("localhost","root","","project_kp");
$data = mysqli_query($conn,"SELECT * FROM siswa");
?>

<!DOCTYPE html>
<html>
<head>
<title>Cetak Data Siswa</title>
<style>
table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid black;
    padding:8px;
    text-align:center;
}
</style>
</head>

<body onload="window.print()">

<h2 align="center">Data Siswa</h2>

<table>
<tr>
    <th>NISN</th>
    <th>Nama</th>
    <th>Jenis Kelamin</th>
    <th>Tahun</th>
    <th>Kelas</th>
    <th>No HP</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><?= $row['Nisn'] ?></td>
    <td><?= $row['Nama'] ?></td>
    <td><?= $row['Jenis_Kelamin'] ?></td>
    <td><?= $row['Tahun_Masuk'] ?></td>
    <td><?= $row['Kelas'] ?></td>
    <td><?= $row['No_HP'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>