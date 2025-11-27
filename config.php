<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['login_user']) && $current_page != 'login.php') {
    header("Location: login.php");
    exit;
}

$host = 'localhost:3310'; 
$user = 'root';
$pass = '';
$db   = 'db_surat_alumunium';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Koneksi gagal");
?>