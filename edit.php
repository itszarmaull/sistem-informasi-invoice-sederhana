<?php
include 'config.php';

$id = $_GET['id'];

$q_surat = $conn->query("SELECT * FROM surat_pengajuan WHERE id = $id");
$surat = $q_surat->fetch_assoc();

$q_barang = $conn->query("SELECT * FROM detail_barang WHERE id_surat = $id");
$items = [];
while ($row = $q_barang->fetch_assoc()) {
    preg_match('/(\d+)\s*(.*)/', $row['jumlah_barang'], $matches);
    $row['qty_num'] = isset($matches[1]) ? $matches[1] : 1;
    $row['qty_text'] = isset($matches[2]) ? $matches[2] : '';
    $items[] = $row;
}
$jsonItems = json_encode($items);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Surat - Fajar Jaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100 pb-20">

    <div class="max-w-5xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-xl border border-slate-200">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h2 class="text-2xl font-bold text-slate-800">Edit Penawaran</h2>
            <a href="index.php" class="text-slate-500 hover:text-slate-700">Kembali</a>
        </div>

        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?= $surat['id'] ?>">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Nomor Surat</label>
                    <input type="text" name="nomor_surat" value="<?= $surat['nomor_surat'] ?>" class="w-full p-2 border rounded-lg bg-slate-50" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Lampiran</label>
                    <input type="text" name="lampiran" value="<?= $surat['lampiran'] ?>" class="w-full p-2 border rounded-lg bg-slate-50" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Kepada Yth</label>
                    <input type="text" name="kepada" value="<?= $surat['kepada'] ?>" class="w-full p-2 border rounded-lg bg-slate-50" required>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="font-semibold text-slate-700 mb-3">Daftar Barang</h3>
                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="bg-slate-100 text-slate-700 uppercase">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Jenis Barang</th>
                                <th class="px-4 py-3">Ukuran</th>
                                <th class="px-4 py-3">Jml (Ket)</th>
                                <th class="px-4 py-3">Harga Satuan</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3 text-center"><i class="fa fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            </tbody>
                    </table>
                </div>
                <button type="button" id="addRowBtn" class="mt-3 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded shadow-md transition">+ Tambah Baris</button>
            </div>

            <div class="flex justify-end items-center gap-4 mb-8">
                <span class="text-lg font-bold text-slate-700">Grand Total:</span>
                <input type="text" id="grandTotalDisplay" class="text-2xl font-bold text-blue-600 bg-transparent border-none text-right w-64" readonly>
                <input type="hidden" name="grand_total" id="grandTotalInput">
            </div>

            <div class="text-right border-t pt-6">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg">Update Data</button>
            </div>
        </form>
    </div>

    <script>
        const tableBody = document.getElementById('tableBody');
        const addRowBtn = document.getElementById('addRowBtn');
        
        // Data lama dari PHP
        const existingItems = <?= $jsonItems ?>;

        function createRow(data = null) {
            const row = document.createElement('tr');
            row.className = 'border-b hover:bg-slate-50';
            row.innerHTML = `
                <td class="px-4 py-2 text-center row-index">1</td>
                <td class="px-4 py-2"><input type="text" name="jenis[]" value="${data ? data.jenis_barang : ''}" class="w-full p-1 border rounded" required></td>
                <td class="px-4 py-2"><input type="text" name="ukuran[]" value="${data ? data.ukuran : ''}" class="w-full p-1 border rounded" required></td>
                <td class="px-4 py-2 flex gap-1">
                    <input type="number" name="qty_num[]" value="${data ? data.qty_num : '1'}" class="w-16 p-1 border rounded qty-input" min="1" required>
                    <input type="text" name="qty_text[]" value="${data ? data.qty_text : 'Unit'}" class="w-20 p-1 border rounded" required>
                </td>
                <td class="px-4 py-2"><input type="number" name="harga[]" value="${data ? data.harga_satuan : ''}" class="w-full p-1 border rounded price-input" required></td>
                <td class="px-4 py-2"><input type="text" class="w-full p-1 bg-slate-100 font-bold text-slate-700 total-input text-right" readonly></td>
                <td class="px-4 py-2 text-center">
                    <button type="button" class="text-red-500 hover:text-red-700 remove-row">×</button>
                </td>
            `;
            
            // Delete Action
            row.querySelector('.remove-row').addEventListener('click', function() {
                if(tableBody.rows.length > 1) {
                    row.remove();
                    updateTotals();
                }
            });

            return row;
        }

        function updateTotals() {
            let grandTotal = 0;
            const rows = tableBody.querySelectorAll('tr');
            rows.forEach((row, index) => {
                row.querySelector('.row-index').textContent = index + 1;
                
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const total = qty * price;

                row.querySelector('.total-input').value = 'Rp ' + total.toLocaleString('id-ID');
                grandTotal += total;
            });
            document.getElementById('grandTotalDisplay').value = 'Rp ' + grandTotal.toLocaleString('id-ID');
            document.getElementById('grandTotalInput').value = grandTotal;
        }

        if(existingItems.length > 0) {
            existingItems.forEach(item => {
                tableBody.appendChild(createRow(item));
            });
        } else {
            tableBody.appendChild(createRow()); 
        }
        updateTotals();


        addRowBtn.addEventListener('click', () => {
            tableBody.appendChild(createRow());
            updateTotals();
        });

        tableBody.addEventListener('input', (e) => {
            if(e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                updateTotals();
            }
        });
    </script>
</body>
</html>