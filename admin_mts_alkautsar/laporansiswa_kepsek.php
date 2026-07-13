<?php
$conn = mysqli_connect("localhost","root","","project_kp");

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
    background:#2ecc71;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
}

select{
    padding:8px;
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