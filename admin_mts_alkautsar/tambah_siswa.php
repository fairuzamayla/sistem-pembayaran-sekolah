<?php
$conn = mysqli_connect("localhost","root","","project_kp");

if(!$conn){
    die("Koneksi gagal: ".mysqli_connect_error());
}

if(isset($_POST['simpan'])){
    
   
    $nisn  = $_POST['Nisn'] ?? '';
    $nama  = $_POST['Nama'] ?? '';
    $jk    = $_POST['Jenis_Kelamin'] ?? '';
    $tahun = $_POST['Tahun_Masuk'] ?? '';
    $kelas = $_POST['Kelas'] ?? '';
    $No_HP = $_POST['No_HP'] ?? '';



// cek kosong (NISN tidak wajib)
if($nama == "" || $jk == "" || $tahun == "" || $kelas == "" || $No_HP == ""){
    echo "<script>alert('Data tidak boleh kosong');</script>";
} else {

   $query = mysqli_query($conn,"
            INSERT INTO siswa (Nisn, Nama, Jenis_Kelamin, Tahun_Masuk, Kelas, No_HP)
            VALUES ('$nisn','$nama','$jk','$tahun','$kelas','$No_HP')
        ") or die(mysqli_error($conn));

        if($query){
            echo "<script>
                alert('Data siswa berhasil ditambahkan');
                window.location='data_siswa.php';
            </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Siswa</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
}

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

h2{
    text-align:center;
    margin-bottom:20px;
}

input{
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
    background:	#18d845;
    border:none;
    border-radius:8px;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:	#0f440f;
}

.kembali{
    display:block;
    width:100%;
    margin-top:10px;
    padding:9px;
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

select{
    width:100%;
    padding:10px;
    margin-top:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

label{
    display:block;
    margin-top:15px;
    font-weight:500;
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

/* INPUT */
body.dark input{
    background: #2c2c2c;
    color:white;
    border:1px solid #444;
}

/* SELECT */
body.dark select{
    background:#2c2c2c;
    color:white;
    border:1px solid #444;
}

/* LABEL */
body.dark label{
    color:white;
}

/* BUTTON */
body.dark button{
    background:#228B22;
    color:white;
}

body.dark button:hover{
    background:#145214;
}

/* LINK KEMBALI */
body.dark .kembali{
    color:#ddd;
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

<h2>➕ Tambah Siswa</h2>



<form method="POST">

<label>NISN</label>
<input type="text" name="Nisn" placeholder="Kosongkan jika belum ada">

<label>Nama Siswa</label>
<input type="text" name="Nama" required>

<label>Jenis Kelamin</label>
<select name="Jenis_Kelamin" required>
    <option value="">-- Pilih --</option>
    <option value="L">Laki-laki</option>
    <option value="P">Perempuan</option>
</select>

<label>Tahun Masuk</label>
<input type="number" name="Tahun_Masuk" required>

<label>Kelas</label>
<input type="text" name="Kelas" required>

<label>No Handphone</label>
<input type="text" name="No_HP" required>

<button type="submit" name="simpan">Simpan</button>

</form>

<a href="data_siswa.php" class="kembali"> Kembali</a>


</div>

</div>

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