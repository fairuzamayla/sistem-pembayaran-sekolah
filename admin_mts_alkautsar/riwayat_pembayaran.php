<?php
session_start();
include "config.php";

if(!$conn){
    die("Koneksi gagal: ".mysqli_connect_error());
}

$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';

$where = "";

if($bulan != '' && $tahun != ''){
    $where = "WHERE MONTH(t.tanggal)='$bulan' AND YEAR(t.tanggal)='$tahun'";
}elseif($tahun != ''){
    $where = "WHERE YEAR(t.tanggal)='$tahun'";
}

$data = mysqli_query($conn,"
SELECT t.*, s.Nama, s.Kelas, b.nama_biaya
FROM transaksi t
JOIN siswa s ON t.id_siswa = s.Id
JOIN biaya b ON t.id_biaya = b.Id
$where
ORDER BY t.id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Riwayat Pembayaran</title>

<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    margin:0;
    background:		#FFF8DC
}

/* HEADER */
.header{
    background:	#008000;
    color:white;
    padding:18px;
    text-align:center;
    font-size:20px;
}

/* CONTAINER */
.container{
    padding:30px;
}

/* CARD */
.aksi-atas{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.btn-kembali{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:80px;
    height:35px;
    padding:0 20px;
    background:#DC143C;
    color:white;
    text-decoration:none;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
    box-sizing:border-box;
}

.btn-kembali:hover{
    background:#8B0000;
}



.btn-cetak{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:90px;
    height:35px;
    padding:0 20px;
    background: #2418d8;
    color:white;
    text-decoration:none;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
    box-sizing:border-box;
}

.btn-cetak:hover{
    background: #00008B;
}

.tampilkan{
    background: #6B8E23;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

.tampilkan:hover{
    background: #006400;
}
/* FILTER */
.filter{
    display:flex;
    gap:10px;
    margin-bottom:20px;
    align-items:center;
}

.filter select{
    padding:10px;
    border-radius:6px;
    border:1px solid #5c2525;
}





/* TABLE */
.card-table{
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-top:20px;
}

body.dark .card-table{
    background:#0C0637;
    color:white;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:	#008000;
    color:white;
    padding:12px;
}

td{
    padding:10px;
    text-align:center;
}

tr:nth-child(even){
    background: #f2f2f2;
}

tr:hover{
    background:	#556B2F;
     color:white;
}

/* BUTTON */
.btn{
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    color:white;
}



 
/* DARK MODE */
body.dark{
    background:	#070425;
    color:white;
}

.dark .header{
    background: #262e48;
    color:white;
    padding:18px;
    text-align:center;
    font-size:20px;
}
/* CARD */
body.dark .card{
    background: #0C0637;;
    color:white;
}

/* TABLE */
body.dark table{
    background:#1e1e1e;
    color:white;
}

body.dark th{
    background:	#262e48;
    color:white;
}

body.dark td{
    color:white;
}

/* WARNA BARIS */
body.dark tr:nth-child(even){
    background: #2a2a2a;
}

body.dark tr:nth-child(odd){
    background:	#262e48;;
}

/* HOVER TABEL */
body.dark tr:hover{
    background:	#A9A9A9 !important;
}

body.dark tr:hover td{
    color:white;
}

.dark .btn{
    padding:8px 12px;
    border-radius:6px;
    text-decoration:none;
    color:white;
}

.dark .btn-kembali{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:80px;
    height:35px;
    padding:0 20px;
    background: #7159b9;
    color:white;
    text-decoration:none;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
    box-sizing:border-box;
}

.dark .btn-kembali:hover{
    background: #352065;
}
.dark .btn-cetak{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:90px;
    height:35px;
    padding:0 20px;
    background: #8968b7;
    color:white;
    text-decoration:none;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
    box-sizing:border-box;
}

.dark .btn-cetak:hover{
    background: #3c1a8a;
}


.dark .filter select{
    padding:10px;
    border-radius:6px;
    border:1px solid #b8b3b3;
}

body.dark select{
    background: #262e48;
    color:white;
    border:1px solid #555;
}

.dark .tampilkan{
    background: #3f30ae;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}
.tampilkan:hover{
    background: #251f55;
}


</style>

</head>

<body>

<div class="header">
📊 Riwayat Pembayaran
</div>

<div class="container">
            
<div class="aksi-atas">
<?php
$role = strtolower(trim($_SESSION['role']));

if($role == 'kepala sekolah'){
    $dashboard = 'dashboard_kepala.php';
}else{
    $dashboard = 'dashboard.php';
}
?>

<a href="<?= $dashboard; ?>" class="btn-kembali">
    Kembali
</a>

   <a href="cetak_riwayat.php" target="_blank" class="btn-cetak">
    Cetak
</a>

</div>

<form method="GET" class="filter">

<select name="bulan">
    <option value="">Semua Bulan</option>
    <option value="1">Januari</option>
    <option value="2">Februari</option>
    <option value="3">Maret</option>
    <option value="4">April</option>
    <option value="5">Mei</option>
    <option value="6">Juni</option>
    <option value="7">Juli</option>
    <option value="8">Agustus</option>
    <option value="9">September</option>
    <option value="10">Oktober</option>
    <option value="11">November</option>
    <option value="12">Desember</option>
</select>

<select name="tahun">
<option value="">Semua Tahun</option>

<?php

$tahun = mysqli_query($conn,"
SELECT DISTINCT YEAR(tanggal) as tahun
FROM transaksi
ORDER BY tahun DESC
");

while($t = mysqli_fetch_assoc($tahun)){
?>

<option value="<?= $t['tahun']; ?>">
    <?= $t['tahun']; ?>
</option>

<?php } ?>

</select>
  

<button type="submit" class="tampilkan">
    Tampilkan
</button>



</form>

<div class="card-table">
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
    <td><?= $row['tanggal'] ?></td>
    <td><?= $row['Nama'] ?></td>
    <td><?= $row['Kelas'] ?></td>
    <td><?= $row['nama_biaya'] ?></td>
    <td>Rp <?= number_format($row['jumlah']) ?></td>


</tr>
<?php } ?>

</table>

</div>
</div>

<script>
function darkMode(){

    document.body.classList.toggle("dark");

    if(document.body.classList.contains("dark")){
        localStorage.setItem("theme","dark");
    }else{
        localStorage.setItem("theme","light");
    }
}

window.onload = function(){

    if(localStorage.getItem("theme") === "dark"){
        document.body.classList.add("dark");
    }

}
</script>

</body>
</html>