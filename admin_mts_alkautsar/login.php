<?php
ob_start();
session_start();
include "config.php";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if(empty($username) || empty($password) || empty($role)){

        echo "<script>alert('Semua harus diisi!')</script>";

    }else{

        $query = mysqli_query($conn,"
        SELECT * FROM admin
        WHERE username='$username'
        AND password='$password'
        AND role='$role'");

        if(mysqli_num_rows($query) > 0){

            $data = mysqli_fetch_assoc($query);

            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $data['role'];

    

            $role = strtolower(trim($data['role']));

            if($role == 'admin'){

                header("Location: dashboard.php");
                exit();

            }elseif($role == 'kepala sekolah'){

                header("Location: dashboard_kepala.php");
                exit();

            }

        }else{

            echo "<script>alert('Username / Password / Role salah')</script>";

        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Admin MTs Al-Kautsar</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(135deg, #119821, #119821);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

/* BOX LOGIN */
.login-box{
    background:white;
    padding:50px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    width:500px;
    text-align:center;
}

/* LOGO */
.Logo{
    width:90px;
    height:90px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:15px;
}

/* TITLE */
h2{
    margin-bottom:20px;
    color:#333;
}

/* INPUT */
input, select{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:6px;
    border:1px solid #ccc;
    box-sizing:border-box;

}

/* BUTTON */
button{
    width:100%;
    padding:10px;
    background:#4facfe;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#2d8cf0;
}


select{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:6px;
    border:1px solid #035114;
    font-size:15px;
    background:white;
    cursor:pointer;
    outline:none;
    transition:0.3s;
}

select:focus{
    border-color:#4facfe;
    box-shadow:0 0 5px rgba(79,172,254,0.5);
}

.password-box{
    position:relative;
}

.password-box input{
    width:100%;
    padding:10px;
    padding-right:40px;
    box-sizing:border-box;
}

.eye{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
}
</style>

</head>

<body>

<div class="login-box">

<img src="assets/gambar/Logo.png" class="Logo">

<h2>Login Sistem Sekolah</h2>


   <form method="POST">

<!-- LOGIN SEBAGAI -->
<select name="role" id="role" onchange="isiUsername()" required>
    <option value="">-- Login Sebagai --</option>
    <option value="Admin">Admin</option>
    <option value="Kepala Sekolah">Kepala Sekolah</option>
</select>

<input type="hidden" name="username" id="username">

<div class="password-box">
<input type="password" 
name="password" 
id="password"
placeholder="Password" 
required>

<span class="eye" onclick="lihatPassword()">
👁
</span>
</div>
<button type="submit" name="login">Login</button>

</form>

</div>
<script>
    
function isiUsername(){

    var role = document.getElementById("role").value;
    var username = document.getElementById("username");

    if(role == "Admin"){
        username.value = "Admin";
    }

    else if(role == "Kepala Sekolah"){
        username.value = "Kepala Sekolah";
    }

    else{
        username.value = "";
    }
}
</script>


<script>
function lihatPassword(){

    var pass = document.getElementById("password");

    if(pass.type == "password"){
        pass.type = "text";
    }else{
        pass.type = "password";
    }
}
</script>

</body>
</html>