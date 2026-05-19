<?php

include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Petugas Inventaris
cek_role('Petugas Inventaris');

$message = '';
$aset_data = null;

// Proses scan barcode (input manual atau dari kamera)
if(isset($_POST['scan'])) {
    $barcode = mysqli_real_escape_string($koneksi, trim($_POST['barcode']));
    
    if(!empty($barcode)) {
        // Cari aset berdasarkan barcode
        $stmt = $koneksi->prepare("SELECT a.*, r.nama_ruangan 
                                   FROM aset a 
                                   LEFT JOIN ruangan r ON a.id_ruangan = r.id_ruangan 
                                   WHERE a.barcode = ? OR a.kode_aset = ?");
        $stmt->bind_param("ss", $barcode, $barcode);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $aset_data = $result->fetch_assoc();
            $message = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>✅ Aset ditemukan!</div>';
            
            // Catat log scan
            $log_stmt = $koneksi->prepare("INSERT INTO scan_log (kode_aset, id_user, lokasi_scan) VALUES (?, ?, 'Mobile Scan')");
            $log_stmt->bind_param("si", $aset_data['kode_aset'], $_SESSION['id_user']);
            $log_stmt->execute();
        } else {
            $message = '<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>❌ Barcode tidak ditemukan! Pastikan barcode sudah digenerate.</div>';
        }
    } else {
        $message = '<div class="alert alert-warning"><i class="fas fa-exclamation-circle me-2"></i>⚠️ Kode barcode tidak boleh kosong.</div>';
    }
}

