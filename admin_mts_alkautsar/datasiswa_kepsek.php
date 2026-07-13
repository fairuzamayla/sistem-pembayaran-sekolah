<?php
include "config.php";
$kelas = $_GET['kelas'] ?? '';

if($kelas != ''){
    $data = mysqli_query($conn,"
    SELECT * FROM siswa
    WHERE Kelas='$kelas'
    ");
}else{
    $data = mysqli_query($conn,"SELECT * FROM siswa");
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Data Siswa</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
}

.header{
    background:#228B22;
    color:white;
    padding:18px;
    text-align:center;
    font-size:20px;
}

.container{
    padding:30px;
}

.card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}


.top-btn{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}
.tampilkan{
    background:#228B22;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

.tampilkan:hover{
    background:#006400;
}
/* FILTER */
.filter-kelas{
    display:flex;
    align-items:center;
    gap:20px;
    margin:25px 0 30px;
}
.select-kelas{
    min-width:180px;
    padding:10px 15px;
    border:1px solid #137f18;
    border-radius:8px;
    background: #fff;
    font-size:14px;
    cursor:pointer;
    outline:none;
    transition:0.3s;
}

.select-kelas:focus{
    border-color:#228B22;
    box-shadow:0 0 8px rgba(34,139,34,0.2);
}


/* TOMBOL TAMPILKAN */
.tampilkan{
    background: #6B8E23;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s;
}


.tampilkan:hover{
    background:#556B2F;
}



/* TOMBOL */
.btn{
    padding:8px 12px;
    border-radius:6px;
    text-decoration:none;
    color:white;
}


/* TAMBAH */
.tambah{
    background: #1cd73e;
}

.tambah:hover{
    background:	#0d6c18  ;
}

/* KEMBALI */
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
.aksi{
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.edit{ 
    background: #FFD700 ; }

.edit:hover{
    background: #FFA500 ;
}

.hapus{
     background: #FF0000     ; }

.hapus:hover { background: #B22222    ; }

@media (max-width: 768px){

    .aksi{
        flex-direction: column;
        gap: 6px;
    }

    .edit,
    .hapus{
        width: 70px;
        margin: 0 auto;
    }
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


/* HOVER TABLE */
tr{
    transition:0.3s;
}

tr:hover{
    background: #556B2F !important;
    color:white;
}

tr:hover td{
    color:white;
}



table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#228B22;
    color:white;
    padding:12px;
    text-align:center;
}

td{
    padding:10px;
    text-align:center;
}

tr:nth-child(even){
    background:#f2f2f2;
}




 
/* DARK MODE */
body.dark{
    background:	#070425;
    color:white;
}

.dark .header{
    background: #262e48;
    color:white;
    padding:18px;
    text-align:center;
    font-size:20px;
}
/* CARD */
body.dark .card{
    background: #0C0637;;
    color:white;
}


/* TOMBOL */
.dark .btn{
    padding:10px 15px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    transition:0.3s;
}
.dark .select-kelas{
    min-width:180px;
    padding:10px 15px;
    border:1px solid #ffffff;
    border-radius:8px;
    background: #262e48;
    font-size:14px;
    cursor:pointer;
    outline:none;
    transition:0.3s;
    color:white;
}

/* TAMBAH */
.dark .tambah{
    background: #403686;
}

.dark .tambah:hover{
    background: #331e5e;
}

.dark .tampilkan{
    background: #3f30ae;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

.dark .tampilkan:hover{
    background: #251f55;
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
    background:	#A9A9A9!important;
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

</style>
</head>

<body onload="window.print()">

<div class="header">📋 Data Siswa</div>

<div class="container">

    <div class="card">

        <!-- TOMBOL -->
        <div class="top-btn">

          <a href="dashboard_kepala.php" class="btn-kembali">
    Kembali
    </a>

          <a href="cetak_riwayat.php" target="_blank" class="btn-cetak">
    Cetak
</a>

        </div>

        <!-- TABLE -->
 

<form method="GET" class="filter-kelas">

    <select name="kelas" class="select-kelas">
        <option value="">Semua Kelas</option>

        <?php
        $kelas_list = mysqli_query($conn,"
        SELECT DISTINCT Kelas
        FROM siswa
        ORDER BY Kelas
        ");

        while($k = mysqli_fetch_assoc($kelas_list)){
            $selected = ($kelas == $k['Kelas']) ? 'selected' : '';
            echo "<option value='".$k['Kelas']."' $selected>".$k['Kelas']."</option>";
        }
        ?>
    </select>

    <button type="submit" class="tampilkan">
        Tampilkan
    </button>

</form>

<div class="card-table">

<table>
<tr>
    <th>NISN</th>
    <th>Nama</th>
    <th>Jenis Kelamin</th>
    <th>Tahun</th>
    <th>Kelas</th>
    <th>No HP</th>

</tr>

<?php while($row = mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><?= $row['Nisn'] ?></td>
    <td><?= $row['Nama'] ?></td>
    <td><?= $row['Jenis_Kelamin'] ?></td>
    <td><?= $row['Tahun_Masuk'] ?></td>
    <td><?= $row['Kelas'] ?></td>
    <td><?= $row['No_HP'] ?></td>

</tr>
<?php } ?>

</table>

    </div>

</div>


<br><br>




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