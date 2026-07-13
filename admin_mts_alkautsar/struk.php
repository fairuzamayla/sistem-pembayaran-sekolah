<?php
$conn = mysqli_connect("localhost","root","","project_kp");

$id = $_GET['id'];

$data = mysqli_query($conn,"
SELECT 
    transaksi.*,
    siswa.Nama,
    siswa.Kelas,
    biaya.nama_biaya

FROM transaksi

JOIN siswa 
ON transaksi.id_siswa = siswa.Id

JOIN biaya
ON transaksi.id_biaya = biaya.Id

WHERE transaksi.id='$id'
");

$d = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>
<head>
<title>Struk Pembayaran</title>

<style>
body{
    font-family:Arial;
    background:#f4f4f4;
}

.struk{
    width:400px;
    margin:30px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

.line{
    border-top:1px dashed black;
    margin:15px 0;
}

table{
    width:100%;
}

td{
    padding:6px 0;
}

.total{
    font-size:18px;
    font-weight:bold;
}

.btn{
    display:block;
    width:100%;
    margin-top:20px;
    padding:12px;

    background:#008000;
    color:white;

    border:none;
    border-radius:8px;

    cursor:pointer;
    font-size:16px;
}

@media print{

    .btn{
        display:none;
    }

    body{
        background:white;
    }

    .struk{
        box-shadow:none;
    }
}
</style>
</head>

<body>

<div class="struk">

<h2>🧾 STRUK PEMBAYARAN</h2>

<div class="line"></div>

<table>

<tr>
    <td>Kode</td>
    <td>: <?= $d['kode_transaksi']; ?></td>
</tr>

<tr>
    <td>Tanggal</td>
    <td>: <?= $d['tanggal']; ?></td>
</tr>

<tr>
    <td>Nama</td>
    <td>: <?= $d['Nama']; ?></td>
</tr>

<tr>
    <td>Kelas</td>
    <td>: <?= $d['Kelas']; ?></td>
</tr>

<tr>
    <td>Pembayaran</td>
    <td>: <?= $d['nama_biaya']; ?></td>
</tr>

</table>

<div class="line"></div>

<table>

<tr class="total">
    <td>Total Bayar</td>
    <td align="right">
        Rp <?= number_format($d['jumlah']); ?>
    </td>
</tr>

</table>

<div class="line"></div>

<p align="center">
Terima kasih telah melakukan pembayaran
</p>

<button onclick="window.print()" class="btn">
🖨 Cetak Struk
</button>

</div>

<script>
window.onload = function(){
    window.print();
}
</script>

</body>
</html>