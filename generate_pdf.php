<?php
// HAPUS SEMUA SPASI SEBELUM TAG PHP INI
ob_start(); // MULAI BUFFER

require 'vendor/autoload.php';
include 'config.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'];
$query = $conn->query("SELECT * FROM surat_pengajuan WHERE id = $id");
$surat = $query->fetch_assoc();

$query_items = $conn->query("SELECT * FROM detail_barang WHERE id_surat = $id");


$pathLogo = 'logosfj.jpg';
$typeLogo = pathinfo($pathLogo, PATHINFO_EXTENSION);
$base64Logo = '';
if (file_exists($pathLogo)) {
    $dataLogo = file_get_contents($pathLogo);
    $base64Logo = 'data:image/' . $typeLogo . ';base64,' . base64_encode($dataLogo);
}

$pathTtd = 'ttd.png';
$typeTtd = pathinfo($pathTtd, PATHINFO_EXTENSION);
$base64Ttd = '';
if (file_exists($pathTtd)) {
    $dataTtd = file_get_contents($pathTtd);
    $base64Ttd = 'data:image/' . $typeTtd . ';base64,' . base64_encode($dataTtd);
}


$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 2.5cm; }
        body { font-family: "Arial", sans-serif; font-size: 11pt; line-height: 1.5; }
        .header-table { width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-img { width: 90px; height: auto; }
        .company-name { font-size: 18pt; font-weight: bold; text-transform: uppercase; }
        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { padding: 2px 0; vertical-align: top; }
        .recipient-block { margin-top: 10px; margin-bottom: 20px; text-align: left; }
        .content-table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 10pt; }
        .content-table th { border: 1px solid #000; padding: 8px 5px; background-color: #ffff00; text-align: center; font-weight: bold; }
        .content-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; }
        .total-value { background-color: #ffff00; font-weight: bold; text-align: right; white-space: nowrap; }
        .footer-table { width: 100%; margin-top: 50px; }
        .ttd-column { text-align: center; padding-right: 10px; }
        .ttd-img { width: 120px; height: auto; margin: 10px 0; }
        .nama-ttd { font-weight: bold; text-decoration: underline; font-size: 11pt; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="15%" align="left"><img src="' . $base64Logo . '" class="logo-img"></td>
            <td width="85%" align="center">
                <div class="company-name">FAJAR JAYA ALUMINIUM</div>
                <div style="font-size: 11pt; font-weight: bold; margin-bottom: 5px;">Spesialis Aluminium & Kaca</div>
                <div style="font-size: 9pt; line-height: 1.3;">
                    Jl. BSD Bintaro No.6, Lengkong Karya, Kec. Serpong Utara,<br>
                    Kota Tangerang Selatan, Banten 15310<br>
                    Tlp. 0858-1355-6864 &nbsp;|&nbsp; E-mail : pajarjayaaluminium12@gmail.com
                </div>
            </td>
        </tr>
    </table>

    <table class="meta-table">
        <tr><td width="15%">Nomor</td><td width="2%">:</td><td>' . $surat['nomor_surat'] . '</td></tr>
        <tr><td>Lampiran</td><td>:</td><td>' . $surat['lampiran'] . '</td></tr>
    </table>

    <div class="recipient-block">
        Kepada Yth :<br>Bapak/Ibu <b>' . $surat['kepada'] . '</b><br>Di Tempat
    </div>

    <p>Dengan hormat,</p>
    <p>Kami dari <b>FAJAR JAYA ALUMINIUM</b> yang bergerak di bidang Spesialis Kaca dan Aluminium bermaksud untuk memberikan penawaran Pengerjaan Kusen Aluminium dan Kaca kepada Bapak/Ibu. Adapun daftar uraian pekerjaan sebagai berikut :</p>

    <table class="content-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Jenis Barang</th>
                <th width="15%">Ukuran</th>
                <th width="15%">Jumlah</th>
                <th width="20%">Harga Satuan</th>
                <th width="20%">Harga Total</th>
            </tr>
        </thead>
        <tbody>';

        $no = 1;
        while($item = $query_items->fetch_assoc()){
            $html .= '
            <tr>
                <td align="center">'. $no++ .'</td>
                <td>'. $item['jenis_barang'] .'</td>
                <td align="center">'. $item['ukuran'] .'</td>
                <td align="center">'. $item['jumlah_barang'] .'</td>
                <td align="right">Rp. '. number_format($item['harga_satuan'],0,',','.') .'</td>
                <td align="right">Rp. '. number_format($item['harga_total'],0,',','.') .'</td>
            </tr>';
        }

$html .= '
            <tr>
                <td colspan="5" align="center" style="font-weight:bold;">Jumlah Total</td>
                <td class="total-value">Rp. '. number_format($surat['grand_total'],0,',','.') .'</td>
            </tr>
        </tbody>
    </table>

    <p>Demikian surat Pengajuan Harga ini kami buat dengan harapan akan terjadi kemitraan yang menguntungkan antara kami dan pihak Bapak/Ibu. Jika ada pertanyaan, silahkan menghubungi kami melalui nomor telepon atau E-mail kami.</p>
    <p>Atas perhatian serta kerja samanya, Kami Sampaikan Banyak Terimakasih.</p>

    <table class="footer-table">
        <tr>
            <td width="70%"></td>
            <td width="30%" class="ttd-column">
                <p style="margin: 0;">Hormat Saya,</p>
                <img src="' . $base64Ttd . '" class="ttd-img">
                <div class="nama-ttd">YASIR</div>
            </td>
        </tr>
    </table>
</body>
</html>';


ob_end_clean(); 

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Surat_Penawaran_" . str_replace('/','_', $surat['nomor_surat']) . ".pdf", ["Attachment" => 0]);
?>