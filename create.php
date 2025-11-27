<?php
include 'config.php';


$query = $conn->query("SELECT nomor_surat FROM surat_pengajuan ORDER BY id DESC LIMIT 1");
$last_surat = $query->fetch_assoc();

$bulan = date('n');
$romawi = array("", "I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
$bln_romawi = $romawi[$bulan];
$tahun = date('Y');

if ($last_surat) {
    $parts = explode('/', $last_surat['nomor_surat']);
    $last_number = intval($parts[0]);
    $new_number = $last_number + 1;
} else {
    $new_number = 1;
}
$nomor_baru = sprintf("%03d", $new_number) . "/PJ/" . $bln_romawi . "/" . $tahun;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Buat Penawaran Baru - Fajar Jaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
   
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        
     
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-100 selection:text-blue-700">

    <div class="flex h-screen overflow-hidden">
        
        <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity" onclick="toggleSidebar()"></div>
        
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col shadow-2xl border-r border-slate-800">
            <div class="h-20 flex items-center justify-center border-b border-slate-800 bg-slate-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-blue-400 rounded-xl flex items-center justify-center text-xl font-bold text-white shadow-lg shadow-blue-500/20">F</div>
                    <div>
                        <h1 class="font-bold text-lg tracking-wide text-white">FAJAR JAYA</h1>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Admin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition duration-200 group">
                    <i class="fa-solid fa-chart-pie w-5"></i> <span class="font-medium">Dashboard</span>
                </a>
                <a href="create.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white shadow-lg shadow-blue-900/50 rounded-xl transition duration-200">
                    <i class="fa-solid fa-file-circle-plus w-5"></i> <span class="font-medium">Buat Surat Baru</span>
                </a>
                <a href="history.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition duration-200 group">
                    <i class="fa-solid fa-clock-rotate-left w-5"></i> <span class="font-medium">Riwayat Surat</span>
                </a>
                <a href="settings.php" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition duration-200 group">
                    <i class="fa-solid fa-sliders w-5"></i> <span class="font-medium">Pengaturan</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <a href="logout.php" class="flex items-center gap-3 w-full px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-xl transition duration-200">
                    <i class="fa-solid fa-power-off"></i> <span>Keluar</span>
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
            
            <header class="h-16 md:h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 z-30 sticky top-0">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-500 hover:text-slate-700 focus:outline-none p-2 -ml-2">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-lg md:text-xl font-bold text-slate-800 truncate">Buat Penawaran</h2>
                </div>
                <a href="index.php" class="text-xs md:text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 transition">
                    <i class="fa-solid fa-arrow-left"></i> <span class="hidden sm:inline">Batal</span>
                </a>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-8 pb-32"> <form action="save.php" method="POST" class="max-w-6xl mx-auto space-y-6">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-4 md:px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="font-bold text-slate-700 text-sm md:text-base flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-blue-500"></i> Informasi Dasar
                            </h3>
                        </div>
                        
                        <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                            <div class="group">
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nomor Surat</label>
                                <input type="text" name="nomor_surat" value="<?= $nomor_baru ?>" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 font-medium text-sm focus:bg-white focus:border-blue-500 outline-none transition-all cursor-not-allowed" readonly >
                            </div>
                            <div class="group">
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Lampiran</label>
                                <input type="text" name="lampiran" value="Pengajuan Harga" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium text-sm focus:border-blue-500 outline-none transition-all" required>
                            </div>
                            <div class="group">
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tujuan (Klien)</label>
                                <input type="text" name="kepada" placeholder="Nama PT / Bapak / Ibu" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-medium text-sm focus:border-blue-500 outline-none transition-all" required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                        <div class="px-4 md:px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center sticky top-0 z-10">
                            <h3 class="font-bold text-slate-700 text-sm md:text-base flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-green-500"></i> Item Pekerjaan
                            </h3>
                            <button type="button" id="addRowBtn" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs md:text-sm font-semibold hover:bg-blue-700 transition shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> Tambah
                            </button>
                        </div>

                        <div class="p-0">
                            <table class="w-full text-left text-sm" id="itemTable">
                                <thead class="hidden md:table-header-group bg-slate-50 text-slate-500 uppercase font-semibold border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-3 w-12 text-center">#</th>
                                        <th class="px-4 py-3">Deskripsi</th>
                                        <th class="px-4 py-3 w-32">Ukuran</th>
                                        <th class="px-4 py-3 w-32">Qty</th>
                                        <th class="px-4 py-3 w-40">Harga (@)</th>
                                        <th class="px-6 py-3 w-40 text-right">Total</th>
                                        <th class="px-4 py-3 w-12"></th>
                                    </tr>
                                </thead>
                                
                                <tbody id="tableBody" class="block md:table-row-group p-4 md:p-0 space-y-4 md:space-y-0">
                                    <tr class="group block md:table-row bg-white md:bg-transparent border md:border-b border-slate-200 rounded-xl md:rounded-none shadow-sm md:shadow-none p-4 md:p-0 relative">
                                        
                                        <div class="md:hidden absolute top-4 left-4 w-6 h-6 rounded-full bg-slate-100 text-slate-500 text-xs flex items-center justify-center font-bold row-index-mobile">1</div>

                                        <td class="hidden md:table-cell px-6 py-3 text-center text-slate-400 row-index">1</td>
                                        
                                        <td class="block md:table-cell px-0 md:px-4 py-2 md:py-3 mt-6 md:mt-0">
                                            <label class="md:hidden text-[10px] font-bold text-slate-400 uppercase mb-1 block">Deskripsi Barang</label>
                                            <input type="text" name="jenis[]" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm" placeholder="Nama Barang" required>
                                        </td>

                                        <td class="block md:table-cell px-0 md:px-4 py-0 md:py-3">
                                            <div class="grid grid-cols-2 gap-3 md:block">
                                                <div class="py-2 md:py-0">
                                                    <label class="md:hidden text-[10px] font-bold text-slate-400 uppercase mb-1 block">Ukuran</label>
                                                    <input type="text" name="ukuran[]" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm mb-2" placeholder="ex: 2x3" required>
                                                </div>
                                                <div class="py-2 md:py-0">
                                                    <label class="md:hidden text-[10px] font-bold text-slate-400 uppercase mb-1 block">Jumlah</label>
                                                    <div class="flex gap-1">
                                                        <input type="number" name="qty_num[]" class="w-1/2 px-2 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-center qty-input text-sm" value="1" required>
                                                        <input type="text" name="qty_text[]" class="w-1/2 px-2 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm" placeholder="Unit" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="block md:table-cell px-0 md:px-4 py-2 md:py-3">
                                            <label class="md:hidden text-[10px] font-bold text-slate-400 uppercase mb-1 block">Harga Satuan</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-slate-400 text-sm">Rp</span>
                                                <input type="number" name="harga[]" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none price-input text-sm font-medium" placeholder="0" required>
                                            </div>
                                        </td>

                                        <td class="block md:table-cell px-0 md:px-6 py-2 md:py-3">
                                            <div class="flex justify-between items-center md:block">
                                                <div class="md:text-right">
                                                    <label class="md:hidden text-[10px] font-bold text-slate-400 uppercase block">Subtotal</label>
                                                    <input type="text" name="total[]" class="w-full bg-transparent border-none text-left md:text-right font-bold text-slate-700 total-input p-0 text-sm" value="Rp 0" readonly>
                                                </div>
                                                <button type="button" class="md:hidden text-red-500 p-2 remove-row">
                                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                                </button>
                                            </div>
                                        </td>

                                        <td class="hidden md:table-cell px-4 py-3 text-center">
                                            <button type="button" class="text-slate-300 hover:text-red-500 transition remove-row">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 md:static md:bg-transparent md:border-0 md:p-0 z-40">
                        <div class="max-w-6xl mx-auto flex flex-row justify-between items-center gap-4">
                            <div>
                                <p class="text-xs text-slate-500 uppercase font-bold">Grand Total</p>
                                <input type="text" id="grandTotalDisplay" class="text-xl md:text-3xl font-bold text-blue-600 bg-transparent border-none p-0 w-auto focus:ring-0" readonly value="Rp 0">
                                <input type="hidden" name="grand_total" id="grandTotalInput">
                            </div>
                            
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition flex items-center gap-2 text-sm md:text-base">
                                <i class="fa-solid fa-print"></i> <span class="hidden sm:inline">Simpan & </span>PDF
                            </button>
                        </div>
                    </div>
                    
                    <div class="h-20 md:hidden"></div>

                </form>
                
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

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('tableBody');
            const addRowBtn = document.getElementById('addRowBtn');

            function updateTotals() {
                let grandTotal = 0;
                const rows = tableBody.querySelectorAll('tr'); 
                rows.forEach((row, index) => {
                 
                    const desktopIndex = row.querySelector('.row-index');
                    const mobileIndex = row.querySelector('.row-index-mobile');
                    
                    if(desktopIndex) desktopIndex.textContent = index + 1;
                    if(mobileIndex) mobileIndex.textContent = index + 1;

                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const total = qty * price;

                    row.querySelector('.total-input').value = 'Rp ' + total.toLocaleString('id-ID');
                    grandTotal += total;
                });
                document.getElementById('grandTotalDisplay').value = 'Rp ' + grandTotal.toLocaleString('id-ID');
                document.getElementById('grandTotalInput').value = grandTotal;
            }

          
            tableBody.addEventListener('input', function(e) {
                if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                    updateTotals();
                }
            });

            tableBody.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    const row = e.target.closest('tr');
                    if(tableBody.querySelectorAll('tr').length > 1) {
                        row.remove();
                        updateTotals();
                    } else {
                        // Optional: Clear inputs if trying to delete last row
                        row.querySelectorAll('input').forEach(input => {
                            if(input.type === 'number' || input.classList.contains('price-input')) input.value = ''; 
                            else if(input.classList.contains('total-input')) input.value = 'Rp 0';
                            else if(input.classList.contains('qty-input')) input.value = '1'; 
                            else input.value = '';
                        });
                        updateTotals();
                    }
                }
            });

            addRowBtn.addEventListener('click', function() {
               
                const firstRow = tableBody.querySelector('tr');
                const newRow = firstRow.cloneNode(true);
                
     
                newRow.querySelectorAll('input').forEach(input => {
                    if(input.type === 'number' || input.classList.contains('price-input')) input.value = ''; 
                    else if(input.classList.contains('total-input')) input.value = 'Rp 0';
                    else if(input.classList.contains('qty-input')) input.value = '1'; 
                    else input.value = '';
                });
                
                tableBody.appendChild(newRow);
                updateTotals();
            });

            updateTotals();
        });
    </script>
</body>
</html>