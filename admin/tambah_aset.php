<?php

include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Ambil data ruangan untuk dropdown
$query_ruangan = mysqli_query($koneksi, "SELECT * FROM ruangan ORDER BY nama_ruangan");

if (isset($_POST['simpan'])) {
    $kode_aset       = trim($_POST['kode_aset']);
    $nama_aset       = trim($_POST['nama_aset']);
    $kategori        = trim($_POST['kategori']);
    $merk            = trim($_POST['merk']);
    $tahun_perolehan = (int)$_POST['tahun_perolehan'];
    $kondisi         = trim($_POST['kondisi']);
    $nilai_aset      = (float)$_POST['nilai_aset'];
    $id_ruangan      = (int)$_POST['id_ruangan'];

    // QR Code tidak diisi di sini — dikelola di generate_barcode.php (barcode = NULL)
    $stmt = $koneksi->prepare("INSERT INTO aset 
        (kode_aset, nama_aset, kategori, merk, tahun_perolehan, kondisi, nilai_aset, barcode, id_ruangan, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NULL, ?, NOW())");
    $stmt->bind_param(
        "ssssissi",
        $kode_aset, $nama_aset, $kategori, $merk,
        $tahun_perolehan, $kondisi, $nilai_aset, $id_ruangan
    );

    if ($stmt->execute()) {
        echo "<script>alert('✅ Data aset berhasil disimpan!'); document.location='aset.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menyimpan data aset!'); document.location='aset.php';</script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Tambah Aset | SIPATRIA</title>
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
      .error-message {
        color: red;
        font-size: 0.8rem;
        margin-top: 5px;
        display: none;
      }
      .info-qr {
        background: #eef6ff;
        border-left: 4px solid #0d6efd;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 13px;
        color: #555;
        margin-bottom: 16px;
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
                <h4 class="text-section">Master Data</h4>
              </li>
              <li class="nav-item active">
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
              <li class="nav-item">
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
              <a href="index.php" class="logo"><h6 class="text-white">SIPATRIA</h6></a>
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
                            <p class="text-muted"><?= htmlspecialchars($_SESSION['username'] ?? 'admin') ?>@sulsel.go.id</p>
                          </div>
                        </div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
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
            <div class="page-header">
              <h3 class="fw-bold mb-1">Tambah Aset</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="aset.php">Data Aset</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Tambah</a></li>
              </ul>
            </div>

            <!-- Info QR Code -->
            <div class="info-qr">
              <i class="fas fa-info-circle me-1 text-primary"></i>
              <strong>QR Code</strong> tidak dibuat di sini. Setelah aset disimpan, generate QR Code melalui menu
              <a href="generate_barcode.php"><strong>Generate QR Code</strong></a>.
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <form method="POST">
                      <div class="row">

                        <!-- Kolom Kiri -->
                        <div class="col-md-6">

                          <!-- Kode Aset -->
                          <div class="form-group">
                            <label for="kode_aset">Kode Aset <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode_aset" name="kode_aset"
                                   placeholder="Contoh: AST001" required />
                            <div id="kodeError" class="error-message">Kode aset harus diisi</div>
                          </div>

                          <!-- Nama Aset -->
                          <div class="form-group">
                            <label for="nama_aset">Nama Aset <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_aset" name="nama_aset"
                                   placeholder="Contoh: Komputer Desktop" required />
                            <div id="namaError" class="error-message">Nama aset harus diisi</div>
                          </div>

                          <!-- Kategori -->
                          <div class="form-group">
                            <label for="kategori">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control" id="kategori" name="kategori" required>
                              <option value="">Pilih Kategori</option>
                              <option value="Elektronik">Elektronik</option>
                              <option value="Furniture">Furniture</option>
                              <option value="Kendaraan">Kendaraan</option>
                              <option value="Alat Kantor">Alat Kantor</option>
                              <option value="Lainnya">Lainnya</option>
                            </select>
                            <div id="kategoriError" class="error-message">Kategori harus dipilih</div>
                          </div>

                          <!-- Merk -->
                          <div class="form-group">
                            <label for="merk">Merk / Tipe</label>
                            <input type="text" class="form-control" id="merk" name="merk"
                                   placeholder="Contoh: Dell, HP, Olympic" />
                          </div>

                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">

                          <!-- Tahun Perolehan -->
                          <div class="form-group">
                            <label for="tahun_perolehan">Tahun Perolehan <span class="text-danger">*</span></label>
                            <select class="form-control" id="tahun_perolehan" name="tahun_perolehan" required>
                              <option value="">Pilih Tahun</option>
                              <?php for ($year = date('Y'); $year >= 2010; $year--): ?>
                                <option value="<?= $year ?>"><?= $year ?></option>
                              <?php endfor; ?>
                            </select>
                            <div id="tahunError" class="error-message">Tahun perolehan harus dipilih</div>
                          </div>

                          <!-- Kondisi -->
                          <div class="form-group">
                            <label for="kondisi">Kondisi <span class="text-danger">*</span></label>
                            <select class="form-control" id="kondisi" name="kondisi" required>
                              <option value="">Pilih Kondisi</option>
                              <option value="Baik">Baik</option>
                              <option value="Rusak Ringan">Rusak Ringan</option>
                              <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                            <div id="kondisiError" class="error-message">Kondisi harus dipilih</div>
                          </div>

                          <!-- Nilai Aset -->
                          <div class="form-group">
                            <label for="nilai_aset">Nilai Aset (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="nilai_aset" name="nilai_aset"
                                   placeholder="Contoh: 8500000" min="0" required />
                            <div id="nilaiError" class="error-message">Nilai aset harus diisi dengan angka positif</div>
                          </div>

                          <!-- Ruangan -->
                          <div class="form-group">
                            <label for="id_ruangan">Ruangan <span class="text-danger">*</span></label>
                            <select class="form-control" id="id_ruangan" name="id_ruangan" required>
                              <option value="">Pilih Ruangan</option>
                              <?php while ($ruangan = mysqli_fetch_assoc($query_ruangan)): ?>
                                <option value="<?= (int)$ruangan['id_ruangan'] ?>">
                                  <?= htmlspecialchars($ruangan['nama_ruangan']) ?>
                                </option>
                              <?php endwhile; ?>
                            </select>
                            <div id="ruanganError" class="error-message">Ruangan harus dipilih</div>
                          </div>

                        </div>
                      </div><!-- /.row -->

                      <div class="card-action">
                        <button type="submit" name="simpan" id="submitButton" class="btn btn-success fw-semibold">
                          <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="aset.php" class="btn btn-danger fw-semibold">
                          <i class="fas fa-times me-1"></i> Batal
                        </a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

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
      </div>
    </div>

    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const fields = {
          kode:     { el: document.getElementById('kode_aset'),       err: document.getElementById('kodeError'),     type: 'text' },
          nama:     { el: document.getElementById('nama_aset'),        err: document.getElementById('namaError'),     type: 'text' },
          kategori: { el: document.getElementById('kategori'),          err: document.getElementById('kategoriError'), type: 'select' },
          tahun:    { el: document.getElementById('tahun_perolehan'),   err: document.getElementById('tahunError'),    type: 'select' },
          kondisi:  { el: document.getElementById('kondisi'),           err: document.getElementById('kondisiError'),  type: 'select' },
          nilai:    { el: document.getElementById('nilai_aset'),        err: document.getElementById('nilaiError'),    type: 'number' },
          ruangan:  { el: document.getElementById('id_ruangan'),        err: document.getElementById('ruanganError'),  type: 'select' },
        };
        const submitBtn = document.getElementById('submitButton');

        function validate(key) {
          const f = fields[key];
          let ok = true;
          if (f.type === 'text')   ok = f.el.value.trim() !== '';
          if (f.type === 'select') ok = f.el.value !== '';
          if (f.type === 'number') ok = !isNaN(parseFloat(f.el.value)) && parseFloat(f.el.value) >= 0;
          f.err.style.display = ok ? 'none' : 'block';
          return ok;
        }

        function checkAll() {
          const allOk = Object.keys(fields).every(k => validate(k));
          submitBtn.disabled = !allOk;
        }

        Object.keys(fields).forEach(k => {
          const ev = fields[k].type === 'text' || fields[k].type === 'number' ? 'input' : 'change';
          fields[k].el.addEventListener(ev, checkAll);
        });
      });
    </script>
  </body>
</html>