<?php

include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// ─── Load Library QR Code ────────────────────────────────────────────────────
// Pastikan Composer autoload tersedia
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

// Deteksi versi endroid/qr-code yang terinstall
// v3 : Builder::create()->...->build()
// v4+ : (new Builder(...))->build()   ← yang benar untuk v4/v5
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

// ─── Fungsi Generate QR Code (kompatibel v3, v4, v5) ────────────────────────
function generateQRCode($text, $filename) {
    // Buat folder jika belum ada
    $folder = dirname($filename);
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    try {
        // Deteksi versi berdasarkan keberadaan class
        if (!class_exists('Endroid\\QrCode\\Writer\\PngWriter')) {
            throw new Exception("Library endroid/qr-code tidak ditemukan. Jalankan: composer require endroid/qr-code");
        }

        // === endroid/qr-code v4 / v5 ===
        // API: new QrCode($text) + PngWriter->write()
        if (class_exists('Endroid\\QrCode\\QrCode')) {
            $qrCode = new QrCode($text);

            // Set error correction level (v4/v5 pakai Enum atau string)
            if (method_exists($qrCode, 'setErrorCorrectionLevel')) {
                if (class_exists('Endroid\\QrCode\\ErrorCorrectionLevel')) {
                    // v4 pakai class enum
                    $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
                }
            }

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $result->saveToFile($filename);
            return true;
        }

        // === endroid/qr-code v3 (fallback) ===
        // API: Builder::create()->...->build()
        if (class_exists('Endroid\\QrCode\\Builder\\Builder')) {
            $builderClass = 'Endroid\\QrCode\\Builder\\Builder';
            $writerClass  = 'Endroid\\QrCode\\Writer\\PngWriter';

            $result = $builderClass::create()
                ->writer(new $writerClass())
                ->data($text)
                ->encoding('UTF-8')
                ->size(300)
                ->margin(10)
                ->build();

            $result->saveToFile($filename);
            return true;
        }

        throw new Exception("Versi endroid/qr-code tidak dikenali.");

    } catch (Exception $e) {
        error_log("QR Code generation error: " . $e->getMessage());
        return false;
    }
}

// ─── Cek apakah library tersedia ────────────────────────────────────────────
function libraryTersedia() {
    return class_exists('Endroid\\QrCode\\QrCode') ||
           class_exists('Endroid\\QrCode\\Builder\\Builder');
}

// ─── Proses Update Barcode (satu aset) ──────────────────────────────────────
if (isset($_POST['update_barcode'])) {
    $id_aset      = (int)$_POST['id_aset'];
    $barcode_text = trim($_POST['barcode_text']);

    if (empty($barcode_text)) {
        echo "<script>alert('Teks QR Code tidak boleh kosong!'); history.back();</script>";
        exit;
    }

    // Pastikan folder uploads ada
    if (!file_exists("../uploads")) {
        mkdir("../uploads", 0777, true);
    }

    // Nama file unik
    $safe_text    = preg_replace('/[^a-zA-Z0-9_-]/', '_', $barcode_text);
    $barcode_file = 'qr_' . time() . '_' . $safe_text . '.png';
    $barcode_path = "../uploads/" . $barcode_file;

    if (!libraryTersedia()) {
        $_SESSION['qr_warning'] = "Library endroid/qr-code belum terinstall. Jalankan perintah berikut di terminal: <code>composer require endroid/qr-code</code>";
        echo "<script>document.location='generate_barcode.php';</script>";
        exit;
    }

    $qr_generated = generateQRCode($barcode_text, $barcode_path);

    if ($qr_generated) {
        // Hapus barcode lama jika ada
        $stmt_old = $koneksi->prepare("SELECT barcode FROM aset WHERE id_aset = ?");
        $stmt_old->bind_param("i", $id_aset);
        $stmt_old->execute();
        $old = $stmt_old->get_result()->fetch_assoc();
        if (!empty($old['barcode']) && file_exists("../uploads/" . $old['barcode'])) {
            unlink("../uploads/" . $old['barcode']);
        }

        // Update database
        $stmt = $koneksi->prepare("UPDATE aset SET barcode = ? WHERE id_aset = ?");
        $stmt->bind_param("si", $barcode_file, $id_aset);

        if ($stmt->execute()) {
            echo "<script>alert('✅ QR Code berhasil digenerate!'); document.location='generate_barcode.php';</script>";
        } else {
            if (file_exists($barcode_path)) unlink($barcode_path);
            echo "<script>alert('❌ Gagal menyimpan ke database!'); document.location='generate_barcode.php';</script>";
        }
    } else {
        echo "<script>alert('❌ Gagal generate QR Code! Periksa error log untuk detail.'); document.location='generate_barcode.php';</script>";
    }
    exit;
}

