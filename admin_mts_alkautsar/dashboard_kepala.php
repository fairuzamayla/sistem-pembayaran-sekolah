<?php
session_start();

if(!isset($_SESSION['username'])){
    header("location:login.php");
    exit();
}
include "config.php";

/* jumlah siswa */
$querySiswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa");
$dataSiswa = mysqli_fetch_assoc($querySiswa);
$total_siswa = $dataSiswa['total'];

/*total transaksi*/
$total_transaksi = mysqli_query($conn,"
SELECT SUM(jumlah) as total FROM transaksi
");
$data_transaksi = mysqli_fetch_assoc($total_transaksi);

/*total hari ini*/
$total_hari_ini = mysqli_query($conn,"
SELECT IFNULL(SUM(jumlah),0) as total_hari
FROM transaksi
WHERE DATE(tanggal) = CURDATE()
");
$data_hari_ini = mysqli_fetch_assoc($total_hari_ini);

/*total bulan ini*/
$total_bulan_ini = mysqli_query($conn,"
SELECT IFNULL(SUM(jumlah),0) as total_bulan
FROM transaksi
WHERE MONTH(tanggal)=MONTH(CURDATE())
AND YEAR(tanggal)=YEAR(CURDATE())
");

$data_bulan_ini = mysqli_fetch_assoc($total_bulan_ini);




/* chart transaksi */
$data_bulan = [];
$data_total = [];

$queryChart = mysqli_query($conn,"
    SELECT MONTH(tanggal) as bulan, SUM(jumlah) as total
    FROM transaksi
    GROUP BY MONTH(tanggal)
");

while ($row = mysqli_fetch_assoc($queryChart)) {
    $data_bulan[] = $row['bulan'];
    $data_total[] = $row['total'];
}

// ubah angka bulan jadi nama
$nama_bulan = [
    1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"Mei",6=>"Jun",
    7=>"Jul",8=>"Agu",9=>"Sep",10=>"Okt",11=>"Nov",12=>"Des"
];

$label_bulan = [];
foreach ($data_bulan as $b) {
    $label_bulan[] = $nama_bulan[$b];
}



/*transaksi terbaru*/
$queryTerbaru = mysqli_query($conn,"
SELECT transaksi.*, siswa.Nama, siswa.Kelas
FROM transaksi
JOIN siswa ON transaksi.id_siswa = siswa.Id
ORDER BY transaksi.id DESC
LIMIT 5
");


?>
<!DOCTYPE html>
<html>

<head>
<title>Dashboard admin </title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    margin:0;
    background:		#FFF8DC
}

.content{
       margin-left:250px;
    padding:30px;
        min-height: auto;
}
@media (max-width: 768px){
    .content{
        padding-bottom: 20px;
    }
}

/* WELCOME */
.welcome-box{
    background:linear-gradient(135deg,#F0E68C,#228B22);
    padding:15px;
    border-radius:30px;
    margin-bottom:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);

    width:100%;
    box-sizing:border-box;
}

.welcome-box h1{
    margin:0 0 10px 0;
    font-size:38px;
    color:#222;
}

.welcome-box p{
    margin:0;
    font-size:18px;
    color:#444;
    line-height:1.6;

}

/* SIDEBAR */
.sidebar{
      width:250px;
    height:100vh;
    background:linear-gradient(180deg,#008000,#228B22);
    position:fixed;
    color: white;
    top:0;
    left:0;
    display:flex;
    flex-direction:column;
    overflow-y:auto;
}

.sidebar h3{
    text-align:center;
    margin:30px 0 35px 0;
}

.profile-sidebar{
    margin-top:auto;
    padding:15px;

    display:flex;
    align-items:center;
    gap:12px;
    color:white;
    border-top:1px solid rgba(255,255,255,0.3);
    position:sticky;
    bottom:0;

    background:#228B22;
}
/* FOTO PROFILE */
.profile-img-sidebar{
    width:45px;
    height:45px;
    border-radius:50%;
    object-fit:cover;
 object-position:center;
    border:3px solid white;

    display:block;
}


.profile-sidebar p{
    margin:0;
    font-size:20px;
    opacity:0.8;
}

/* MENU */
.sidebar a{
    display:flex;
    align-items:center;
    gap:10px;
    color:white;
padding:12px 16px;

    text-decoration:none;
    cursor:pointer;
    transition:0.3s;
    border-left:4px solid transparent;
    border-radius:8px;
    margin:7px 10px;
    border-bottom:1px solid rgba(255,255,255,0.15);
    
}

.sidebar a:hover{
  
    transform:translateY(-5px);
    background:	#556B2F;
    padding-left:20px;
    border-left:4px solid black;
      color: #ffffff;
}


.submenu a{
    font-size:15px;
    padding:10px 16px 10px 32px;
    margin:1px 10px;
}
.submenu{
    display:none;
}
.sidebar-title{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2px;
    margin: 30px 0 25px 15px;
    color: white;
    font-size: 26px;
    font-weight: bold;

}
.sidebar-title span{
    position: relative;
    top: 1px;
}

.menu-list{
    margin-top:5px;
     font-size:15px;
    justify-content:center;
}
.menu-logo{
    width: 32px;
    height: 32px;
    object-fit: contain;
}
/* HEADER */
.header{
     margin-left:250px;
    background:#228B22;
    color:white;
    padding:18px 30px;

    display:flex;
    justify-content:space-between;
    align-items:center;

    font-size:20px;
    font-weight:bold;
}

/* KIRI */
.header-left{
    flex:1;
}

/* TENGAH */
.header-center{
    flex:1;
    text-align:center;
    font-size:18px;
}

/* KANAN */
.header-right{
    flex:1;
    display:flex;
    justify-content:flex-end;
}

/* BUTTON DARK MODE */
.dark-btn{
    background:white;
    border:none;
    padding:8px 14px;
    border-radius:10px;
    cursor:pointer;
    font-size:18px;
}



/* WRAPPER CARD */
.cards{
    display:flex;
    gap:21px;
    flex-wrap:wrap;
    margin-top:20px;
}

/* SEMUA CARD */
.card{
    width:190px;
    height:100px;
    padding:19px;
    background:#9ACD32;
    color:black;
    border-radius:15px;
    text-align:center;
    display:flex;
    flex-direction:column;
    justify-content:center;
    transition:0.3s;
}

.cards-container{
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    margin-top: 40px;

}

/* HOVER */
.card:hover{
    transform:translateY(-5px);
    background:	#556B2F;

}
.card h1{
    margin:10px 0 0;
    font-size:20px;
    font-weight:bold;
}
.card-header p{
    margin:0;
    font-size:20px;
    font-weight:600;
}

.card-header i{
    font-size:18px;
}

.card-header{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:5px;
}
.card-header i{
    font-size:15px;
}


.dashboard-bottom{
    display:flex;
    gap:25px;
    margin-top:30px;
    align-items:flex-start;
}


.grafik-box{
    flex:2;
    background:white;
    padding:25px;
    border-radius:25px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

.box-transaksi{
    flex:1;
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.table-terbaru{
    width:100%;
    border-collapse:collapse;
}

.table-terbaru th{
    background:#008000;
    color:white;
    padding:10px;
}

.table-terbaru td{
    padding:10px;
    border-bottom:1px solid #ddd;
}

.card-box:hover{
    transform:translateY(-5px);
    transition:0.3s;
}


/* DARK MODE */
.dark{
    background: #121212;
    color:white;
}

.dark .content{
    background: #070425;
        color:white;
}

.dark .header{
    background: #0C0637;
    color:white;
}

.dark .sidebar{
    background: #0f172a;
        color:white;
}
.dark .sidebar a:hover{
    transform: translateY(-5px);
    background: #5D6D8E;;
    padding-left: 20px;
    border-left: 4px solid #60a5fa;
    color: #ffffff;
}

.dark .profile-sidebar{
    background: #0f172a;
    border-top:1px solid rgba(255,255,255,0.15);
}
  

.dark .welcome-box h1{ 
    margin:0 0 10px 0;
    font-size:38px;
    color:white;
}

.dark .welcome-box p{
    margin:0;
    font-size:18px;
    color:white;
    line-height:1.6;
}

.dark .card{
    background:	#5D6D8E;
    color:white;
}


.dark .welcome-banner,
.dark .grafik-box,
.dark .box-transaksi{
    background:	#2F4F4F;
    color:white;
}

.dark .welcome-box{
      background:linear-gradient(135deg,#2F4F4F,#30084A);
      color:white;
}

.dark .card:hover{
    transform:translateY(-5px);
    background:	#A9A9A9;
}
  

.dark .table-terbaru th{
    background: #170C6E;
    color:white;
    padding:10px;
}

.dark table{
    color:white;
}

.dark td{
    border-color:	#4169E1;
}

.dark tr:nth-child(even){
    background:	#262e48;
}


</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h3 class="sidebar-title">
    <img src="assets/gambar/Logo.png" class="menu-logo">
    <span>Menu</span>
</h3>

    <div class="menu-list">

        <a href="dashboard_kepala.php">🏠 Dashboard</a>

        <a href="datasiswa_kepsek.php">👤 Data Siswa</a>

 <a onclick="toggleLaporan()">
    📊 Laporan ▼
</a>

<div id="submenuLaporan" class="submenu">

    <a href="laporan.php">
        📚 Laporan Pembayaran
    </a>

    <a href="laporan_kelas.php">
        📚 Laporan Per Kelas
    </a>

</div>


    <!-- TRANSAKSI -->
    <a onclick="toggleMenu()">💰 Transaksi ▼</a>
    <div id="submenu" class="submenu">
        <a href="pembayaran_kepsek.php">📄 Pembayaran</a>
         <a href="riwayat_pembayaran.php">📄 Riwayat Pembayaran</a>
    </div>

        <a href="logout.php">🚪 Logout</a>

    </div>
 
<div class="profile-sidebar">

<img src="<?php
if($_SESSION['role'] == 'kepala sekolah'){
    echo 'assets/gambar/kepala_sekolah.jpg';
}else{
    echo 'assets/gambar/admin.jpg';
}
?>" class="profile-img-sidebar">

    <div class="profile-info">
        <?php echo $_SESSION['role']; ?>
    </div>


</div>

</div>






<!-- HEADER -->
<div class="header">

    <div class="header-left">
        Dashboard Kepala Sekolah
    </div>

    <div class="header-center">
        <span id="jam"></span>
    </div>

    <div class="header-right">
        <button onclick="darkMode()" class="dark-btn">
            🌙
        </button>
    </div>

</div>


<!-- CONTENT -->
<div class="content">

    <!-- WELCOME -->
    <div class="welcome-box">

        <h1>
            Hi, <?php echo $_SESSION['username']; ?> 👋
        </h1>

        <p>
            Selamat Datang
            <br>
            Silakan pilih menu di samping.
        </p>

    </div>

    <hr>

    <!-- CARD -->
<div class="cards-container">
<!-- Total Siswa -->
    <div class="card">
    <div class="card-header">
        <i class="fa-solid fa-user-graduate"></i>
        <p>Total Siswa</p>
    </div>

    <h1><?= $total_siswa; ?></h1>

</div>

        <!-- Total Transaksi -->
        <div class="card">
    <div class="card-header">
             <i class="fa-solid fa-money-bill-wave"></i>
            <p>Total Transaksi</p>
        </div>
            <h1>
                Rp <?= number_format($data_transaksi['total']) ?> </h1>
        </div>
        

        <!-- Total Hari Ini -->
        <div class="card">
            <div class="card-header">
                 <i class="fa-solid fa-calendar-day"></i>
            <p>Total Hari Ini</p>
            </div>
            <h1> Rp <?= number_format($data_hari_ini['total_hari']) ?> </h1>
        </div>



        <!-- Total Bulan Ini -->
        <div class="card">
             <div class="card-header">
                <i class="fa-solid fa-calendar-check"></i>  
            <p>Total Bulan Ini</p>
            </div>
            <h1>
                Rp <?= number_format($data_bulan_ini['total_bulan']) ?>
            </h1>
        </div>

    </div>

    <!--garfik-->
 <div class="dashboard-bottom">

    <div class="grafik-box">
        <h3>📊 Grafik Transaksi</h3>
        <canvas id="chartTransaksi"></canvas>
    </div>

<!--transaksi terbaru-->
    <div class="box-transaksi">

        <h3>🧾 Transaksi Terbaru</h3>

        <table class="table-terbaru">

        <tr>
            <th>Siswa</th>
            <th>Nominal</th>
        </tr>

        <?php while($t = mysqli_fetch_assoc($queryTerbaru)){ ?>

        <tr>
            <td><?= $t['Nama']; ?></td>

            <td>
                Rp <?= number_format($t['jumlah']); ?>
            </td>
        </tr>

        <?php } ?>

        </table>

    </div>

</div>





</script>




<?php while($t = mysqli_fetch_assoc($queryTerbaru)){ ?>

<tr>
    <td><?= $t['Nama']; ?></td>
    <td><?= $t['Kelas']; ?></td>
    <td>
        Rp <?= number_format($t['jumlah']); ?>
    </td>
</tr>

<?php } ?>

</table>

</div>


<!-- SCRIPT -->
<script>
function toggleMenu(){
    var menu = document.getElementById("submenu");
    if(menu.style.display === "block"){
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    }
}
</script>

<script>
function toggleLaporan(){
    var menu = document.getElementById("submenuLaporan");

    if(menu.style.display == "block"){
        menu.style.display = "none";
    }else{
        menu.style.display = "block";
    }
}
</script>

<script>
var ctx = document.getElementById('chartTransaksi').getContext('2d');

var chart = new Chart(ctx, {
    type: 'line',
   data: {
        labels: <?= json_encode($label_bulan); ?>,

        datasets: [{
            label: 'Pembayaran',

            data: <?= json_encode($data_total); ?>,

            borderColor: '#22c55e',

            backgroundColor: 'rgba(34,197,94,0.1)',

            tension: 0.4,

            fill: true,

            pointBackgroundColor:'#22c55e',

            pointBorderColor:'#fff',

            pointRadius:5,

            pointHoverRadius:7,

            borderWidth:3
        }]
    },

    options: {
        responsive:true,

        plugins:{
            legend:{
                display:false
            }
        },

        scales:{
            y:{
                beginAtZero:true,

                grid:{
                    color:'rgba(0,0,0,0.05)'
                },

                ticks:{
                    callback:function(value){
                        return 'Rp ' + value;
                    }
                }
            },

            x:{
                grid:{
                    display:false
                }
            }
        }
    }
});

</script>
</script> 


<script>

function darkMode(){

    document.body.classList.toggle("dark");

    if(document.body.classList.contains("dark")){
        localStorage.setItem("theme","dark");
        document.querySelector(".dark-btn").innerHTML = "☀️";
    }else{
        localStorage.setItem("theme","light");
        document.querySelector(".dark-btn").innerHTML = "🌙";
    }
}

/* otomatis aktif saat reload halaman */
if(localStorage.getItem("theme") === "dark"){

    document.body.classList.add("dark");

    window.onload = function(){
        let btn = document.querySelector(".dark-btn");

        if(btn){
            btn.innerHTML = "☀️";
        }
    }
}

</script>


<script>

function updateJam(){

    var sekarang = new Date();

    var hari = [
        "Minggu",
        "Senin",
        "Selasa",
        "Rabu",
        "Kamis",
        "Jumat",
        "Sabtu"
    ];

    var bulan = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    ];

    var namaHari = hari[sekarang.getDay()];
    var tanggal = sekarang.getDate();
    var namaBulan = bulan[sekarang.getMonth()];
    var tahun = sekarang.getFullYear();

    var jam = sekarang.getHours().toString().padStart(2,'0');
    var menit = sekarang.getMinutes().toString().padStart(2,'0');
    var detik = sekarang.getSeconds().toString().padStart(2,'0');

    document.getElementById("jam").innerHTML =
    namaHari + ", " +
    tanggal + " " +
    namaBulan + " " +
    tahun +
    " | " +
    jam + ":" + menit + ":" + detik;
}

setInterval(updateJam,1000);

updateJam();

</script>

</body>
</html>

