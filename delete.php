<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM surat_pengajuan WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Terhapus!',
            'message' => 'Data surat berhasil dihapus dari sistem.'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Gagal!',
            'message' => 'Terjadi kesalahan saat menghapus data.'
        ];
    }
    header("Location: index.php");
}
?>