// ─── Proses Generate Semua (aset yang belum punya barcode) ──────────────────
if (isset($_POST['generate_all'])) {
    if (!libraryTersedia()) {
        $_SESSION['qr_warning'] = "Library endroid/qr-code belum terinstall. Jalankan: <code>composer require endroid/qr-code</code>";
        echo "<script>document.location='generate_barcode.php';</script>";
        exit;
    }

    if (!file_exists("../uploads")) {
        mkdir("../uploads", 0777, true);
    }

    $aset_query = mysqli_query($koneksi, "SELECT id_aset, kode_aset FROM aset WHERE barcode IS NULL OR barcode = ''");
    $count   = 0;
    $success = 0;

    while ($aset = mysqli_fetch_assoc($aset_query)) {
        $safe_text    = preg_replace('/[^a-zA-Z0-9_-]/', '_', $aset['kode_aset']);
        $barcode_file = 'qr_' . time() . '_' . $count . '_' . $safe_text . '.png';
        $barcode_path = "../uploads/" . $barcode_file;

        if (generateQRCode($aset['kode_aset'], $barcode_path)) {
            $stmt = $koneksi->prepare("UPDATE aset SET barcode = ? WHERE id_aset = ?");
            $stmt->bind_param("si", $barcode_file, $aset['id_aset']);
            if ($stmt->execute()) $success++;
        }
        $count++;
        usleep(10000); // hindari nama file duplikat karena time() sama
    }

    echo "<script>alert('Generate $success dari $count QR Code berhasil!'); document.location='generate_barcode.php';</script>";
    exit;
}

// ─── Statistik ───────────────────────────────────────────────────────────────
$total_aset    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset"))['total'];
$sudah_barcode = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE barcode IS NOT NULL AND barcode != ''"))['total'];
$belum_barcode = $total_aset - $sudah_barcode;

$qr_warning = $_SESSION['qr_warning'] ?? '';
unset($_SESSION['qr_warning']);

$library_ok = libraryTersedia();

