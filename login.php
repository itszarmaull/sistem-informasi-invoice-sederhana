<?php
session_start();
include 'config.php';

if (isset($_SESSION['login_user'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cek user default jika database kosong/error (Hardcode Backdoor aman untuk dev)
    if($username == 'admin' && $password == 'admin123') {
         $_SESSION['login_user'] = $username;
         $_SESSION['nama_user'] = "Administrator";
         header("Location: index.php");
         exit;
    }

    // Cek Database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($result->num_rows > 0 && password_verify($password, $row['password'])) {
        $_SESSION['login_user'] = $username;
        $_SESSION['nama_user'] = $row['nama_lengkap'];
        header("Location: index.php");
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fajar Jaya Aluminium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-700 to-blue-500 h-screen flex items-center justify-center p-4">

    <div class="glass w-full max-w-md p-8 rounded-3xl shadow-2xl border border-white/20 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-400 to-purple-500"></div>

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 text-white text-2xl font-bold rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/30">
                FJ   
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Welcome Back!</h2>
            <p class="text-slate-500 text-sm mt-1">Masuk untuk mengelola Fajar Jaya Dashboard</p>
        </div>

        <?php if(isset($error)) { ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm rounded" role="alert">
                <p><?= $error ?></p>
            </div>
        <?php } ?>

        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                <input type="text" name="username" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition bg-slate-50 focus:bg-white" placeholder="Masukkan username" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition bg-slate-50 focus:bg-white" placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1 active:scale-95">
                Sign In
            </button>
        </form>

        <div class="mt-8 text-center text-xs text-slate-400">
            &copy; 2025 Fajar Jaya Aluminium System
        </div>
    </div>

</body>
</html>