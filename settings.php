<?php
include 'config.php';

// Logic Update User
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_now = $_SESSION['login_user'];
    $new_user = $_POST['username'];
    $new_name = $_POST['nama_lengkap'];
    $new_pass = $_POST['password'];
    
    $query = "UPDATE users SET username='$new_user', nama_lengkap='$new_name'";
    
    if(!empty($new_pass)) {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $query .= ", password='$hash'";
    }
    
    $query .= " WHERE username='$user_now'";
    
    if($conn->query($query)) {
        $_SESSION['login_user'] = $new_user; 
        $_SESSION['nama_user'] = $new_name; // Update session nama
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Profil berhasil diperbarui!'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal', 'message' => 'Gagal update akun'];
    }
    header("Location: settings.php");
    exit;
}

// Ambil Data User Terbaru
$user_now = $_SESSION['login_user'];
$data_user = $conn->query("SELECT * FROM users WHERE username='$user_now'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Fajar Jaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100 text-slate-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <div id="sidebarBackdrop" class="fixed inset-0 bg-black/50 z-20 hidden md:hidden" onclick="toggleSidebar()"></div>
        
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-30 w-64 bg-slate-900 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col shadow-2xl">
            <div class="h-20 flex items-center justify-center border-b border-slate-700 bg-slate-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-xl font-bold">F</div>
                    <div>
                        <h1 class="font-bold text-lg tracking-wide">FAJAR JAYA</h1>
                        <p class="text-xs text-slate-400">Admin Dashboard</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition">
                    <i class="fa-solid fa-chart-line w-5"></i> <span class="font-medium">Dashboard</span>
                </a>
                <a href="create.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition">
                    <i class="fa-solid fa-plus-circle w-5"></i> <span class="font-medium">Buat Surat Baru</span>
                </a>
                <a href="history.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition">
                    <i class="fa-solid fa-clock-rotate-left w-5"></i> <span class="font-medium">Riwayat Surat</span>
                </a>
                <a href="settings.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white shadow-lg shadow-blue-500/30 rounded-xl transition">
                    <i class="fa-solid fa-user-gear w-5"></i> <span class="font-medium">Pengaturan Akun</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-700">
                <a href="logout.php" class="flex items-center gap-3 w-full px-4 py-3 text-red-400 hover:bg-slate-800 rounded-xl transition">
                    <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
            
            <header class="h-20 bg-white shadow-sm flex items-center justify-between px-6 lg:px-8 z-10">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-600 focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-slate-700">Pengaturan Akun</h2>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-8 flex flex-col">
                <div class="max-w-5xl mx-auto">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <div class="md:col-span-1">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                                <div class="relative mt-12 mb-4">
                                    <div class="w-24 h-24 mx-auto bg-white rounded-full p-1 shadow-lg">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($data_user['nama_lengkap']) ?>&background=0D8ABC&color=fff&size=128" alt="Profile" class="w-full h-full rounded-full object-cover">
                                    </div>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($data_user['nama_lengkap']) ?></h3>
                                <p class="text-slate-500 text-sm mb-4">@<?= htmlspecialchars($data_user['role']) ?></p>
                                
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold border border-blue-100">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    <span><?= htmlspecialchars($data_user['role']) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                                <h3 class="text-lg font-bold text-slate-700 mb-6 border-b pb-2">Edit Informasi Pribadi</h3>
                                
                                <form method="POST" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-600 mb-2">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data_user['nama_lengkap']) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-slate-50 focus:bg-white transition" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-600 mb-2">Username</label>
                                            <input type="text" name="username" value="<?= htmlspecialchars($data_user['username']) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-slate-50 focus:bg-white transition" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-600 mb-2">Role / Jabatan</label>
                                        <input type="text" value="<?= htmlspecialchars($data_user['role']) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-gray-100 text-gray-500 cursor-not-allowed" readonly title="Hubungi IT untuk mengganti role">
                                        <p class="text-xs text-slate-400 mt-1">*Role hanya bisa diubah oleh Database Administrator.</p>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100">
                                        <h4 class="text-sm font-bold text-slate-700 mb-4">Keamanan</h4>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-600 mb-2">Password Baru</label>
                                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti password" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-slate-50 focus:bg-white transition">
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-4">
                                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
               <div class="mt-auto pt-8"> <?php include 'footer.php'; ?>
</div>
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            // Toggle Translate X
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full'); 
                backdrop.classList.remove('hidden'); 
            } else {
                sidebar.classList.add('-translate-x-full'); 
                backdrop.classList.add('hidden'); 
            }
        }

        // SweetAlert Logic
        <?php if(isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['alert']['type'] ?>',
                title: '<?= $_SESSION['alert']['title'] ?>',
                text: '<?= $_SESSION['alert']['message'] ?>',
                timer: 2000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </script>
</body>
</html>