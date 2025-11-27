<?php

$host = 'localhost:3310'; 
$user = 'root';
$pass = '';
$db   = 'db_surat_alumunium';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}


$password_baru = 'admin123';
$hash_resmi = password_hash($password_baru, PASSWORD_DEFAULT);


$username_target = 'yasir';


$cek = $conn->query("SELECT * FROM users WHERE username = '$username_target'");

if ($cek->num_rows > 0) {
   
    $sql = "UPDATE users SET password = '$hash_resmi' WHERE username = '$username_target'";
    
    if ($conn->query($sql)) {
        echo "<h1>✅ SUKSES!</h1>";
        echo "Password user <b>$username_target</b> berhasil di-reset.<br>";
        echo "Password baru: <b>admin123</b><br><br>";
        echo "<a href='login.php'>Klik disini untuk Login</a>";
    } else {
        echo "Gagal update database: " . $conn->error;
    }
} else {
    echo "<h1>❌ GAGAL!</h1>";
    echo "Username <b>$username_target</b> tidak ditemukan.<br>";
    echo "Coba cek di PHPMyAdmin apakah tulisannya 'yasir' atau 'Yasir' (huruf besar berpengaruh).";
}
?>