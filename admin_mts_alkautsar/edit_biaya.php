<?php
$conn = mysqli_connect("localhost","root","","project_kp");

$id = $_GET['id'] ?? 0;

// ambil data biaya
$q = mysqli_query($conn,"SELECT * FROM biaya WHERE Id='$id'");
$data = mysqli_fetch_assoc($q);

// kalau data ga ada
if(!$data){
    die("Data tidak ditemukan");
}

// proses update
if(isset($_POST['update'])){
    $jumlah = $_POST['jumlah'];

    mysqli_query($conn,"
        UPDATE biaya 
        SET jumlah='$jumlah'
        WHERE Id='$id'
    ");

    echo "<script>
        alert('Total biaya berhasil diubah');
        window.location='pembayaran.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Biaya</title>
<style>
body{font-family:Arial;background:#f4f6f9;}
.box{
    width:400px;
    margin:100px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
input{
    width:100%;
    padding:10px;
    margin-top:10px;
}
button{
    margin-top:15px;
    padding:10px;
    width:100%;
    background:#2ecc71;
    color:white;
    border:none;
    border-radius:5px;
}
</style>
</head>

<body>

<div class="box">

<h2>Edit Total Biaya</h2>

<p><b><?= $data['nama_biaya']; ?></b></p>

<form method="POST">

<label>Total Biaya</label>
<input type="number" name="jumlah" value="<?= $data['jumlah']; ?>" required>

<button type="submit" name="update">Simpan</button>

</form>

</div>

</body>
</html>