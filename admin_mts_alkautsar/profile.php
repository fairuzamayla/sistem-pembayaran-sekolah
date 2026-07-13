<?php
session_start();
$conn = mysqli_connect("localhost","root","","project_kp");

$id = $_SESSION['id_admin'];

$data = mysqli_query($conn,"
SELECT * FROM admin 
WHERE id_admin='$id'
");

$d = mysqli_fetch_assoc($data);

if(isset($_POST['upload'])){

    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];

    move_uploaded_file($tmp,"upload/".$foto);

    mysqli_query($conn,"
    UPDATE admin 
    SET profile='$foto'
    WHERE id_admin='$id'
    ");

    echo "
    <script>
    alert('Profile berhasil diupdate');
    location.href='profile.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>

<style>

body{
    font-family:Arial;
    background:#f4f6f9;
}

.card{
    width:400px;
    margin:50px auto;
    background:white;
    padding:30px;
    border-radius:15px;
    text-align:center;
}

img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:20px;
}

button{
    background:#32CD32;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;
}

</style>
</head>

<body>

<div class="card">

<img src="upload/<?= $d['profile']; ?>">

<h2><?= $d['nama_admin']; ?></h2>

<form method="POST" enctype="multipart/form-data">

<input type="file" name="foto" required>

<br><br>

<button type="submit" name="upload">
Upload Profile
</button>

</form>

</div>

</body>
</html>