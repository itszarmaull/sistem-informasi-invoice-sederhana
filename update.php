<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    // 1. Update Header Surat
    $no_surat = $_POST['nomor_surat'];
    $lampiran = $_POST['lampiran'];
    $kepada   = $_POST['kepada'];
    $grand_total = $_POST['grand_total'];

    $stmt = $conn->prepare("UPDATE surat_pengajuan SET nomor_surat=?, lampiran=?, kepada=?, grand_total=? WHERE id=?");
    $stmt->bind_param("sssdi", $no_surat, $lampiran, $kepada, $grand_total, $id);
    $stmt->execute();

    // 2. Update Detail Barang (Cara Mudah: Hapus Lama -> Insert Baru)
    $conn->query("DELETE FROM detail_barang WHERE id_surat = $id");

    $jenis_barang = $_POST['jenis'];
    $ukuran       = $_POST['ukuran'];
    $qty_num      = $_POST['qty_num'];
    $qty_text     = $_POST['qty_text'];
    $harga        = $_POST['harga'];

    $stmt_detail = $conn->prepare("INSERT INTO detail_barang (id_surat, jenis_barang, ukuran, jumlah_barang, harga_satuan, harga_total) VALUES (?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < count($jenis_barang); $i++) {
        $gabung_qty = $qty_num[$i] . " " . $qty_text[$i];
        $total_row  = $qty_num[$i] * $harga[$i];
        
        $stmt_detail->bind_param("isssdd", $id, $jenis_barang[$i], $ukuran[$i], $gabung_qty, $harga[$i], $total_row);
        $stmt_detail->execute();
    }

    // Redirect kembali ke index
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Update Sukses!',
        'message' => 'Data penawaran berhasil diperbarui.'
    ];

    header("Location: index.php");
    exit();
    exit();
}
?>