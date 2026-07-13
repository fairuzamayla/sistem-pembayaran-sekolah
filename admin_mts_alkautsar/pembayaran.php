<?php
include "config.php";
mysqli_set_charset($conn, "utf8mb4");

if(!$conn){
    die("Koneksi gagal: ".mysqli_connect_error());
}

$nama = $_GET['nama'] ?? '';

if($nama == ''){

    $siswa = [
        'Id' => 0,
        'Nama' => '-',
        'Kelas' => '-'
    ];

}else{

    $ambil = mysqli_query($conn,"
    SELECT * FROM siswa
    WHERE Nama LIKE '%$nama%'
    ");

    if(mysqli_num_rows($ambil) > 0){

        $siswa = mysqli_fetch_assoc($ambil);

    }else{

        $siswa = [
            'Id' => 0,
            'Nama' => 'Siswa Tidak Ditemukan',
            'Kelas' => '-'
        ];

    }
}

// =====================
// PROSES TRANSAKSI CICILAN
// =====================
if(isset($_POST['simpan'])){

    $id_siswa = $_POST['id_siswa'];
    $id_biaya = $_POST['id_biaya'];
    $jumlah   = $_POST['jumlah'];
    $tanggal  = date("Y-m-d");
    $kode     = "TRX".date("Ymd").rand(100,999);

    // Ambil data biaya
    $q = mysqli_query($conn,"SELECT * FROM biaya WHERE Id='$id_biaya'");
    $b = mysqli_fetch_assoc($q);

    // Total yang sudah dibayar
    $cek = mysqli_query($conn,"
        SELECT SUM(jumlah) as total
        FROM transaksi
        WHERE id_siswa='$id_siswa'
        AND id_biaya='$id_biaya'
    ");

    $d = mysqli_fetch_assoc($cek);
    $dibayar = $d['total'] ?? 0;

    $sisa = $b['jumlah'] - $dibayar;

    // Validasi
    if($jumlah <= 0){

        echo "<script>
        alert('Jumlah tidak valid!');
        </script>";

    }elseif($jumlah > $sisa){

        echo "<script>
        alert('Jumlah melebihi sisa pembayaran!');
        </script>";

    }else{

        mysqli_query($conn,"
            INSERT INTO transaksi
            (kode_transaksi,id_siswa,id_biaya,jumlah,tanggal)
            VALUES
            ('$kode','$id_siswa','$id_biaya','$jumlah','$tanggal')
        ");

        $id_transaksi = mysqli_insert_id($conn);

        
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sistem Pembayaran Sekolah</title>
<style>
body{
    font-family:Arial;
    background:#f4f6f9;
    margin:0;
    padding:0;
}

/* CONTAINER */
.container{
    width:95%;
    margin:auto;
}

/* HEADER */
.header{
    background:#008000;
    color:white;
    padding:20px;
    text-align:center;
    font-size:28px;
    font-weight:bold;

    width:100%;
    margin:0 0 30px 0;
    box-sizing:border-box;
}

/* BODY PEMBAYARAN */
.body-pembayaran{
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:30px;
}

/* CARD */
.card{
    background:white;
    padding:20px;
    border-radius:12px;
}

/* FLEX */
.flex{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

/* BOX */
.box{
    flex:1;
    min-width:200px;
    padding:20px;
    border-radius:12px;
    background:#f1f3f6;
}

.box h4{
    margin:0;
    color:#555;
}

.box h2{
    margin-top:10px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#008000;
    color:white;
    padding:14px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:nth-child(even){
    background:#f2f2f2;
}

tr:hover{
    background: #556B2F ;
     color:white;
}
/* TOMBOL CARI */
.btn-cari{
    background: #9ACD32;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.btn-cari:hover{
    background: #6B8E23;
    transform:translateY(-2px);
}

/* TOMBOL TAMBAH BIAYA */
.btn-tambah{
    background: #9ACD32;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.btn-tambah:hover{
    background: #6B8E23;
    transform:translateY(-2px);
}
/* TOMBOL CARI */
.btn-cari{
    background: #9ACD32;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.btn-cari:hover{
    background: #6B8E23;
    transform:translateY(-2px);
}

/* CARD TABLE */
.card-table{
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-top:25px;
    width:100%;
    box-sizing:border-box;
}

/* BUTTON */
.btn{
    padding:8px 12px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:12px;
    transition:0.3s;
    display:inline-block;
}

.edit{ 
    background: #FFD700 ; }

.edit:hover{
    background: #FFA500 ;
}

.hapus{
     background: #FF0000     ; }

.hapus:hover { background: #B22222    ; }

.bayar{
    background: #9ACD32;;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;
}

/* WARNA */
.hijau{
    color:green;
}

.merah{
    color:red;
}

/* TOMBOL DASHBOARD */
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

/* DARK MODE */
body.dark{
    background: #070425;
    color:white;
}

body.dark .header{
    background: #262e48;
}

body.dark .body-pembayaran,
body.dark .card,
body.dark .card-table{
    background: #0C0637;
    color:pink;
}

body.dark .box{
    background: #0b041a;
    color:white;
}

body.dark table{
    color:white;
}

body.dark th{
    background: #ea0fb7;
}

body.dark td{
    color:white;
    border-bottom:1px solid #333;
}

body.dark tr:nth-child(even){
    background: #e6158f;
}

body.dark tr:nth-child(odd){
    background:  #dbc325;
}

body.dark tr:hover{
    background: #555 !important;
}
/* TOMBOL TAMBAH BIAYA */
.dark .btn-tambah{
    background: #144981;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.dark .btn-tambah:hover{
    background: #25215a;
    transform:translateY(-2px);
}
.dark.edit{ 
    background: #b58106 ; }

.edit:hover{
    background: #c28108 ;
}

.hapus{
     background: #FF0000     ; }

.hapus:hover { background: #B22222    ; }
/* CARD TABLE */
.card-table{
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-top:25px;
    width:100%;
    box-sizing:border-box;
}

/* BUTTON */
.dark .btn{
    padding:8px 12px;
    border-radius:6px; 
    text-decoration:none;
    color:white;
    font-size:12px;
    transition:0.3s;
    display:inline-block;
}
.dark .btn-cari{
    background: #144981;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.dark .btn-cari:hover{
    background: #25215a;
    transform:translateY(-2px);
}

</style>
</head>

<body>
<div class="container">

<div class="header">

💰 Sistem Pembayaran Sekolah

</div>
<style>

    body{
    font-family: Arial, Helvetica, sans-serif;
    margin:0;
    background:		#FFF8DC
}


    form{
    display:flex;
    gap:10px;
    align-items:center;
    margin-top:20px;
}

input[type="text"]{
    padding:10px 15px;
    border-radius:8px;
    border:1px solid #ccc;
    outline:none;
    width:250px;
    transition:0.3s;
}

input[type="text"]:focus{
    border-color:#20B2AA;
    box-shadow:0 0 5px rgba(101, 46, 204, 0.5);
}


button:active{
    transform:scale(0.95);
}
form{
    display:flex;
    gap:15px;
    align-items:center;
    flex-wrap:wrap;
    margin-top:15px;
}

select, input{
    padding:12px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:16px;
    min-width:200px;
    
}

select{
    min-width:350px;
}

input{
    width:200px;
}

.dark button{
    padding:12px 20px;
    border:none;
    border-radius:8px;
    background: #4626d0;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:1,9s;
}

button:hover{
    background: 	#1b0435;
     color:white;
}



.cetak{
    background: #4169E1 ;

}

.cetak:hover{
    background: 	#0000CD    ;

}
.kembali-dashboard{
    background: #DC143C    ;

}
.kembali-dashboard:hover{
    background: 	#8B0000  ;

}

.wa{
    background:#25D366;
    color:white;
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
    font-size:12px;
    display:inline-block;
    margin-left:5px;
}

.wa:hover{
    background: 	#006400;
    color:white;
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
    font-size:12px;
    display:inline-block;
    margin-left:5px;
}

.card-table{
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-top:25px;
}

.card-table table{
    width:100%;
    border-collapse:collapse;
}

.card-table th{
    background: #008000;
    color:white;
    padding:14px;
    text-align:center;
}

.card-table td{
    padding:32px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

.card-table tr:nth-child(even){
    background: #f2f2f2;
}

.card-table tr:hover{
    background: #f5f5dc;
}

 
/* DARK MODE */
body.dark{
    background:	#070425;
    color:white;
}

.dark .header{
    background:#262e48;
    color:white;
    padding:18px;
    text-align:center;
    font-size:20px;
}
/* CARD */
body.dark .card{
    background:#0C0637;;
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
    background:#2a2a2a;
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




.dark .tambah{ background:  #008080; }
.dark .edit{ background:	#191970; }
.dark .edit:hover{
    background: #07055f;
}
.dark .hapus{ background:	#800000; }
.dark .hapus:hover{
    background: #550808;
}



</style>

<!-- CARI SISWA --> 
<div class="card">
<a href="dashboard.php" class="btn-kembali">
    Kembali
</a>




<form method="GET">
    <input type="text" name="nama" placeholder="Masukkan Nama Siswa" required>
  <button type="submit" class="btn-cari">Cari</button>
</form>


<br>



<h3>Nama: <?= $siswa['Nama']; ?></h3>
<p>Kelas: <?= $siswa['Kelas']; ?></p>
<?php

$total_tagihan = 0;
$total_dibayar = 0;

if($siswa['Id'] != 0){

    $biaya2 = mysqli_query($conn,"SELECT * FROM biaya");

    while($bx = mysqli_fetch_assoc($biaya2)){

        $total_tagihan += $bx['jumlah'];

        $cek2 = mysqli_query($conn,"
        SELECT SUM(jumlah) as total
        FROM transaksi
        WHERE id_siswa='".$siswa['Id']."'
        AND id_biaya='".$bx['Id']."'
        ");

        $dx = mysqli_fetch_assoc($cek2);
        $total_dibayar += $dx['total'] ?? 0;
    }

}

$sisa_tagihan = $total_tagihan - $total_dibayar;

?>

<div class="flex">

<div class="box">
<h4>Total Tagihan</h4>


<h2>Rp <?= number_format($total_tagihan); ?></h2>

</div>

<div class="box">
<h4>Sudah Dibayar</h4>
<h2 class="hijau">Rp <?= number_format($total_dibayar); ?></h2>   
</div>

<div class="box">
<h4>Sisa Tagihan</h4>
<h2 class="merah">Rp <?= number_format($sisa_tagihan); ?></h2>

</div>

</div>



<!-- ===================== -->
<!-- FORM TRANSAKSI -->
<!-- ===================== -->

<h3>💳 Input Pembayaran</h3>



<form method="POST">
<input type="hidden" name="id_siswa" value="<?= $siswa['Id']; ?>">


<select name="id_biaya" required>
<option value="">-- Pilih Biaya --</option>

<?php
$biaya = mysqli_query($conn,"SELECT * FROM biaya");
while($b = mysqli_fetch_assoc($biaya)){
?>
<option value="<?= $b['Id']; ?>">

<?= $b['nama_biaya']; ?>
(Rp <?= number_format($b['jumlah']); ?>)

</option>
<?php } ?>

</select>

<br><br>

<input type="number" name="jumlah" placeholder="Jumlah bayar" required>

<br><br>

<button type="submit" name="simpan" class="bayar">Bayar</button>
</form>

<br><br>

<!-- ===================== -->
<!-- TABEL BIAYA + SISA -->
<!-- ===================== -->


<h3>📋 Data Pembayaran</h3>

<table>
<tr>
<th>Nama Biaya</th>
<th>Total</th>
<th>Sudah Dibayar</th>
<th>Sisa</th>
<th>Status</th>
<th>Aksi</th>
</tr>



<?php
$biaya = mysqli_query($conn,"SELECT * FROM biaya");

while($b = mysqli_fetch_assoc($biaya)){

$cek = mysqli_query($conn,"SELECT SUM(jumlah) as total 
FROM transaksi 
WHERE id_siswa='".$siswa['Id']."' 
AND id_biaya='".$b['Id']."'");

$d = mysqli_fetch_assoc($cek);
$dibayar = $d['total'] ?? 0;
$sisa = $b['jumlah'] - $dibayar;
?>


<tr>

<td><?= $b['nama_biaya']; ?></td>

<td>
Rp <?= number_format($b['jumlah'],0,',','.'); ?>
</td>

<td>
Rp <?= number_format($dibayar); ?>
</td>

<td>
Rp <?= number_format($sisa); ?>
</td>

<td>
  
<?php
if($dibayar == 0){
    echo '<span style="color:red;">Belum Lunas</span>';

}elseif($dibayar < $b['jumlah']){
    echo '<span style="color:orange;">Belum Lunas</span>';

}else{
    echo '<span style="color:green;">Lunas</span>';
}
?>
</td>

<td>

<a href="edit_biaya.php?id=<?= $b['Id']; ?>" 
class="btn edit"
onclick="return confirm('Yakin ingin edit data ini?')">
Edit
</a>

<a href="hapus_biaya.php?id=<?= $b['Id']; ?>" 
class="btn hapus"
onclick="return confirm('Yakin ingin menghapus data ini?')">
Hapus
</a>

</td>

</tr>


<?php } ?>

</table>


<br><br>

<?php

$transaksiTerbaru = mysqli_query($conn,"
SELECT transaksi.*, 
siswa.Nama, 
siswa.No_HP,
biaya.nama_biaya
FROM transaksi
JOIN siswa ON transaksi.id_siswa = siswa.Id
JOIN biaya ON transaksi.id_biaya = biaya.Id
ORDER BY transaksi.id DESC
LIMIT 5
");

?>

<h3>🧾 Transaksi Terbaru</h3>

<table>

<tr>
    <th>Tanggal</th>
    <th>Nama</th>
    <th>Pembayaran</th>
    <th>Jumlah</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php while($t = mysqli_fetch_assoc($transaksiTerbaru)){ ?>

<tr>

<td><?= $t['tanggal']; ?></td>

<td><?= $t['Nama']; ?></td>

<td><?= $t['nama_biaya']; ?></td>

<td>
Rp <?= number_format($t['jumlah']); ?>
</td>

<?php
$cekStatus = mysqli_query($conn,"
SELECT SUM(jumlah) as total
FROM transaksi
WHERE id_siswa='".$t['id_siswa']."'
AND id_biaya='".$t['id_biaya']."'
");

$dataStatus = mysqli_fetch_assoc($cekStatus);

$qBiaya = mysqli_query($conn,"
SELECT jumlah
FROM biaya
WHERE Id='".$t['id_biaya']."'
");

$biayaStatus = mysqli_fetch_assoc($qBiaya);

$sisaStatus = $biayaStatus['jumlah'] - ($dataStatus['total'] ?? 0);
?>

<td>
<?php if($sisaStatus <= 0){ ?>
    <span style="color:green;">Lunas</span>
<?php } else { ?>
    <span style="color:red;">Belum Lunas</span>
<?php } ?>
</td>

<td>

<a href="struk.php?id=<?= $t['id']; ?>" 
class="btn cetak"
target="_blank">
🖨️ Cetak 
</a>
<a href="
https://wa.me/62<?= ltrim($t['No_HP'],'0'); ?>?text=
*KONFIRMASI PEMBAYARAN*%0A%0A
Nama Siswa : *<?= $t['Nama']; ?>*%0A
Pembayaran : *<?= $t['nama_biaya']; ?>*%0A
Jumlah : *Rp <?= number_format($t['jumlah']); ?>*%0A
Tanggal : *<?= $t['tanggal']; ?>*%0A%0A
================================%0A
Status : *BERHASIL*%0A
================================%0A%0A
Terima kasih telah melakukan pembayaran.%0A
Simpan pesan ini sebagai bukti pembayaran.
"
class="wa"
target="_blank">
📱 WhatsApp
</a>
 
</td>
</tr>


<?php } ?>

<?php

if(isset($_POST['tambah_biaya'])){

    $nama_biaya = $_POST['nama_biaya'];
    $total = $_POST['total_biaya'];

    mysqli_query($conn,"
    INSERT INTO biaya(nama_biaya,jumlah)
    VALUES('$nama_biaya','$total') 
    ");

    echo "<script>
    alert('Biaya berhasil ditambahkan');
    location.href='';
    </script>";
}

?>

</table>


<br><br>


<h3>➕ Tambah Pembayaran Baru</h3>

<form method="POST">

<table>
<tr>
    <th>Nama Biaya</th>
    <th>Total Tagihan</th>
    <th>Aksi</th>
</tr>

<tr>

<td>
<input type="text" 
name="nama_biaya" 
placeholder="Masukkan Nama Biaya"
required>
</td>

<td>
<input type="number" 
name="total_biaya" 
placeholder="Masukkan Total"
required>
</td>

<td>
<button type="submit" name="tambah_biaya" class="btn-tambah">
    Tambah
</button>
</td>

</tr>

</table>

</form>
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