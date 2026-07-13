<?php
session_start();
include "config.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan</title>

<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    margin:0;
    background:		#FFF8DC
}

.container{
    padding:30px;
}

.card{
    background:white;
    padding:20px;
    margin-bottom:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
.top-btn{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.top-btn{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
}

/* TOMBOL */
.btn{
    padding:10px 15px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    transition:0.3s;
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

h2{
    margin-bottom:15px;
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
    color:black;
    color-interpolation-filters: auto;
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
    background:	#FFFFE0;

}
/* DARK MODE */
.dark{
    background:#0f172a;
    color:white;
}

.dark .container,
.dark .content{
    background:#0f172a;
}

.dark .card,
.dark .card-box,
.dark .grafik-box,
.dark .box-transaksi,
.dark .welcome-banner{
    background:#1e293b;
    color:white;
}

.dark table{
    color:white;
}

.dark th{
    background:#334155;
}

.dark td{
    border-color:#334155;
}

.dark tr:nth-child(even){
    background:#1e293b;
}

.dark input,
.dark select{
    background:#1e293b;
    color:white;
    border:1px solid #475569;
}

.dark .sidebar{
    background:#111827;
}

.dark .sidebar a:hover{
  
    transform:translateY(-5px);
    background:#A9A9A9;
    padding-left:20px;
    border-left:4px solid black;
}  

.dark .header{
    background: #1e293b;
}

.dark .btn{
    padding:10px 15px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    transition:0.3s;
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
</style>
</head>

<body onload="window.print()">
<div class="container">    
<div class="card">
<h2>📊 Laporan Pembayaran </h2>
<div class="container">
    <!-- TOMBOL -->
    <div class="top-btn">
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

    <!-- CARD TABEL -->
     <!-- 1. PEMBAYARAN PER KELAS -->
<!-- ======================== -->
   
<div class="card">
<h2>Pembayaran per Kelas</h2>
  
            
<table>
<tr>
    <th>Kelas</th>
    <th>Total</th>
</tr>

<?php
$data = mysqli_query($conn,"
    SELECT siswa.Kelas, SUM(transaksi.jumlah) as total
    FROM transaksi
    JOIN siswa ON transaksi.id_siswa = siswa.Id
    GROUP BY siswa.Kelas
") or die(mysqli_error($conn));

while($row = mysqli_fetch_assoc($data)){
?>
<tr>
    <td><?= $row['Kelas'] ?></td>
    <td>Rp <?= number_format($row['total']) ?></td>
</tr>
<?php } ?>

</table>

    </div>

<!-- ======================== -->
<!-- 2. PER BULAN -->
<!-- ======================== -->
<div class="card">
<h2>Total per Bulan</h2>

<table>
<tr>
    <th>Bulan</th>
    <th>Total</th>
</tr>

<?php
$data = mysqli_query($conn,"
    SELECT MONTH(tanggal) as bulan, SUM(jumlah) as total 
    FROM transaksi 
    GROUP BY MONTH(tanggal)
");

while($row = mysqli_fetch_assoc($data)){
?>
<tr>
    <td><?= $row['bulan'] ?></td>
    <td>Rp <?= number_format($row['total']) ?></td>
</tr>
<?php } ?>

</table>
</div>


<!-- ======================== -->
<!-- 3. PER TAHUN -->
<!-- ======================== -->
<div class="card">
<h2>Total per Tahun</h2>

<table>
<tr>
    <th>Tahun</th>
    <th>Total</th>
</tr>

<?php
$data = mysqli_query($conn,"
    SELECT YEAR(tanggal) as tahun, SUM(jumlah) as total 
    FROM transaksi 
    GROUP BY YEAR(tanggal)
");

while($row = mysqli_fetch_assoc($data)){
?>
<tr>
    <td><?= $row['tahun'] ?></td>
    <td>Rp <?= number_format($row['total']) ?></td>
</tr>
<?php } ?>

</table>
</div>

</div>


<script>
function darkMode(){

    document.body.classList.toggle("dark");

    // simpan mode
    if(document.body.classList.contains("dark")){
        localStorage.setItem("theme","dark");
    }else{
        localStorage.setItem("theme","light");
    }
}

// otomatis aktif saat halaman dibuka
window.onload = function(){

    if(localStorage.getItem("theme") === "dark"){
        document.body.classList.add("dark");
    }

}
</script>


</body>
</html>