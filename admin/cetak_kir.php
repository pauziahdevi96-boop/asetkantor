<?php
include '../koneksi.php';

$id_kir = isset($_GET['id_kir']) ? intval($_GET['id_kir']) : 0;
$id_ruangan = isset($_GET['id_ruangan']) ? intval($_GET['id_ruangan']) : 0;

if($id_kir > 0) {
    // Cetak berdasarkan ID KIR yang sudah tersimpan
    $query = mysqli_query($koneksi, "SELECT k.*, r.nama_ruangan, r.lokasi, r.kode_ruangan 
                                     FROM kir k 
                                     JOIN ruangan r ON k.id_ruangan = r.id_ruangan 
                                     WHERE k.id_kir = '$id_kir'");
    $kir = mysqli_fetch_array($query);
    
    $detail_query = mysqli_query($koneksi, "SELECT dk.*, a.kode_aset, a.nama_aset, a.kategori, a.merk, a.tahun_perolehan, a.nilai_aset
                                            FROM detail_kir dk 
                                            JOIN aset a ON dk.id_aset = a.id_aset 
                                            WHERE dk.id_kir = '$id_kir'");
} else if($id_ruangan > 0) {
    // Cetak langsung dari data aset saat ini
    $ruangan_query = mysqli_query($koneksi, "SELECT * FROM ruangan WHERE id_ruangan = '$id_ruangan'");
    $ruangan = mysqli_fetch_array($ruangan_query);
    
    $detail_query = mysqli_query($koneksi, "SELECT * FROM aset WHERE id_ruangan = '$id_ruangan'");
    $detail_aset = [];
    while($row = mysqli_fetch_array($detail_query)) {
        $detail_aset[] = $row;
    }
    
    $kir = [
        'nama_ruangan' => $ruangan['nama_ruangan'],
        'lokasi' => $ruangan['lokasi'],
        'kode_ruangan' => $ruangan['kode_ruangan'],
        'tanggal_cetak' => date('Y-m-d')
    ];
    $detail_aset = $detail_aset;
} else {
    die("Parameter tidak valid");
}

$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KIR - <?= htmlspecialchars($kir['nama_ruangan']) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Arial, sans-serif;
            background: #fff;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 16px;
        }
        .header h3 {
            font-size: 14px;
        }
        .header p {
            font-size: 12px;
        }
        .info-ruangan {
            margin: 20px 0;
            border: 1px solid #000;
            padding: 10px;
        }
        .info-ruangan table {
            width: 100%;
            font-size: 12px;
        }
        .info-ruangan td {
            padding: 5px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        table.data th, table.data td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table.data th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        table.data td {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .ttd {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .ttd div {
            text-align: center;
            width: 200px;
        }
        .ttd .line {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 100%;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
            .container {
                max-width: 100%;
                margin: 0;
            }
        }
        button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 14px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        .print-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        .total-aset {
            margin-top: 10px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="print-buttons no-print">
        <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        <button onclick="window.close()">❌ Tutup</button>
    </div>
    
    <div class="container">
        <div class="header">
            <h1>PEMERINTAH PROVINSI SULAWESI SELATAN</h1>
            <h2>KANTOR GUBERNUR SULAWESI SELATAN</h2>
            <h3>BIRO UMUM DAN PERLENGKAPAN</h3>
            <p>Jl. Urip Sumoharjo No. 1, Makassar 90231 Telp. (0411) 1234567</p>
            <hr>
            <h2>KARTU INVENTARIS RUANGAN (KIR)</h2>
        </div>
        
        <div class="info-ruangan">
            <table>
                <tr><td width="150">Nama Ruangan</td><td width="10">:</td><td><b><?= htmlspecialchars($kir['nama_ruangan']) ?></b></td></tr>
                <tr><td>Kode Ruangan</td><td>:</td><td><?= htmlspecialchars($kir['kode_ruangan'] ?? '-') ?></td></tr>
                <tr><td>Lokasi</td><td>:</td><td><?= htmlspecialchars($kir['lokasi']) ?></td></tr>
                <tr><td>Tanggal Cetak</td><td>:</td><td><?= date('d/m/Y', strtotime($kir['tanggal_cetak'])) ?></td></tr>
            </table>
        </div>
        
        <table class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Aset</th>
                    <th>Nama Aset</th>
                    <th>Kategori</th>
                    <th>Merk/Tipe</th>
                    <th>Tahun Perolehan</th>
                    <th>Kondisi</th>
                    <th>Nilai (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_nilai = 0;
                if($id_kir > 0):
                    while($aset = mysqli_fetch_array($detail_query)): 
                        $total_nilai += $aset['nilai_aset'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($aset['kode_aset']) ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($aset['nama_aset']) ?></td>
                    <td><?= htmlspecialchars($aset['kategori']) ?></td>
                    <td><?= htmlspecialchars($aset['merk']) ?></td>
                    <td><?= $aset['tahun_perolehan'] ?></td>
                    <td><?= htmlspecialchars($aset['kondisi_saat_cetak'] ?? $aset['kondisi']) ?></td>
                    <td style="text-align: right;"><?= number_format($aset['nilai_aset'], 0, ',', '.') ?></td>
                </tr>
                <?php 
                    endwhile;
                else:
                    foreach($detail_aset as $aset): 
                        $total_nilai += $aset['nilai_aset'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($aset['kode_aset']) ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($aset['nama_aset']) ?></td>
                    <td><?= htmlspecialchars($aset['kategori']) ?></td>
                    <td><?= htmlspecialchars($aset['merk']) ?></td>
                    <td><?= $aset['tahun_perolehan'] ?></td>
                    <td><?= htmlspecialchars($aset['kondisi']) ?></td>
                    <td style="text-align: right;"><?= number_format($aset['nilai_aset'], 0, ',', '.') ?></td>
                </tr>
                <?php 
                    endforeach;
                endif;
                
                if($no == 1):
                ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada aset di ruangan ini</td>
                </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="background-color: #f0f0f0;">
                    <th colspan="7" style="text-align: right;">TOTAL NILAI ASET:</th>
                    <th style="text-align: right;">Rp <?= number_format($total_nilai, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
        
        <div class="total-aset">
            <p>Jumlah Aset: <?= $no - 1 ?> Item</p>
        </div>
        
        <div class="ttd">
            <div>
                <p>Mengetahui,</p>
                <p>Kepala Biro Umum</p>
                <div class="line"></div>
                <p>(_____________________)</p>
                <p>NIP. ________________</p>
            </div>
            <div>
                <p>Makassar, <?= date('d F Y') ?></p>
                <p>Petugas Inventaris</p>
                <div class="line"></div>
                <p><?= $_SESSION['nama_user'] ?? '_____________________' ?></p>
                <p>NIP. ________________</p>
            </div>
        </div>
        
        <div class="footer">
            <p>Dokumen ini dicetak dari sistem SIPATRIA (Sistem Pendataan Aset & Kartu Inventaris Ruangan)</p>
            <p>Kantor Gubernur Provinsi Sulawesi Selatan</p>
        </div>
    </div>
</body>
</html>