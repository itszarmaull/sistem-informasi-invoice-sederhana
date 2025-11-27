<?php 
include 'config.php'; 

// --- DATA STATISTIK ---
$total_surat = $conn->query("SELECT COUNT(*) as total FROM surat_pengajuan")->fetch_assoc()['total'];
$total_omset = $conn->query("SELECT SUM(grand_total) as total FROM surat_pengajuan")->fetch_assoc()['total'];
$bulan_ini = date('m');
$surat_bulan_ini = $conn->query("SELECT COUNT(*) as total FROM surat_pengajuan WHERE MONTH(tanggal) = '$bulan_ini'")->fetch_assoc()['total'];

// --- CHART DATA ---
$tahun_ini = date('Y');
$chart_data = [];
for($i=1; $i<=12; $i++){
    $res = $conn->query("SELECT SUM(grand_total) as total FROM surat_pengajuan WHERE MONTH(tanggal) = '$i' AND YEAR(tanggal) = '$tahun_ini'")->fetch_assoc();
    $chart_data[] = $res['total'] ? $res['total'] : 0;
}
$json_chart_data = json_encode($chart_data);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Fajar Jaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased">

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
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white shadow-lg shadow-blue-500/30 rounded-xl transition">
                    <i class="fa-solid fa-chart-line w-5"></i> <span class="font-medium">Dashboard</span>
                </a>
                <a href="create.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition">
                    <i class="fa-solid fa-plus-circle w-5"></i> <span class="font-medium">Buat Surat Baru</span>
                </a>
                <a href="history.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition">
                    <i class="fa-solid fa-clock-rotate-left w-5"></i> <span class="font-medium">Riwayat Surat</span>
                </a>
                <a href="settings.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition">
                    <i class="fa-solid fa-user-gear w-5"></i> <span class="font-medium">Pengaturan Akun</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-700">
                <a href="logout.php" class="flex items-center gap-3 w-full px-4 py-3 text-red-400 hover:bg-slate-800 rounded-xl transition">
                    <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            
            <header class="h-20 bg-white shadow-sm flex items-center justify-between px-6 lg:px-8 z-10">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-600 focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-slate-700">Overview</h2>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-700"><?= $_SESSION['nama_user'] ?? 'Admin' ?></p>
                        <p class="text-xs text-slate-500"><?= $_SESSION['role'] ?? 'User' ?></p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold border-2 border-white shadow">
                        <?= substr($_SESSION['login_user'] ?? 'A', 0, 1) ?>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 lg:p-8 flex flex-col">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase">Total Surat</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1"><?= $total_surat ?></h3>
                        <div class="mt-2 h-1 w-full bg-blue-100 rounded-full"><div class="h-1 bg-blue-500 rounded-full" style="width: 70%"></div></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase">Omset Total</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp <?= number_format($total_omset/1000000, 1, ',', '.') ?> Jt</h3>
                        <div class="mt-2 h-1 w-full bg-green-100 rounded-full"><div class="h-1 bg-green-500 rounded-full" style="width: 50%"></div></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase">Bulan Ini</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1"><?= $surat_bulan_ini ?> Surat</h3>
                        <div class="mt-2 h-1 w-full bg-orange-100 rounded-full"><div class="h-1 bg-orange-500 rounded-full" style="width: 30%"></div></div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-8">
                    <h3 class="font-bold text-lg text-slate-700 mb-4">Statistik Penawaran (<?= date('Y') ?>)</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="omsetChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100"><h3 class="font-bold text-lg text-slate-700">Riwayat Surat Terakhir</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                                <tr>
                                    <th class="py-4 px-6 font-semibold">No Surat</th>
                                    <th class="py-4 px-6 font-semibold">Klien</th>
                                    <th class="py-4 px-6 font-semibold">Tanggal</th>
                                    <th class="py-4 px-6 text-right font-semibold">Nilai Proyek</th>
                                    <th class="py-4 px-6 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                                <?php
                                $result = $conn->query("SELECT * FROM surat_pengajuan ORDER BY id DESC LIMIT 10"); 
                                while($row = $result->fetch_assoc()):
                                ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-4 px-6 font-medium text-slate-800"><?= htmlspecialchars($row['nomor_surat']) ?></td>
                                    <td class="py-4 px-6"><?= htmlspecialchars($row['kepada']) ?></td>
                                    <td class="py-4 px-6"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                    <td class="py-4 px-6 text-right font-bold text-slate-700">Rp <?= number_format($row['grand_total'], 0, ',', '.') ?></td>
                                    <td class="py-4 px-6 text-center flex justify-center gap-2">
                                        <a href="generate_pdf.php?id=<?= $row['id'] ?>" target="_blank" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-file-pdf fa-lg"></i></a>
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="text-yellow-500 hover:text-yellow-700"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                                        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-trash fa-lg"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
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
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        // SWEETALERT & CHART
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

        const ctx = document.getElementById('omsetChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Omset',
                    data: <?= $json_chart_data ?>,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4] },
                        ticks: {
                            callback: function(value) { 
                                if(value >= 1000000) return (value/1000000).toFixed(0) + ' Jt';
                                return value;
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>