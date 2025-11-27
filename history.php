<?php 
include 'config.php'; 

// --- LOGIC FILTER DATA ---
// 1. Dasar Query
$sql = "SELECT * FROM surat_pengajuan";
$conditions = [];

// 2. Cek Pencarian
$search_keyword = "";
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search_keyword = $_GET['q'];
    $safe_search = $conn->real_escape_string($search_keyword);
    $conditions[] = "(nomor_surat LIKE '%$safe_search%' OR kepada LIKE '%$safe_search%')";
}

// 3. Cek Filter Tanggal
$start_date = $_GET['start'] ?? '';
$end_date = $_GET['end'] ?? '';
if (!empty($start_date) && !empty($end_date)) {
    $conditions[] = "tanggal BETWEEN '$start_date' AND '$end_date'";
}

// 4. Gabungkan WHERE
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

// 5. Sorting Dropdown
$sort_option = $_GET['sort'] ?? 'newest';
switch ($sort_option) {
    case 'oldest': $sql .= " ORDER BY id ASC"; break;
    case 'expensive': $sql .= " ORDER BY grand_total DESC"; break;
    case 'cheap': $sql .= " ORDER BY grand_total ASC"; break;
    default: $sql .= " ORDER BY id DESC"; break; // Default Newest
}

// Eksekusi Query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Surat - Fajar Jaya</title>
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
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition"><i class="fa-solid fa-chart-line w-5"></i> <span class="font-medium">Dashboard</span></a>
                <a href="create.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition"><i class="fa-solid fa-plus-circle w-5"></i> <span class="font-medium">Buat Surat Baru</span></a>
                <a href="history.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white shadow-lg shadow-blue-500/30 rounded-xl transition"><i class="fa-solid fa-clock-rotate-left w-5"></i> <span class="font-medium">Riwayat Surat</span></a>
                <a href="settings.php" class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-slate-800 hover:text-white rounded-xl transition"><i class="fa-solid fa-user-gear w-5"></i> <span class="font-medium">Pengaturan Akun</span></a>
            </nav>
            <div class="p-4 border-t border-slate-700">
                <a href="logout.php" class="flex items-center gap-3 w-full px-4 py-3 text-red-400 hover:bg-slate-800 rounded-xl transition"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
            
            <header class="h-20 bg-white shadow-sm flex items-center justify-between px-6 lg:px-8 z-10">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-600 focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-slate-700">Data Riwayat Surat</h2>
                </div>
                
                <form action="" method="GET" class="hidden md:flex relative">
                    <input type="hidden" name="sort" value="<?= $sort_option ?>">
                    <input type="hidden" name="start" value="<?= $start_date ?>">
                    <input type="hidden" name="end" value="<?= $end_date ?>">
                    
                    <i class="fa-solid fa-search absolute left-3 top-3 text-slate-400"></i>
                    <input type="text" name="q" value="<?= htmlspecialchars($search_keyword) ?>" placeholder="Cari no surat / klien..." class="pl-10 pr-4 py-2 bg-slate-100 rounded-full text-sm focus:ring-2 focus:ring-blue-500 outline-none w-64 transition">
                </form>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-8 flex flex-col">
                
                <div class="flex flex-col lg:flex-row justify-between items-center mb-6 gap-4">
                    
                    <form id="filterForm" method="GET" class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                        <input type="hidden" name="q" value="<?= htmlspecialchars($search_keyword) ?>">
                        <input type="hidden" name="sort" value="<?= $sort_option ?>">

                        <div class="relative">
                            <button type="button" onclick="toggleDateFilter()" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-gray-50 shadow-sm flex items-center gap-2">
                                <i class="fa-regular fa-calendar"></i> Filter Tanggal
                            </button>
                            
                            <div id="datePopup" class="hidden absolute top-12 left-0 bg-white p-4 shadow-xl rounded-xl border border-slate-100 z-50 w-72">
                                <p class="text-xs font-bold text-slate-500 mb-2 uppercase">Pilih Rentang Tanggal</p>
                                <div class="space-y-3">
                                    <input type="date" name="start" value="<?= $start_date ?>" class="w-full p-2 border rounded text-sm">
                                    <input type="date" name="end" value="<?= $end_date ?>" class="w-full p-2 border rounded text-sm">
                                    <div class="flex justify-between">
                                        <a href="history.php" class="text-xs text-red-500 hover:underline mt-2">Reset</a>
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Terapkan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="export_excel.php?<?= http_build_query($_GET) ?>" target="_blank" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-green-700 hover:bg-green-50 shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-file-excel"></i> Export Excel
                        </a>
                    </form>

                    <div class="flex items-center gap-3 w-full lg:w-auto justify-end">
                        <label class="text-sm text-slate-500 hidden md:block">Urutkan:</label>
                        <select onchange="updateSort(this.value)" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <option value="newest" <?= $sort_option == 'newest' ? 'selected' : '' ?>>Terbaru</option>
                            <option value="oldest" <?= $sort_option == 'oldest' ? 'selected' : '' ?>>Terlama</option>
                            <option value="expensive" <?= $sort_option == 'expensive' ? 'selected' : '' ?>>Nilai Tertinggi</option>
                            <option value="cheap" <?= $sort_option == 'cheap' ? 'selected' : '' ?>>Nilai Terendah</option>
                        </select>

                        <a href="create.php" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg shadow-blue-500/30 transition flex items-center gap-2 ml-2">
                            <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Buat Baru</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex-1">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-xs border-b border-slate-100">
                                <tr>
                                    <th class="py-4 px-6 font-semibold">No Surat</th>
                                    <th class="py-4 px-6 font-semibold">Klien / Tujuan</th>
                                    <th class="py-4 px-6 font-semibold">Tanggal Dibuat</th>
                                    <th class="py-4 px-6 text-right font-semibold">Nilai Total</th>
                                    <th class="py-4 px-6 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                                <?php if($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                    <tr class="hover:bg-blue-50/50 transition duration-150 group">
                                        <td class="py-4 px-6 font-medium text-slate-800">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                                <?= htmlspecialchars($row['nomor_surat'] ?? '') ?>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 font-semibold"><?= htmlspecialchars($row['kepada'] ?? '') ?></td>
                                        <td class="py-4 px-6 text-slate-500">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-regular fa-calendar text-slate-400"></i>
                                                <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-right font-bold text-slate-700">
                                            Rp <?= number_format($row['grand_total'], 0, ',', '.') ?>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <div class="flex justify-center items-center gap-2 opacity-70 group-hover:opacity-100 transition">
                                                <a href="generate_pdf.php?id=<?= $row['id'] ?>" target="_blank" class="p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition" title="PDF">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                </a>
                                                <a href="edit.php?id=<?= $row['id'] ?>" class="p-2 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus permanen?')" class="p-2 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-700 hover:text-white transition" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400">
                                            <i class="fa-regular fa-folder-open text-4xl mb-3 block"></i>
                                            Data tidak ditemukan
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-auto pt-8">
                    <?php include 'footer.php'; ?>
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

       
        function toggleDateFilter() {
            document.getElementById('datePopup').classList.toggle('hidden');
        }

        function updateSort(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>