// Proses update kondisi aset
if(isset($_POST['update_kondisi'])) {
    $id_aset    = mysqli_real_escape_string($koneksi, $_POST['id_aset']);
    $kondisi    = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    $stmt = $koneksi->prepare("UPDATE aset SET kondisi = ?, updated_at = NOW() WHERE id_aset = ?");
    $stmt->bind_param("si", $kondisi, $id_aset);
    $update = $stmt->execute();

    if($update) {
        // Catat log update
        $log_stmt = $koneksi->prepare("INSERT INTO scan_log (kode_aset, id_user, lokasi_scan) 
                                       VALUES ((SELECT kode_aset FROM aset WHERE id_aset = ?), ?, ?)");
        $log_msg = "Update Kondisi: $kondisi" . (!empty($keterangan) ? " - $keterangan" : "");
        $log_stmt->bind_param("iis", $id_aset, $_SESSION['id_user'], $log_msg);
        $log_stmt->execute();

        $message = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>✅ Kondisi aset berhasil diperbarui!</div>';

        // Refresh data aset
        $stmt2 = $koneksi->prepare("SELECT a.*, r.nama_ruangan FROM aset a LEFT JOIN ruangan r ON a.id_ruangan = r.id_ruangan WHERE a.id_aset = ?");
        $stmt2->bind_param("i", $id_aset);
        $stmt2->execute();
        $aset_data = $stmt2->get_result()->fetch_assoc();
    } else {
        $message = '<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>❌ Gagal memperbarui kondisi aset!</div>';
    }
}

// Proses update lokasi aset (mutasi)
if(isset($_POST['update_lokasi'])) {
    $id_aset           = mysqli_real_escape_string($koneksi, $_POST['id_aset']);
    $id_ruangan_tujuan = mysqli_real_escape_string($koneksi, $_POST['id_ruangan']);

    // Ambil ruangan asal
    $stmt = $koneksi->prepare("SELECT id_ruangan, kode_aset FROM aset WHERE id_aset = ?");
    $stmt->bind_param("i", $id_aset);
    $stmt->execute();
    $aset_lama       = $stmt->get_result()->fetch_assoc();
    $id_ruangan_asal = $aset_lama['id_ruangan'];

    // Update lokasi aset
    $stmt2 = $koneksi->prepare("UPDATE aset SET id_ruangan = ?, updated_at = NOW() WHERE id_aset = ?");
    $stmt2->bind_param("ii", $id_ruangan_tujuan, $id_aset);
    $update = $stmt2->execute();

    if($update) {
        // Catat mutasi
        $mut_stmt = $koneksi->prepare("INSERT INTO mutasi_aset (id_aset, id_ruangan_asal, id_ruangan_tujuan, tanggal_mutasi, keterangan) 
                                       VALUES (?, ?, ?, CURDATE(), 'Mutasi oleh petugas')");
        $mut_stmt->bind_param("iii", $id_aset, $id_ruangan_asal, $id_ruangan_tujuan);
        $mut_stmt->execute();

        // Catat log
        $log_stmt = $koneksi->prepare("INSERT INTO scan_log (kode_aset, id_user, lokasi_scan) VALUES (?, ?, 'Mutasi Ruangan')");
        $log_stmt->bind_param("si", $aset_lama['kode_aset'], $_SESSION['id_user']);
        $log_stmt->execute();

        $message = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>✅ Lokasi aset berhasil diperbarui!</div>';

        // Refresh data aset
        $stmt3 = $koneksi->prepare("SELECT a.*, r.nama_ruangan FROM aset a LEFT JOIN ruangan r ON a.id_ruangan = r.id_ruangan WHERE a.id_aset = ?");
        $stmt3->bind_param("i", $id_aset);
        $stmt3->execute();
        $aset_data = $stmt3->get_result()->fetch_assoc();
    } else {
        $message = '<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>❌ Gagal memperbarui lokasi aset!</div>';
    }
}

// Ambil data ruangan untuk dropdown mutasi
$query_ruangan = mysqli_query($koneksi, "SELECT * FROM ruangan ORDER BY nama_ruangan");

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Scan Barcode | SIPATRIA - Petugas</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- WebFont -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- html5-qrcode CDN -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
      /* ─── Scan Box ─────────────────────────────────────── */
      .scan-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        text-align: center;
        box-shadow: 0 8px 25px rgba(102,126,234,0.35);
      }
      .scan-box input {
        font-size: 18px;
        text-align: center;
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      }
      .scan-box input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255,255,255,0.4);
      }

      /* ─── Kamera Scanner ───────────────────────────────── */
      #camera-section {
        display: none;
        margin-top: 20px;
        animation: fadeIn .3s ease;
      }
      #reader {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        background: #000;
      }
      /* Override html5-qrcode UI */
      #reader video {
        border-radius: 12px !important;
      }
      #reader__scan_region {
        background: transparent !important;
      }
      #reader__dashboard_section_csr button {
        background: rgba(255,255,255,0.2) !important;
        color: white !important;
        border: 1px solid rgba(255,255,255,0.5) !important;
        border-radius: 6px !important;
        padding: 6px 14px !important;
        cursor: pointer;
      }
      #reader__dashboard_section_swaplink {
        color: rgba(255,255,255,0.8) !important;
      }

      /* ─── Status kamera ────────────────────────────────── */
      #scan-status {
        font-size: 13px;
        margin-top: 8px;
        min-height: 20px;
      }

      /* ─── Tombol kamera ────────────────────────────────── */
      .btn-camera {
        border: 2px solid rgba(255,255,255,0.7);
        color: white;
        background: rgba(255,255,255,0.15);
        border-radius: 8px;
        transition: all .2s;
      }
      .btn-camera:hover {
        background: rgba(255,255,255,0.3);
        color: white;
      }

      /* ─── Detail aset ──────────────────────────────────── */
      .aset-detail {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 24px;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
      }
      .aset-detail h5 {
        color: #444;
        font-weight: 600;
      }
      .table th {
        background-color: #eef2ff;
        color: #555;
        font-weight: 600;
      }

      /* ─── Badge kondisi ────────────────────────────────── */
      .badge-kondisi {
        font-size: 13px;
        padding: 6px 14px;
        border-radius: 20px;
      }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
      }
    </style>
  </head>

  <body>
    <div class="wrapper">

      <!-- ─── Sidebar ──────────────────────────────────────── -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <div class="logo-header" data-background-color="dark">
            <a href="index.php" class="logo">
                <h5 class="text-white ms-3">SIPATRIA</h5>
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item">
                <a href="index.php">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Data</h4>
              </li>
              <li class="nav-item">
                <a href="aset.php">
                  <i class="fas fa-boxes"></i>
                  <p>Data Aset</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="ruangan.php">
                  <i class="fas fa-building"></i>
                  <p>Data Ruangan</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Inventaris</h4>
              </li>
              <li class="nav-item active">
                <a href="scan_barcode.php">
                  <i class="fas fa-qrcode"></i>
                  <p>Scan Barcode</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="kir.php">
                  <i class="fas fa-file-alt"></i>
                  <p>Kartu Inventaris (KIR)</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Laporan</h4>
              </li>
              <li class="nav-item">
                <a href="laporan_aset.php">
                  <i class="fas fa-chart-bar"></i>
                  <p>Laporan Aset</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- ─── End Sidebar ──────────────────────────────────── -->

      <div class="main-panel">
        <!-- ─── Navbar ──────────────────────────────────────── -->
        <div class="main-header">
          <div class="main-header-logo">
            <div class="logo-header" data-background-color="dark">
              <a href="index.php" class="logo">
                <h5 class="text-white ms-3">SIPATRIA</h5>
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
          </div>
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item">
                  <span class="nav-link text-muted">
                    <i class="fas fa-user-circle me-1"></i>
                    <?= htmlspecialchars($_SESSION['nama'] ?? 'Petugas') ?>
                  </span>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../logout.php">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                  </a>
                </li>
              </ul>
            </div>
          </nav>
        </div>
        <!-- ─── End Navbar ──────────────────────────────────── -->

        <div class="container">
          <div class="page-inner">

            <!-- Breadcrumb & Judul -->
            <div class="page-header">
              <h3 class="fw-bold mb-1">Scan Barcode Aset</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="index.php"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Scan Barcode</a></li>
              </ul>
            </div>

            <!-- Alert pesan -->
            <?= $message ?>

            <!-- ─── Form Scan Barcode ───────────────────────── -->
            <div class="row mb-4">
              <div class="col-md-12">
                <div class="scan-box">
                  <h4 class="fw-bold mb-1">
                    <i class="fas fa-qrcode me-2"></i>Scan Barcode Aset
                  </h4>
                  <p class="mb-3 opacity-75">Masukkan kode barcode secara manual atau gunakan kamera smartphone</p>

                  <form method="POST" action="" id="formScan">
                    <!-- ✅ FIX: Hidden input 'scan' agar $_POST['scan'] selalu ada saat submit via JS -->
                    <input type="hidden" name="scan" id="hiddenScan" value="1">
                    <div class="row justify-content-center g-2">
                      <div class="col-md-6 col-10">
                        <input
                          type="text"
                          name="barcode"
                          id="inputBarcode"
                          class="form-control form-control-lg"
                          placeholder="Kode Barcode / Kode Aset"
                          autofocus
                          required
                          autocomplete="off"
                        >
                      </div>
                      <div class="col-md-2 col-5">
                        <button type="submit" class="btn btn-light btn-lg w-100 fw-semibold">
                          <i class="fas fa-search me-1"></i> Cari
                        </button>
                      </div>
                      <div class="col-md-2 col-5">
                        <button type="button" class="btn btn-camera btn-lg w-100 fw-semibold" id="btnKamera">
                          <i class="fas fa-camera me-1"></i> Kamera
                        </button>
                      </div>
                    </div>
                  </form>

                  <!-- Area kamera scanner -->
                  <div id="camera-section">
                    <div id="reader"></div>
                    <div id="scan-status" class="text-white-50">Arahkan kamera ke barcode / QR Code aset...</div>
                    <button type="button" class="btn btn-sm btn-outline-light mt-2" id="btnStopKamera">
                      <i class="fas fa-stop-circle me-1"></i> Hentikan Kamera
                    </button>
                  </div>

                  <p class="mt-3 mb-0 small opacity-75">
                    <i class="fas fa-info-circle me-1"></i>
                    Bisa menggunakan kode aset atau scan barcode yang sudah dicetak. Kamera memerlukan HTTPS / localhost.
                  </p>
                </div>
              </div>
            </div>

            <!-- ─── Detail Aset (tampil jika ditemukan) ──────── -->
            <?php if($aset_data): ?>
            <div class="row">
              <div class="col-md-12">
                <div class="aset-detail">

                  <h4 class="fw-bold mb-3">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Detail Aset
                  </h4>
                  <hr>

                  <!-- Tabel info aset -->
                  <div class="row">
                    <div class="col-md-6">
                      <table class="table table-bordered table-sm">
                        <tr>
                          <th width="42%">Kode Aset</th>
                          <td><code><?= htmlspecialchars($aset_data['kode_aset']) ?></code></td>
                        </tr>
                        <tr>
                          <th>Nama Aset</th>
                          <td><?= htmlspecialchars($aset_data['nama_aset']) ?></td>
                        </tr>
                        <tr>
                          <th>Kategori</th>
                          <td><?= htmlspecialchars($aset_data['kategori']) ?></td>
                        </tr>
                        <tr>
                          <th>Merk / Model</th>
                          <td><?= htmlspecialchars($aset_data['merk']) ?></td>
                        </tr>
                        <tr>
                          <th>Tahun Perolehan</th>
                          <td><?= htmlspecialchars($aset_data['tahun_perolehan']) ?></td>
                        </tr>
                      </table>
                    </div>
                    <div class="col-md-6">
                      <table class="table table-bordered table-sm">
                        <tr>
                          <th width="42%">Ruangan Saat Ini</th>
                          <td><?= htmlspecialchars($aset_data['nama_ruangan'] ?? 'Belum Ditentukan') ?></td>
                        </tr>
                        <tr>
                          <th>Kondisi</th>
                          <td>
                            <?php
                              $kondisi = $aset_data['kondisi'];
                              $badge   = match($kondisi) {
                                'Baik'        => 'bg-success',
                                'Rusak Ringan' => 'bg-warning text-dark',
                                default        => 'bg-danger',
                              };
                            ?>
                            <span class="badge <?= $badge ?> badge-kondisi"><?= htmlspecialchars($kondisi) ?></span>
                          </td>
                        </tr>
                        <tr>
                          <th>Nilai Aset</th>
                          <td>Rp <?= number_format($aset_data['nilai_aset'], 0, ',', '.') ?></td>
                        </tr>
                      </table>
                    </div>
                  </div>

                  <!-- ─── Update Kondisi Aset ────────────────── -->
                  <div class="mt-4">
                    <h5><i class="fas fa-edit me-2 text-warning"></i>Update Kondisi Aset</h5>
                    <form method="POST" action="" class="mt-2">
                      <input type="hidden" name="id_aset" value="<?= (int)$aset_data['id_aset'] ?>">
                      <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                          <label class="form-label small fw-semibold">Kondisi</label>
                          <select name="kondisi" class="form-control" required>
                            <option value="Baik"         <?= $aset_data['kondisi'] === 'Baik'         ? 'selected' : '' ?>>Baik</option>
                            <option value="Rusak Ringan" <?= $aset_data['kondisi'] === 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                            <option value="Rusak Berat"  <?= $aset_data['kondisi'] === 'Rusak Berat'  ? 'selected' : '' ?>>Rusak Berat</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small fw-semibold">Keterangan <span class="text-muted">(opsional)</span></label>
                          <input type="text" name="keterangan" class="form-control"
                                 placeholder="Contoh: Layar retak, tombol tidak berfungsi, dsb.">
                        </div>
                        <div class="col-md-3">
                          <button type="submit" name="update_kondisi" class="btn btn-warning w-100 fw-semibold">
                            <i class="fas fa-save me-1"></i> Simpan Kondisi
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>

                  <!-- ─── Mutasi Ruangan ─────────────────────── -->
                  <div class="mt-4">
                    <h5><i class="fas fa-exchange-alt me-2 text-info"></i>Pindahkan ke Ruangan Lain</h5>
                    <form method="POST" action="" class="mt-2">
                      <input type="hidden" name="id_aset" value="<?= (int)$aset_data['id_aset'] ?>">
                      <div class="row g-2 align-items-end">
                        <div class="col-md-6">
                          <label class="form-label small fw-semibold">Ruangan Tujuan</label>
                          <select name="id_ruangan" class="form-control" required>
                            <option value="">-- Pilih Ruangan Tujuan --</option>
                            <?php
                              mysqli_data_seek($query_ruangan, 0);
                              while($ruangan = mysqli_fetch_assoc($query_ruangan)):
                                $isCurrentRoom = ($aset_data['id_ruangan'] == $ruangan['id_ruangan']);
                            ?>
                              <option value="<?= (int)$ruangan['id_ruangan'] ?>"
                                      <?= $isCurrentRoom ? 'disabled' : '' ?>>
                                <?= htmlspecialchars($ruangan['nama_ruangan']) ?>
                                <?= $isCurrentRoom ? ' (Lokasi Saat Ini)' : '' ?>
                              </option>
                            <?php endwhile; ?>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <button type="submit" name="update_lokasi"
                                  class="btn btn-info w-100 fw-semibold text-white"
                                  onclick="return confirm('Yakin ingin memindahkan aset ini?')">
                            <i class="fas fa-arrows-alt me-1"></i> Pindahkan
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>

                </div><!-- /.aset-detail -->
              </div>
            </div>
            <?php endif; ?>

          </div><!-- /.page-inner -->
        </div><!-- /.container -->

        <!-- ─── Footer ──────────────────────────────────────── -->
        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="#">SIPATRIA</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Help</a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2026, made with <i class="fa fa-heart heart text-danger"></i> by
              <a href="#">Fauziah Deviani Imani Halim</a>
            </div>
            <div>Sistem Pendataan Aset &amp; KIR</div>
          </div>
        </footer>

      </div><!-- /.main-panel -->
    </div><!-- /.wrapper -->

    <!-- ─── Scripts ─────────────────────────────────────────── -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>

    <script>
      // ─── Kamera Scanner (html5-qrcode) ──────────────────────
      let html5QrCode = null;
      let scannerAktif = false;

      const btnKamera     = document.getElementById('btnKamera');
      const btnStop       = document.getElementById('btnStopKamera');
      const cameraSection = document.getElementById('camera-section');
      const inputBarcode  = document.getElementById('inputBarcode');
      const scanStatus    = document.getElementById('scan-status');
      const formScan      = document.getElementById('formScan');

      // Aktifkan kamera
      btnKamera.addEventListener('click', function () {
        if (scannerAktif) return;

        // Periksa dukungan browser
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
          alert('Browser ini tidak mendukung akses kamera. Gunakan Chrome/Firefox terbaru di HTTPS.');
          return;
        }

        cameraSection.style.display = 'block';
        btnKamera.disabled = true;
        btnKamera.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memuat...';
        scanStatus.textContent = 'Memulai kamera...';

        html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start(
          { facingMode: "environment" },   // kamera belakang (rear camera)
          {
            fps: 12,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            disableFlip: false,
          },

          // ✅ FIX: Callback sukses — pastikan $_POST['scan'] ada & gunakan .finally() agar submit selalu terjadi
          function (decodedText, decodedResult) {
            // Isi input barcode dengan hasil scan
            inputBarcode.value = decodedText;
            scanStatus.textContent = '✅ Barcode terdeteksi: ' + decodedText;

            // Hentikan kamera, lalu submit form
            if (html5QrCode && scannerAktif) {
              html5QrCode.stop()
                .catch(function (err) {
                  console.warn('Stop kamera gagal:', err);
                })
                .finally(function () {
                  html5QrCode    = null;
                  scannerAktif   = false;
                  cameraSection.style.display = 'none';
                  btnKamera.disabled = false;
                  scanStatus.textContent = '';
                  // Submit form — hidden input #hiddenScan sudah ada di form
                  formScan.submit();
                });
            } else {
              formScan.submit();
            }
          },

          // Callback error frame (normal, abaikan)
          function (errorMessage) { /* scanning */ }

        ).then(function () {
          scannerAktif = true;
          btnKamera.innerHTML = '<i class="fas fa-camera me-1"></i> Kamera';
          scanStatus.textContent = 'Arahkan kamera ke barcode / QR Code aset...';
        }).catch(function (err) {
          cameraSection.style.display = 'none';
          btnKamera.disabled = false;
          btnKamera.innerHTML = '<i class="fas fa-camera me-1"></i> Kamera';
          scanStatus.textContent = '';

          if (err.name === 'NotAllowedError') {
            alert('Akses kamera ditolak. Silakan izinkan akses kamera di browser Anda.');
          } else if (err.name === 'NotFoundError') {
            alert('Kamera tidak ditemukan pada perangkat ini.');
          } else {
            alert('Gagal mengakses kamera: ' + err.message);
          }
        });
      });

      // Hentikan kamera
      btnStop.addEventListener('click', function () {
        hentikanKamera(null);
      });

      function hentikanKamera(callback) {
        if (html5QrCode && scannerAktif) {
          html5QrCode.stop().then(function () {
            html5QrCode = null;
            scannerAktif = false;
            cameraSection.style.display = 'none';
            btnKamera.disabled = false;
            scanStatus.textContent = '';
            if (typeof callback === 'function') callback();
          }).catch(function (err) {
            console.warn('Stop kamera gagal:', err);
            if (typeof callback === 'function') callback();
          });
        } else {
          if (typeof callback === 'function') callback();
        }
      }

      // Hentikan kamera jika user meninggalkan halaman
      window.addEventListener('beforeunload', function () {
        hentikanKamera(null);
      });

      // Auto-focus ke input saat halaman dimuat
      document.addEventListener('DOMContentLoaded', function () {
        inputBarcode.focus();
      });

      // Konfirmasi sebelum update lokasi (jika belum ada inline onclick)
      document.querySelectorAll('form [name="update_lokasi"]').forEach(function(btn) {
        btn.addEventListener('click', function (e) {
          if (!confirm('Yakin ingin memindahkan aset ke ruangan yang dipilih?')) {
            e.preventDefault();
          }
        });
      });
    </script>

  </body>
</html>