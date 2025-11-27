<?php
include 'config.php';


header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data_Surat_Fajar_Jaya.xls");


$where_clause = "WHERE 1=1";

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search = $conn->real_escape_string($_GET['q']);
    $where_clause .= " AND (nomor_surat LIKE '%$search%' OR kepada LIKE '%$search%')";
}

if (isset($_GET['start']) && !empty($_GET['start']) && isset($_GET['end']) && !empty($_GET['end'])) {
    $start = $_GET['start'];
    $end = $_GET['end'];
    $where_clause .= " AND tanggal BETWEEN '$start' AND '$end'";
}

$order_sql = "ORDER BY id DESC"; 
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == 'oldest') $order_sql = "ORDER BY id ASC";
    elseif ($_GET['sort'] == 'expensive') $order_sql = "ORDER BY grand_total DESC";
    elseif ($_GET['sort'] == 'cheap') $order_sql = "ORDER BY grand_total ASC";
}

$query = "SELECT * FROM surat_pengajuan $where_clause $order_sql";
$result = $conn->query($query);
?>

<table border="1">
    <thead>
        <tr style="background-color: yellow; font-weight: bold;">
            <th>No</th>
            <th>Nomor Surat</th>
            <th>Klien / Tujuan</th>
            <th>Lampiran</th>
            <th>Tanggal</th>
            <th>Total Nilai (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while($row = $result->fetch_assoc()): 
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nomor_surat'] ?></td>
            <td><?= $row['kepada'] ?></td>
            <td><?= $row['lampiran'] ?></td>
            <td><?= $row['tanggal'] ?></td>
            <td><?= $row['grand_total'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>