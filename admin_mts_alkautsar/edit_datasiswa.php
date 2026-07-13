<?php
$conn = mysqli_connect("localhost","root","","project_kp");

// ambil id dari URL
$id = $_GET['id'];

// ambil data berdasarkan id
$data = mysqli_query($conn,"SELECT * FROM siswa WHERE Id='$id'");
$row = mysqli_fetch_assoc($data);

// proses update
if(isset($_POST['update'])){

    $nisn  = $_POST['Nisn'];
    $nama  = $_POST['Nama'];
    $jk    = $_POST['Jenis_Kelamin'];
    $tahun = $_POST['Tahun_Masuk'];
    $kelas = $_POST['Kelas'];
    $No_HP = $_POST['No_HP'];

    mysqli_query($conn,"
        UPDATE siswa SET
        Nisn='$nisn',
        Nama='$nama',
        Jenis_Kelamin='$jk',
        Tahun_Masuk='$tahun',
        Kelas='$kelas',
        No_HP='$No_HP'
        WHERE Id='$id'
    ");

    echo "<script>
        alert('Data berhasil diupdate');
        window.location=' data_siswa.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Siswa</title>

<style>
body{ font-family:Arial; background:#f4f6f9; }

.container{
    width:400px;
    margin:80px auto;
}

.card{
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

input, select{
    width:100%;
    padding:10px;
    margin-top:10px;
    border-radius:8px;
    border:1px solid #ccc;
}
input, select{
    width:100%;
    padding:10px;
    margin-top:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    width:100%;
    margin-top:20px;
    padding:12px;
    background: #32CD32;
    border:none;
    border-radius:8px;
    color:white;
    font-weight:bold;
}
.button :hover{
    background: #095f06;
}
.kembali{
    display:block;
    width:100%;
    margin-top:15px;
    padding:12px;
    background: #5e8ce0;
    border:none;
    border-radius:8px;
    color:white;
    font-weight:bold;
    text-align:center;
    text-decoration:none;
    box-sizing:border-box;
    transition:0.3s;
}

.kembali:hover{
    background: #221c7c;
}
/* DARK MODE */
body.dark{
    background:#121212;
    color:white;
}

/* CARD */
body.dark .card{
    background:#1e1e1e;
    color:white;
    box-shadow:0 5px 15px rgba(255,255,255,0.05);
}

/* INPUT & SELECT */
body.dark input,
body.dark select{
    background:#2c2c2c;
    color:white;
    border:1px solid #444;
}

/* LABEL & JUDUL */
body.dark label,
body.dark h2{
    color:white;
}

/* TOMBOL UPDATE */
body.dark button{
    background:#228B22;
    color:white;
}

body.dark button:hover{
    background:#145214;
}

/* TOMBOL KEMBALI */
body.dark .kembali{
    background:#7159b9;
    color:white;
}

body.dark .kembali:hover{
    background:#352065;
}

/* PLACEHOLDER */
body.dark input::placeholder{
    color:#aaa;
}
</style>
</head>

<body>

<div class="container">
<div class="card">

<h2>Edit Siswa</h2>

<form method="POST">

<label>NISN</label>
<input type="text" name="Nisn" value="<?= $row['Nisn'] ?>">

<label>Nama</label>
<input type="text" name="Nama" value="<?= $row['Nama'] ?>">

<label>Jenis Kelamin</label>
<select name="Jenis_Kelamin">
    <option value="L" <?= $row['Jenis_Kelamin']=='L'?'selected':'' ?>>Laki-laki</option>
    <option value="P" <?= $row['Jenis_Kelamin']=='P'?'selected':'' ?>>Perempuan</option>
</select>

<label>Tahun Masuk</label>
<input type="number" name="Tahun_Masuk" value="<?= $row['Tahun_Masuk'] ?>">

<label>Kelas</label>
<input type="text" name="Kelas" value="<?= $row['Kelas'] ?>">

<label>No HP</label>
<input type="text" name="No_HP" value="<?= $row['No_HP'] ?>">

<button type="submit" name="update">Update</button>

<a href="data_siswa.php" class="kembali">Kembali</a>

</form>

</div>
</div>
<script>
window.onload = function(){

    if(localStorage.getItem("theme") === "dark"){
        document.body.classList.add("dark");
    }

}
</script>
</body>
</html>