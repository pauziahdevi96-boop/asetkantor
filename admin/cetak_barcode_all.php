<?php
include '../koneksi.php';

$query = mysqli_query($koneksi, "SELECT * FROM aset WHERE barcode IS NOT NULL AND barcode != '' ORDER BY kode_aset");
$aset_list = [];
while($row = mysqli_fetch_array($query)) {
    $aset_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Semua Barcode</title>
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
            padding: 15px;
            text-align: center;
            margin: 10px;
            display: inline-block;
            vertical-align: top;
            page-break-after: avoid;
            break-inside: avoid;
        }
        .barcode-card h3 {
            font-size: 12px;
            margin-bottom: 5px;
            color: #333;
        }
        .barcode-card .nama-aset {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .barcode-card .kode-aset {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
        .barcode-card img {
            max-width: 100%;
            height: auto;
        }
        .print-area {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
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
            margin-bottom: 20px;
        }
        .header-print {
            text-align: center;
            margin-bottom: 30px;
        }
        .header-print h1 {
            font-size: 18px;
        }
        .header-print p {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container-print">
        <button class="no-print" onclick="window.print()">🖨️ Cetak Semua Barcode</button>
        <button class="no-print" onclick="window.close()">❌ Tutup</button>
    </div>
    
    <div class="header-print no-print">
        <h1>DAFTAR BARCODE ASET</h1>
        <p>Kantor Gubernur Provinsi Sulawesi Selatan</p>
        <hr>
    </div>
    
    <div class="print-area">
        <?php foreach($aset_list as $aset): ?>
        <div class="barcode-card">
            <h3>KARTU INVENTARIS RUANGAN (KIR)</h3>
            <div class="nama-aset"><?= htmlspecialchars($aset['nama_aset']) ?></div>
            <div class="kode-aset">Kode: <?= htmlspecialchars($aset['kode_aset']) ?></div>
            <img src="../uploads/<?= $aset['barcode'] ?>" alt="Barcode <?= $aset['kode_aset'] ?>">
            <div class="kode-aset"><?= $aset['kode_aset'] ?></div>
        </div>
        <?php endforeach; ?>
        
        <?php if(count($aset_list) == 0): ?>
        <div class="alert alert-warning" style="width: 100%; text-align: center; padding: 50px;">
            Belum ada aset yang memiliki barcode. Silakan generate barcode terlebih dahulu.
        </div>
        <?php endif; ?>
    </div>
</body>
</html>