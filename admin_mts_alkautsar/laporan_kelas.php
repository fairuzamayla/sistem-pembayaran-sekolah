<?php
session_start();
include "config.php";

// filter kelas
$kelas = $_GET['kelas'] ?? '';
$id_biaya = $_GET['id_biaya'] ?? '';


// query
$where = "WHERE 1=1";

if($kelas != ""){
    $where .= " AND siswa.Kelas='$kelas'";
}



$query = mysqli_query($conn,"
SELECT 
    siswa.Id,
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
<title>Laporan Per Kelas</title>

<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    margin:0;
    background:		#FFF8DC
}

.container{ padding:30px; }

.card{
    background:white;
    padding:20px;
    border-radius:12px;
}
/* TABLE */
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
    color : white;
}

/* HEADER */
.header{
    background:	#008000;
    color:white;
    padding:18px;
    text-align:center;
    font-size:20px;
}

.lunas{ color:green; font-weight:bold; }
.belum{ color:red; font-weight:bold; }



.top-btn{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.btn{
    padding:10px 20px;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
}

.btn-kembali{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:80px;
    height:35px;
    padding:0 20px;
    background: #DC143C;
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
    background: #8B0000;
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

/* FORM FILTER */
form{
    display:flex;
    gap:10px;
    align-items:center;
    margin-bottom:20px;
    flex-wrap:wrap;
    
}

/* SELECT */
select{
    padding:10px 15px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:14px;
    min-width:180px;
    outline:none;
    transition:0.3s;
}

select:focus{
    border-color: #3f30ae;
    box-shadow:0 0 5px rgba(32,178,170,0.5);
}
.filter-select{
    min-width:180px;
    padding:10px 15px;
    border:1px solid #dcdcdc;
    border-radius:8px;
    background:#fff;
    font-size:14px;
    cursor:pointer;
    outline:none;
    transition:0.3s;
}

.filter-select:focus{
    border-color:#228B22;
    box-shadow:0 0 8px rgba(34,139,34,0.2);
}

/* BUTTON TAMPILKAN */
button{
    padding:10px 18px;
    border:none;
    border-radius:8px;
    background: #6B8E23;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background: #556B2F;
    transform:translateY(-1px);
}
.lunas{
    color: green;
    font-weight: bold;
}

.belum{
    color: red;
    font-weight: bold;
}

.kurang{
    color: orange;
    font-weight: bold;
}
/* DARK MODE */
body.dark select{
    background:#262e48;
    color:white;
    border:1px solid #555;
}

body.dark button{
    background: #3f30ae;
}
.dark button:hover{
    background:	#251f55;
    transform:translateY(-1px);
}


/* DARK MODE */
body.dark{
    background: #0C0637;
    color:white;
}


/* CARD */
body.dark .card{
    background: #070425;
    color:white;
    box-shadow:0 5px 15px rgba(255,255,255,0.05);
}

/* TABLE */
body.dark table{
    background: #070425;
    color:white;
}

/* TABLE HEADER */
body.dark th{
    background: #262e48;
    color:white;
}

/* TABLE DATA */
body.dark td{
    color:white;
    border-bottom:1px solid #0C0637;
}

/* WARNA BARIS */
body.dark tr:nth-child(even){
    background: #2a2a2a;
}

body.dark tr:nth-child(odd){
    background: #1f1f1f;
}

/* HOVER TABLE */
body.dark tr:hover{
    background: #251f55;
    transition:0.3s;
}

/* STATUS */
body.dark .lunas{
    color: #7CFC00;
    font-weight:bold;
}

body.dark tr:hover{
    background:	#251f55;
}

body.dark .belum{
    color: #ff6b6b;
    font-weight:bold;
}
.dark-btn{
    position:fixed;
    top:20px;
    right:20px;

    width:45px;
    height:45px;

    border:none;
    border-radius:50%;

    background: #3f30ae;
    color:white;

    cursor:pointer;
    font-size:18px;

    z-index:999;
}

.dark .btn{
    padding:10px 20px;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
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

<body>

<div class="container">
    
<div class="card">

<h2>📊 Laporan Pembayaran per Kelas</h2>

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

<a href="cetak_laporan_kelas.php?kelas=<?= urlencode($kelas) ?>&id_biaya=<?= urlencode($id_biaya) ?>"
   target="_blank"
   class="btn-cetak">
    Cetak
</a>

</div>

<?php
$biaya = mysqli_query($conn,"SELECT * FROM biaya");
?>

<form method="GET">
<select name="kelas" class="filter-select">
    <option value="">-- Semua Kelas --</option>

    <?php
    $kelas_list = mysqli_query($conn,"
        SELECT DISTINCT Kelas
        FROM siswa
        ORDER BY Kelas
    ");

    while($k = mysqli_fetch_assoc($kelas_list)){

        $selected = ($kelas == $k['Kelas']) ? 'selected' : '';

        echo "<option value='".$k['Kelas']."' $selected>
                ".$k['Kelas']."
              </option>";
    }
    ?>
</select>

<select name="id_biaya" class="filter-select">
    <option value="">-- Semua Pembayaran --</option>
    <?php while($b = mysqli_fetch_assoc($biaya)){ ?>
    <option value="<?= $b['Id'] ?>" <?= ($id_biaya == $b['Id']) ? 'selected' : '' ?>>
    <?= $b['nama_biaya'] ?>
</option>
    <?php } ?>
</select>

<button type="submit">Tampilkan</button>

</form>


<table>
<tr>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Total Bayar</th>
    <th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)){

$total_tagihan = $row['target'] ?? 0;
$total_bayar   = $row['total_bayar'] ?? 0;
$sisa          = $total_tagihan - $total_bayar;

if($total_bayar == 0){
    $status = "Belum Lunas";
    $class = "belum";
}elseif($total_bayar < $total_tagihan){
    $status = "Belum Lunas";
    $class = "kurang";
}else{
    $status = "Lunas";
    $class = "lunas";
}


?>

<tr>
    <td><?= $row['Nama'] ?></td>
    <td><?= $row['Kelas'] ?></td>
    
    <td>Rp <?= number_format($row['total_bayar']) ?></td>
<td class="<?= $class ?>">
    <?= $status ?>
</td>
</tr>

<?php } ?>

</table>
<br>
<div id="areaCetak">

<table>
    
</table>

</button>

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



    

    </body>
    </html>
    `
  
