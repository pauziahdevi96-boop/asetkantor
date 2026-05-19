<?php
include '../koneksi.php';

$id_aset = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = mysqli_query($koneksi, "SELECT * FROM aset WHERE id_aset = '$id_aset'");
$aset = mysqli_fetch_array($query);

if(!$aset) {
    die("Aset tidak ditemukan");
}

$barcode_file = "../uploads/" . $aset['barcode'];
$file_exists = file_exists($barcode_file) && !empty($aset['barcode']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak QR Code - <?= htmlspecialchars($aset['nama_aset']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .barcode-card {
            width: 350px;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 0 auto;
            page-break-after: avoid;
        }
        .barcode-card h3 {
            margin-bottom: 10px;
            font-size: 14px;
            color: #333;
        }
        .barcode-card .nama-aset {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }
        .barcode-card .kode-aset {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
        }
        .barcode-card img {
            max-width: 200px;
            height: auto;
            margin: 10px auto;
            display: block;
        }
        .print-area {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
            .barcode-card {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
        button {
            padding: 10px 20px;
            margin: 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        .container-print {
            text-align: center;
        }
        .qr-placeholder {
            background: #f0f0f0;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
        }
        .warning {
            color: #856404;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 10px;
            border-radius: 5px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container-print">
        <button class="no-print" onclick="window.print()">🖨️ Cetak QR Code</button>
        <button class="no-print" onclick="window.close()">❌ Tutup</button>
    </div>
    
    <div class="print-area">
        <div class="barcode-card">
            <h3>KARTU INVENTARIS RUANGAN (KIR)</h3>
            <h3>KANTOR GUBERNUR PROVINSI SULAWESI SELATAN</h3>
            <div class="nama-aset"><?= htmlspecialchars($aset['nama_aset']) ?></div>
            <div class="kode-aset">Kode: <?= htmlspecialchars($aset['kode_aset']) ?></div>
            
            <?php if($file_exists && strpos($aset['barcode'], '.png') !== false): ?>
                <img src="<?= $barcode_file ?>" alt="QR Code <?= $aset['kode_aset'] ?>">
            <?php elseif($file_exists): ?>
                <div class="qr-placeholder">
                    <p>⚠️ Format file tidak sesuai</p>
                    <p>Harap generate ulang QR Code</p>
                </div>
            <?php else: ?>
                <div class="warning">
                    <p>⚠️ QR Code belum digenerate</p>
                    <p>Silakan generate QR Code terlebih dahulu di menu Generate Barcode</p>
                </div>
            <?php endif; ?>
            
            <div class="kode-aset"><?= $aset['kode_aset'] ?></div>
            <hr>
            <small>Scan QR Code untuk melihat detail aset</small>
        </div>
    </div>
</body>
</html>