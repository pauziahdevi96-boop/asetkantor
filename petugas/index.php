<?php

include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Petugas Inventaris
cek_role('Petugas Inventaris');

// Query untuk statistik
$total_aset = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset"))['total'];
$total_ruangan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM ruangan"))['total'];
$aset_baik = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE kondisi = 'Baik'"))['total'];
$aset_rusak = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE kondisi != 'Baik'"))['total'];

// Query untuk aset terbaru
$query_aset_terbaru = mysqli_query($koneksi, "SELECT a.*, r.nama_ruangan FROM aset a LEFT JOIN ruangan r ON a.id_ruangan = r.id_ruangan ORDER BY a.updated_at DESC LIMIT 10");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard Petugas | SIPATRIA</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

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

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
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
              <li class="nav-item active">
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
              <li class="nav-item">
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
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <div class="logo-header" data-background-color="dark">
              <a href="index.php" class="logo">
                <h6 class="text-white">SIPATRIA</h6>
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
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                      <img src="assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold"><?= $_SESSION['nama_user'] ?? 'Petugas' ?></span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img src="assets/img/profile.jpg" alt="image profile" class="avatar-img rounded" />
                          </div>
                          <div class="u-text">
                            <h4><?= $_SESSION['nama_user'] ?? 'Petugas' ?></h4>
                            <p class="text-muted"><?= $_SESSION['username'] ?? 'petugas' ?>@sulsel.go.id</p>
                            <p class="text-muted small">Role: <?= $_SESSION['role'] ?? 'Petugas Inventaris' ?></p>
                          </div>
                        </div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="../logout.php">Logout</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
        </div>

        <div class="container">
          <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
              <div>
                <h3 class="fw-bold mb-3">Dashboard Petugas Inventaris</h3>
                <h6 class="op-7 mb-2">Selamat datang, <?= $_SESSION['nama_user'] ?? 'Petugas' ?></h6>
                <h6 class="op-7">Sistem Pendataan Aset & Kartu Inventaris Ruangan (KIR)</h6>
                <h6 class="op-7">Kantor Gubernur Provinsi Sulawesi Selatan</h6>
              </div>
            </div>

            <!-- Statistik Cards -->
            <div class="row">
              <div class="col-sm-6 col-md-3">
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
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-building text-info"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Total Ruangan</p>
                          <h4 class="card-title"><?= $total_ruangan ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
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
                          <p class="card-category">Aset Baik</p>
                          <h4 class="card-title"><?= $aset_baik ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-exclamation-triangle text-warning"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Aset Rusak</p>
                          <h4 class="card-title"><?= $aset_rusak ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Menu Aksi Cepat -->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Aksi Cepat</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-4">
                        <a href="scan_barcode.php" class="btn btn-primary btn-block w-100 mb-2">
                          <i class="fas fa-qrcode"></i> Scan Barcode
                        </a>
                      </div>
                      <div class="col-md-4">
                        <a href="aset.php" class="btn btn-info btn-block w-100 mb-2">
                          <i class="fas fa-boxes"></i> Lihat Data Aset
                        </a>
                      </div>
                      <div class="col-md-4">
                        <a href="kir.php" class="btn btn-success btn-block w-100 mb-2">
                          <i class="fas fa-file-alt"></i> Cetak KIR
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Data Aset Terbaru -->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Data Aset Terbaru</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Kode Aset</th>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Ruangan</th>
                            <th>Kondisi</th>
                            <th>Nilai</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php while($aset = mysqli_fetch_array($query_aset_terbaru)): ?>
                          <tr>
                            <td><?= htmlspecialchars($aset['kode_aset']) ?></td>
                            <td><?= htmlspecialchars($aset['nama_aset']) ?></td>
                            <td><?= htmlspecialchars($aset['kategori']) ?></td>
                            <td><?= htmlspecialchars($aset['nama_ruangan'] ?? 'Belum Ditentukan') ?></td>
                            <td>
                              <?php if($aset['kondisi'] == 'Baik'): ?>
                                <span class="badge bg-success">Baik</span>
                              <?php elseif($aset['kondisi'] == 'Rusak Ringan'): ?>
                                <span class="badge bg-warning">Rusak Ringan</span>
                              <?php else: ?>
                                <span class="badge bg-danger">Rusak Berat</span>
                              <?php endif; ?>
                            </td>
                            <td>Rp <?= number_format($aset['nilai_aset'], 0, ',', '.') ?></td>
                          </tr>
                          <?php endwhile; ?>
                          <?php if(mysqli_num_rows($query_aset_terbaru) == 0): ?>
                          <tr>
                            <td colspan="6" class="text-center">Belum ada data aset</td>
                          </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>
  </body>
</html>