<?php
$conn = mysqli_connect("localhost","root","","project_kp");

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
body{ font-family:Arial; background:#f4f6f9; }

.container{ padding:30px; }

.card{
    background:white;
    padding:20px;
    border-radius:12px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#2ecc71;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
}

.lunas{ color:green; font-weight:bold; }
.belum{ color:red; font-weight:bold; }
</style>
</head>

<body>

<div class="container">
<div class="card">

<h2>📊 Laporan Pembayaran per Kelas</h2>
<?php
$biaya = mysqli_query($conn,"SELECT * FROM biaya");
?>

<form method="GET">

<select name="kelas">
    <option value="">-- Semua Kelas --</option>
    <option value="VII A">VII A</option>
    <option value="VII B">VII B</option>
</select>

<select name="id_biaya">
    <option value="">-- Semua Pembayaran --</option>
    <?php while($b = mysqli_fetch_assoc($biaya)){ ?>
        <option value="<?= $b['Id'] ?>">
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
    
$status = ($row['total_bayar'] >= $row['target']) ? "Lunas" : "Belum";
?>

<tr>
    <td><?= $row['Nama'] ?></td>
    <td><?= $row['Kelas'] ?></td>
    
    <td>Rp <?= number_format($row['total_bayar']) ?></td>
    <td class="<?= $status == 'Lunas' ? 'lunas' : 'belum' ?>">
        <?= $status ?>
    </td>
</tr>

<?php } ?>





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
   


