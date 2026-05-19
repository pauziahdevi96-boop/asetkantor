<?php
include '../koneksi.php';

// Ambil parameter filter
$filter_kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : '';
$filter_kondisi = isset($_GET['kondisi']) ? mysqli_real_escape_string($koneksi, $_GET['kondisi']) : '';
$filter_ruangan = isset($_GET['id_ruangan']) ? mysqli_real_escape_string($koneksi, $_GET['id_ruangan']) : '';
$filter_tahun = isset($_GET['tahun']) ? mysqli_real_escape_string($koneksi, $_GET['tahun']) : '';

// Bangun query where
$where = [];
if($filter_kategori != '') $where[] = "a.kategori = '$filter_kategori'";
if($filter_kondisi != '') $where[] = "a.kondisi = '$filter_kondisi'";
if($filter_ruangan != '') $where[] = "a.id_ruangan = '$filter_ruangan'";
if($filter_tahun != '') $where[] = "a.tahun_perolehan = '$filter_tahun'";

$where_clause = '';
if(count($where) > 0) {
    $where_clause = "WHERE " . implode(" AND ", $where);
}

// Query data aset dengan filter
$query = mysqli_query($koneksi, "SELECT a.*, r.nama_ruangan 
                                FROM aset a 
                                LEFT JOIN ruangan r ON a.id_ruangan = r.id_ruangan 
                                $where_clause 
                                ORDER BY a.created_at DESC");

// Hitung total nilai
$total_nilai = 0;
$aset_list = [];
while($row = mysqli_fetch_array($query)) {
    $total_nilai += $row['nilai_aset'];
    $aset_list[] = $row;
}

// Buat judul filter
$filter_text = [];
if($filter_kategori != '') $filter_text[] = "Kategori: $filter_kategori";
if($filter_kondisi != '') $filter_text[] = "Kondisi: $filter_kondisi";
if($filter_ruangan != '') {
    $ruang_query = mysqli_query($koneksi, "SELECT nama_ruangan FROM ruangan WHERE id_ruangan = '$filter_ruangan'");
    $ruang = mysqli_fetch_array($ruang_query);
    $filter_text[] = "Ruangan: " . ($ruang['nama_ruangan'] ?? $filter_ruangan);
}
if($filter_tahun != '') $filter_text[] = "Tahun: $filter_tahun";
$filter_display = implode(" | ", $filter_text);
if($filter_display == '') $filter_display = "Semua Data";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Aset</title>
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
            max-width: 1200px;
            margin: 0 auto;
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
        .filter-info {
            margin: 15px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        .date-info {
            text-align: right;
            font-size: 12px;
            margin-bottom: 15px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }
        table.data th, table.data td {
            border: 1px solid #000;
            padding: 6px;
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
        table.data td.text-left {
            text-align: left;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .total {
            margin-top: 15px;
            text-align: right;
            font-weight: bold;
            font-size: 12px;
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
        }
        button {
            padding: 8px 16px;
            margin: 10px;
            font-size: 14px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
        .print-buttons {
            text-align: center;
            margin-bottom: 20px;
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
            <h2>LAPORAN DATA ASET</h2>
        </div>
        
        <div class="date-info">
            Makassar, <?= date('d F Y') ?>
        </div>
        
        <div class="filter-info">
            <strong>Filter:</strong> <?= $filter_display ?>
        </div>
        
        <table class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Aset</th>
                    <th>Nama Aset</th>
                    <th>Kategori</th>
                    <th>Merk</th>
                    <th>Ruangan</th>
                    <th>Tahun</th>
                    <th>Kondisi</th>
                    <th>Nilai (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach($aset_list as $aset): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($aset['kode_aset']) ?></td>
                    <td class="text-left"><?= htmlspecialchars($aset['nama_aset']) ?></td>
                    <td><?= htmlspecialchars($aset['kategori']) ?></td>
                    <td><?= htmlspecialchars($aset['merk']) ?></td>
                    <td><?= htmlspecialchars($aset['nama_ruangan'] ?? 'Belum Ditentukan') ?></td>
                    <td><?= $aset['tahun_perolehan'] ?></td>
                    <td><?= htmlspecialchars($aset['kondisi']) ?></td>
                    <td style="text-align: right;"><?= number_format($aset['nilai_aset'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
                
                <?php if(count($aset_list) == 0): ?>
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data aset yang ditemukan</td>
                </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="background-color: #f0f0f0;">
                    <th colspan="8" style="text-align: right;">TOTAL NILAI ASET:</th>
                    <th style="text-align: right;">Rp <?= number_format($total_nilai, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
        
        <div class="total">
            <p>Jumlah Aset: <?= count($aset_list) ?> Item</p>
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
                <p>Petugas,</p>
                <div class="line"></div>
                <p>(_____________________)</p>
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