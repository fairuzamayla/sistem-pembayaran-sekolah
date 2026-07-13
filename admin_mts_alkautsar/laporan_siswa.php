<?php
include "config.php";

// filter kelas
$kelas_filter = $_GET['kelas'] ?? '';

// query
if($kelas_filter != ""){
    $data = mysqli_query($conn,"SELECT * FROM siswa WHERE Kelas='$kelas_filter'");
} else {
    $data = mysqli_query($conn,"SELECT * FROM siswa");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan Data Siswa</title>

<style>
body{ font-family:Arial; background:#f4f6f9; }

.container{
    padding:30px;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:	black;
    color:black;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
}

select{
    padding:8px;
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

/* TABLE */
body.dark table{
    background:#1e1e1e;
    color:white;
}

/* HEADER TABLE */
body.dark th{
    background:#145214;
    color:white;
}

/* DATA TABLE */
body.dark td{
    color:white;
    border-bottom:1px solid #333;
}

/* WARNA BARIS */
body.dark tr:nth-child(even){
    background:#2a2a2a;
}

body.dark tr:nth-child(odd){
    background:#1f1f1f;
}

/* HOVER TABLE */
body.dark tr:hover{
    background:#556B2F;
    transition:0.3s;
}

/* SELECT */
body.dark select{
    background:#2c2c2c;
    color:white;
    border:1px solid #444;
}

</style>
</head>

<body>

<div class="container">
<div class="card">

<h2>📊Laporan Data Siswa</h2>

<!-- FILTER KELAS -->
<form method="GET">
    <label>Filter Kelas:</label>
    <select name="kelas" onchange="this.form.submit()">
        <option value="">-- Semua Kelas --</option>
        <option value="7A">7A</option>
        <option value="7B">7B</option>
        <option value="8A">8A</option>
        <option value="8B">8B</option>
        <option value="9A">9A</option>
    </select>
</form>

<table>
<tr>
    <th>NISN</th>
    <th>Nama</th>
    <th>Jenis Kelamin</th>
    <th>Kelas</th>
    <th>Tahun Masuk</th>
    <th>No HP</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><?= $row['Nisn'] ?></td>
    <td><?= $row['Nama'] ?></td>
    <td><?= $row['Jenis_Kelamin'] ?></td>
    <td><?= $row['Kelas'] ?></td>
    <td><?= $row['Tahun_Masuk'] ?></td>
    <td><?= $row['No_HP'] ?></td>
</tr>
<?php } ?>

</table>

</div>
</div>

</body>
</html>