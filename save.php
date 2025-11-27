<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data header
    $no_surat = $_POST['nomor_surat'];
    $lampiran = $_POST['lampiran'];
    $kepada   = $_POST['kepada'];
    $grand_total = $_POST['grand_total'];
    $tanggal  = date('Y-m-d');

    // Insert Surat
    $stmt = $conn->prepare("INSERT INTO surat_pengajuan (nomor_surat, lampiran, kepada, tanggal, grand_total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $no_surat, $lampiran, $kepada, $tanggal, $grand_total);
    $stmt->execute();
    $surat_id = $stmt->insert_id;

    // Insert Detail Barang
    $jenis_barang = $_POST['jenis'];
    $ukuran       = $_POST['ukuran'];
    $qty_num      = $_POST['qty_num'];
    $qty_text     = $_POST['qty_text'];
    $harga        = $_POST['harga'];

    $stmt_detail = $conn->prepare("INSERT INTO detail_barang (id_surat, jenis_barang, ukuran, jumlah_barang, harga_satuan, harga_total) VALUES (?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < count($jenis_barang); $i++) {
        $gabung_qty = $qty_num[$i] . " " . $qty_text[$i]; // Contoh: 5 Daun
        $total_row  = $qty_num[$i] * $harga[$i];
        
        $stmt_detail->bind_param("isssdd", $surat_id, $jenis_barang[$i], $ukuran[$i], $gabung_qty, $harga[$i], $total_row);
        $stmt_detail->execute();
    }

    // Redirect ke PDF
    // Set Session Alert
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Berhasil!',
        'message' => 'Surat penawaran baru berhasil dibuat.'
    ];

    header("Location: index.php");
    exit();
    exit();
}
?>