?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Generate QR Code | SIPATRIA</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: ["Font Awesome 5 Solid","Font Awesome 5 Regular","Font Awesome 5 Brands","simple-line-icons"],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () { sessionStorage.fonts = true; },
      });
    </script>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <style>
      .library-status {
        border-radius: 8px;
        padding: 12px 18px;
        font-size: 14px;
      }
      .qr-thumb {
        max-width: 80px;
        max-height: 80px;
        border-radius: 6px;
        border: 1px solid #ddd;
        padding: 3px;
      }
      .badge-ok    { background-color: #28a745; }
      .badge-warn  { background-color: #ffc107; color: #212529; }
      .badge-error { background-color: #dc3545; }
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
                <h4 class="text-section">Master Data</h4>
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
              <li class="nav-item">
                <a href="user.php">
                  <i class="fas fa-users"></i>
                  <p>Kelola Pengguna</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Inventaris</h4>
              </li>
              <li class="nav-item active">
                <a href="generate_barcode.php">
                  <i class="fas fa-qrcode"></i>
                  <p>Generate Barcode</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="kir.php">
                  <i class="fas fa-file-alt"></i>
                  <p>Kartu Inventaris (KIR)</p>
                </a>
              </li>
              <li class="nav-item ">
                <a href="scan_log.php">
                  <i class="fas fa-history"></i>
                  <p>Scan Log Data</p>
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
                <h6 class="text-white">SIPATRIA</h6>
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
              </div>
              <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
            </div>
          </div>
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                      <img src="assets/img/profile.jpg" alt="profile" class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold"><?= htmlspecialchars($_SESSION['nama_user'] ?? 'Admin') ?></span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img src="assets/img/profile.jpg" alt="profile" class="avatar-img rounded" />
                          </div>
                          <div class="u-text">
                            <h4><?= htmlspecialchars($_SESSION['nama_user'] ?? 'Admin') ?></h4>
                            <p class="text-muted"><?= htmlspecialchars($_SESSION['username'] ?? 'admin') ?>@sipatria.go.id</p>
                          </div>
                        </div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="../logout.php">
                          <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
        </div>
        <!-- ─── End Navbar ──────────────────────────────────── -->

        <div class="container">
          <div class="page-inner">

            <!-- Page Header -->
            <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
              <div>
                <h3 class="fw-bold mb-1">Generate QR Code Aset</h3>
                <ul class="breadcrumbs mb-0">
                  <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                  <li class="separator"><i class="icon-arrow-right"></i></li>
                  <li class="nav-item"><a href="#">Generate QR Code</a></li>
                </ul>
              </div>
              <?php if ($_SESSION['role'] == 'Admin Aset'): ?>
              <form method="POST" style="display:inline;">
                <button type="submit" name="generate_all"
                        class="btn btn-success fw-semibold"
                        onclick="return confirm('Generate QR Code untuk semua aset yang belum memiliki QR Code?')"
                        <?= !$library_ok ? 'disabled title="Library belum terinstall"' : '' ?>>
                  <i class="fas fa-qrcode me-1"></i> Generate Semua
                </button>
              </form>
              <?php endif; ?>
            </div>

            <!-- ─── Status Library ──────────────────────────── -->
            <?php if (!$library_ok): ?>
            <div class="alert alert-danger library-status" role="alert">
              <strong><i class="fas fa-times-circle me-2"></i>Library QR Code Tidak Ditemukan!</strong><br>
              Untuk menggunakan fitur generate QR Code, jalankan perintah berikut di terminal/cmd pada folder project:<br>
              <code class="d-block mt-2 p-2 bg-dark text-white rounded">composer require endroid/qr-code</code>
              <small class="text-muted">Pastikan Composer sudah terinstall. Download: <a href="https://getcomposer.org" target="_blank">getcomposer.org</a></small>
            </div>
            <?php else: ?>
            <div class="alert alert-success library-status py-2" role="alert">
              <i class="fas fa-check-circle me-2"></i>
              Library <strong>endroid/qr-code</strong> terdeteksi dan siap digunakan.
            </div>
            <?php endif; ?>

            <!-- Peringatan dari session -->
            <?php if ($qr_warning): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <?= $qr_warning ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <!-- ─── Statistik ────────────────────────────────── -->
            <div class="row mb-3">
              <div class="col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-boxes text-primary"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Total Aset</p>
                          <h4 class="card-title"><?= $total_aset ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-check-circle text-success"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Sudah QR Code</p>
                          <h4 class="card-title"><?= $sudah_barcode ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-clock text-warning"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Belum QR Code</p>
                          <h4 class="card-title"><?= $belum_barcode ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-print text-info"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Cetak QR Code</p>
                          <h4 class="card-title">
                            <a href="cetak_barcode_all.php" target="_blank" class="btn btn-sm btn-info">Cetak Semua</a>
                          </h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- ─── Tabel Aset ────────────────────────────────── -->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Daftar Aset</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Kode Aset</th>
                            <th>Nama Aset</th>
                            <th>Ruangan</th>
                            <th>QR Code</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $no    = 1;
                            $query = mysqli_query($koneksi,
                              "SELECT a.*, r.nama_ruangan
                               FROM aset a
                               LEFT JOIN ruangan r ON a.id_ruangan = r.id_ruangan
                               ORDER BY a.created_at DESC");
                            while ($aset = mysqli_fetch_assoc($query)):
                          ?>
                          <tr>
                            <td><?= $no++ ?></td>
                            <td><code><?= htmlspecialchars($aset['kode_aset']) ?></code></td>
                            <td><?= htmlspecialchars($aset['nama_aset']) ?></td>
                            <td><?= htmlspecialchars($aset['nama_ruangan'] ?? 'Belum Ditentukan') ?></td>
                            <td class="text-center">
                              <?php
                                $hasFile = !empty($aset['barcode']) && file_exists("../uploads/" . $aset['barcode']);
                                $hasDb   = !empty($aset['barcode']);
                              ?>
                              <?php if ($hasFile): ?>
                                <img src="../uploads/<?= htmlspecialchars($aset['barcode']) ?>"
                                     alt="QR Code <?= htmlspecialchars($aset['kode_aset']) ?>"
                                     class="qr-thumb"><br>
                                <small class="text-muted"><?= htmlspecialchars($aset['barcode']) ?></small>
                              <?php elseif ($hasDb): ?>
                                <span class="badge badge-warn">File tidak ditemukan</span>
                              <?php else: ?>
                                <span class="badge badge-error text-white">Belum Generate</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if (!$hasDb || !$hasFile): ?>
                                <!-- Tombol Generate -->
                                <button type="button"
                                  class="btn btn-sm btn-primary generate-btn"
                                  data-id="<?= (int)$aset['id_aset'] ?>"
                                  data-kode="<?= htmlspecialchars($aset['kode_aset']) ?>"
                                  data-nama="<?= htmlspecialchars($aset['nama_aset']) ?>"
                                  data-bs-toggle="modal"
                                  data-bs-target="#generateModal"
                                  <?= !$library_ok ? 'disabled title="Library belum terinstall"' : '' ?>>
                                  <i class="fas fa-qrcode me-1"></i> Generate
                                </button>
                              <?php else: ?>
                                <!-- Tombol Cetak & Regenerate -->
                                <div class="btn-group" role="group">
                                  <a href="cetak_barcode.php?id=<?= (int)$aset['id_aset'] ?>"
                                     class="btn btn-sm btn-info text-white"
                                     target="_blank"
                                     title="Cetak QR Code">
                                    <i class="fas fa-print me-1"></i> Cetak
                                  </a>
                                  <button type="button"
                                    class="btn btn-sm btn-warning generate-btn"
                                    data-id="<?= (int)$aset['id_aset'] ?>"
                                    data-kode="<?= htmlspecialchars($aset['kode_aset']) ?>"
                                    data-nama="<?= htmlspecialchars($aset['nama_aset']) ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#generateModal"
                                    title="Generate Ulang"
                                    <?= !$library_ok ? 'disabled' : '' ?>>
                                    <i class="fas fa-redo me-1"></i> Ulang
                                  </button>
                                </div>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <?php endwhile; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div><!-- /.page-inner -->
        </div><!-- /.container -->

        <!-- ─── Footer ──────────────────────────────────────── -->
        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="#">SIPATRIA</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Help</a></li>
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

    <!-- ─── Modal Generate QR Code ──────────────────────────── -->
    <div class="modal fade" id="generateModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="fas fa-qrcode me-2"></i>Generate QR Code
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="">
            <div class="modal-body">
              <input type="hidden" name="id_aset" id="generate_id">

              <div class="mb-3">
                <label class="form-label fw-semibold">Kode Aset</label>
                <input type="text" id="generate_kode" class="form-control" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Nama Aset</label>
                <input type="text" id="generate_nama" class="form-control" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Teks untuk QR Code</label>
                <input type="text" name="barcode_text" id="generate_barcode_text" class="form-control" required>
                <div class="form-text">QR Code akan menyimpan teks ini. Saat discan akan menampilkan teks ini.</div>
              </div>

              <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-1"></i>
                QR Code disimpan sebagai file PNG di folder <code>uploads/</code>.
                Cetak dan tempelkan pada aset fisik.
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" name="update_barcode" class="btn btn-primary fw-semibold">
                <i class="fas fa-qrcode me-1"></i> Generate QR Code
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- ─── Scripts ─────────────────────────────────────────── -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>

    <script>
      $(document).ready(function () {
        // Inisialisasi DataTables
        $("#basic-datatables").DataTable({
          language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: { previous: "Sebelumnya", next: "Berikutnya" },
            zeroRecords: "Data tidak ditemukan",
          }
        });

        // Isi modal dari tombol Generate
        $(".generate-btn").on("click", function () {
          var id   = $(this).data("id");
          var kode = $(this).data("kode");
          var nama = $(this).data("nama");

          $("#generate_id").val(id);
          $("#generate_kode").val(kode);
          $("#generate_nama").val(nama);
          $("#generate_barcode_text").val(kode); // default: kode aset
        });
      });
    </script>

  </body>
</html>