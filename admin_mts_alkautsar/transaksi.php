<?php
include "config.php";

$siswa = null;
$detail = [];

// ====================
// CARI SISWA (TETAP)
// ====================
if(isset($_GET['nisn'])){
    $nisn = trim($_GET['nisn']);
    $q = mysqli_query($conn,"SELECT * FROM siswa WHERE Nisn='$nisn'");
    $siswa = mysqli_fetch_array($q);
}

// ====================
// PROSES BAYAR MULTI
// ====================
if(isset($_POST['bayar'])){

    $id_siswa = $_POST['id_siswa'];
    $tanggal = date("Y-m-d");
    $kode = "TRX".date("Ymd").rand(100,999);

    foreach($_POST['biaya'] as $id_biaya){

        $q = mysqli_query($conn,"SELECT * FROM biaya WHERE id='$id_biaya'");
        $b = mysqli_fetch_array($q);

        $cek = mysqli_query($conn,"
        SELECT SUM(jumlah) as total
        FROM transaksi
        WHERE id_siswa='$id_siswa'
        AND id_biaya='$id_biaya'
        ");

        $d = mysqli_fetch_array($cek);
        $dibayar = $d['total'] ?? 0;

        $sisa = $b['jumlah'] - $dibayar;

        if($sisa > 0){

            mysqli_query($conn,"
            INSERT INTO transaksi
            (kode_transaksi,id_siswa,id_biaya,jumlah,tanggal)
            VALUES
            ('$kode','$id_siswa','$id_biaya','$sisa','$tanggal')
            ");

            $detail[] = [
                'nama' => $b['nama_biaya'],
                'bayar' => $sisa
            ];
        }
    }

$berhasil = true;
}


    
?>

<!DOCTYPE html>
<html>
<head>
<title>Pembayaran Sekolah</title>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>
body{font-family:Arial; background:#f4f6f9;}
.container{width:80%; margin:auto;}

button{
    padding:6px 10px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.bayar{background:#2ecc71; color:white;}
.cetak{background:#3498db; color:white;}
.batal{background:#e74c3c; color:white;}
</style>
</head>

<body>

<div class="container">

<h2>💰 Sistem Pembayaran Sekolah</h2>

<!-- INPUT NISN -->
<form method="GET">
    <input type="text" name="nisn" placeholder="Masukkan NISN" required>
    <button type="submit">Cari</button>
</form>

<?php if($siswa){ ?>

<!-- BIODATA -->
<h3>👤 Biodata</h3>
<p><?= $siswa['Nama']; ?> | <?= $siswa['Kelas']; ?></p>

<!-- ==================== -->
<!-- DAFTAR BIAYA (CHECKBOX) -->
<!-- ==================== -->
<h3>📋 Pilih Pembayaran</h3>

<form method="POST">
<input type="hidden" name="id_siswa" value="<?= $siswa['Id']; ?>">

<table border="1" cellpadding="10">
<tr>
<th>Pilih</th>
<th>Nama Biaya</th>
<th>Total</th>
<th>Status</th>
</tr>

<?php
$biaya = mysqli_query($conn,"SELECT * FROM biaya");

while($b = mysqli_fetch_array($biaya)){

$cek = mysqli_query($conn,"SELECT SUM(jumlah) as total FROM transaksi 
WHERE id_siswa='".$siswa['Id']."' AND id_biaya='".$b['id']."'");

$d = mysqli_fetch_array($cek);
$dibayar = $d['total'] ?? 0;
$sisa = $b['jumlah'] - $dibayar;
?>

<tr>

<td>
<?php if($sisa > 0){ ?>
<input type="checkbox" name="biaya[]" value="<?= $b['id']; ?>">
<?php } ?>
</td>

<td><?= $b['nama_biaya']; ?></td>
<td>Rp <?= number_format($b['jumlah']); ?></td>

<td>
<?php if($sisa <= 0){ ?>
✅ Lunas
<?php } else { ?>
Sisa: Rp <?= number_format($sisa); ?>
<?php } ?>
</td>

</tr>

<?php } ?>

</table>

<br>
<button type="submit" name="bayar" class="bayar">
💰 Bayar Sekarang
</button>

</form>

<?php } ?>

<!-- ==================== -->
<!-- HASIL PEMBAYARAN -->
<!-- ==================== -->
<?php if(!empty($detail)){ ?>

<h3>🧾 Transaksi Berhasil</h3>

<table border="1" cellpadding="10">
<tr>
<th>Biaya</th>
<th>Dibayar</th>
</tr>

<?php 
$total = 0;
foreach($detail as $d){
$total += $d['bayar'];
?>

<tr>
<td><?= $d['nama']; ?></td>
<td>Rp <?= number_format($d['bayar']); ?></td>
</tr>

<?php } ?>

<tr>
<td><b>Total</b></td>
<td><b>Rp <?= number_format($total); ?></b></td>
</tr>

</table>

<br>

<button onclick="window.print()" class="cetak">🧾 Cetak</button>
<button onclick="location.reload()" class="batal">❌ Batal</button>

<?php } ?>

</div>
<?php if(isset($berhasil)){ ?>



<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Pembayaran berhasil dilakukan dan tagihan telah lunas',
    confirmButtonColor: '#28a745'
});
</script>

<?php } ?>
</body>
</html> 