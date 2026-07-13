<?php
$conn = mysqli_connect("localhost","root","","project_kp");

$id = $_GET['id'];

$q = mysqli_query($conn,"
SELECT t.*, s.Nama, b.nama_biaya 
FROM transaksi t
JOIN siswa s ON t.id_siswa = s.Id
JOIN biaya b ON t.id_biaya = b.Id
WHERE t.id='$id'
");

$data = mysqli_fetch_assoc($q);

if(isset($_POST['update'])){

    $jumlah = $_POST['jumlah'];

    mysqli_query($conn,"
    UPDATE transaksi 
    SET jumlah='$jumlah'
    WHERE id='$id'
    ");

    echo "<script>
    alert('Berhasil diubah');
    window.location='pembayaran.php';
    </script>";
}
?>

<h2>Edit Transaksi</h2>

<p><?= $data['Nama']; ?></p>
<p><?= $data['nama_biaya']; ?></p>

<form method="POST">
<input type="number" name="jumlah" value="<?= $data['jumlah']; ?>">
<br><br>
<button type="submit" name="update">Simpan</button>
</form>