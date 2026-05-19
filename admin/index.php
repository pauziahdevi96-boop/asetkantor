<?php
// File: admin/index.php (Dashboard Admin Aset)
include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Query untuk mendapatkan statistik data
$total_aset = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset"))['total'];
$total_ruangan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM ruangan"))['total'];
$total_kir = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kir"))['total'];
$aset_baik = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE kondisi = 'Baik'"))['total'];
$aset_rusak_ringan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE kondisi = 'Rusak Ringan'"))['total'];
$aset_rusak_berat = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE kondisi = 'Rusak Berat'"))['total'];

// Query untuk data aset terbaru
$query_aset_terbaru = mysqli_query($koneksi, "SELECT a.*, r.nama_ruangan FROM aset a LEFT JOIN ruangan r ON a.id_ruangan = r.id_ruangan ORDER BY a.created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard Admin | Sistem Pendataan Aset & KIR</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
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

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
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
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
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
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="assets/img/profile.jpg"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold"><?= $_SESSION['nama_user'] ?? 'Admin' ?></span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="assets/img/profile.jpg"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4><?= $_SESSION['nama_user'] ?? 'Admin' ?></h4>
                            <p class="text-muted"><?= $_SESSION['username'] ?? 'admin' ?>@sulsel.go.id</p>
                            <p class="text-muted small">Role: <?= $_SESSION['role'] ?? 'Admin Aset' ?></p>
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
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Dashboard, <?= $_SESSION['nama_user'] ?? 'Admin' ?></h3>
                <h6 class="op-7 mb-2">Sistem Pendataan Aset & Kartu Inventaris Ruangan (KIR)</h6>
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
                          <i class="fas fa-file-alt text-success"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Total KIR</p>
                          <h4 class="card-title"><?= $total_kir ?></h4>
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
                          <i class="fas fa-qrcode text-warning"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Barcode Aktif</p>
                          <h4 class="card-title"><?= $total_aset ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Kondisi Aset Chart -->
            <div class="row">
              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-header">
                    <h4 class="card-title">Statistik Kondisi Aset</h4>
                  </div>
                  <div class="card-body">
                    <canvas id="kondisiChart"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-header">
                    <h4 class="card-title">Ringkasan Kondisi</h4>
                  </div>
                  <div class="card-body d-flex flex-column" style="padding: 1rem;">
                    <div class="row g-3 flex-grow-1">
                      <div class="col-12">
                        <div class="alert alert-success mb-0 d-flex justify-content-between align-items-center" 
                            style="padding: 1rem 1rem; min-height: 70px;">
                          <span style="font-size: 1rem;">
                            <i class="fas fa-check-circle"></i> 
                            <strong>Baik</strong>
                          </span>
                          <span class="badge bg-success" style="font-size: 1rem; padding: 6px 14px;">
                            <?= number_format($aset_baik) ?> Aset
                          </span>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="alert alert-warning mb-0 d-flex justify-content-between align-items-center" 
                            style="padding: 1rem 1rem; min-height: 70px;">
                          <span style="font-size: 1rem;">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Rusak Ringan</strong>
                          </span>
                          <span class="badge bg-warning text-dark" style="font-size: 1rem; padding: 6px 14px;">
                            <?= number_format($aset_rusak_ringan) ?> Aset
                          </span>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="alert alert-danger mb-0 d-flex justify-content-between align-items-center" 
                            style="padding: 1rem 1rem; min-height: 70px;">
                          <span style="font-size: 1rem;">
                            <i class="fas fa-times-circle"></i> 
                            <strong>Rusak Berat</strong>
                          </span>
                          <span class="badge bg-danger" style="font-size: 1rem; padding: 6px 14px;">
                            <?= number_format($aset_rusak_berat) ?> Aset
                          </span>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Total Aset di bagian bawah -->
                    <div class="mt-auto pt-3 border-top">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <i class="fas fa-chart-pie"></i> 
                          <strong>Total Aset</strong>
                        </div>
                        <div>
                          <span class="badge bg-primary" style="font-size: 1rem; padding: 6px 14px;">
                            <?= number_format($total_aset) ?> Aset
                          </span>
                        </div>
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
                          <?php while($row = mysqli_fetch_assoc($query_aset_terbaru)): ?>
                          <tr>
                            <td><?= $row['kode_aset'] ?></td>
                            <td><?= $row['nama_aset'] ?></td>
                            <td><?= $row['kategori'] ?></td>
                            <td><?= $row['nama_ruangan'] ?? 'Belum Ditentukan' ?></td>
                            <td>
                              <?php if($row['kondisi'] == 'Baik'): ?>
                                <span class="badge bg-success">Baik</span>
                              <?php elseif($row['kondisi'] == 'Rusak Ringan'): ?>
                                <span class="badge bg-warning">Rusak Ringan</span>
                              <?php else: ?>
                                <span class="badge bg-danger">Rusak Berat</span>
                              <?php endif; ?>
                            </td>
                            <td>Rp <?= number_format($row['nilai_aset'], 0, ',', '.') ?></td>
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

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="#">
                    SIPATRIA
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2026, made with <i class="fa fa-heart heart text-danger"></i> by
              <a href="#">Fauziah Deviani Imani Halim</a>
            </div>
            <div>
              Sistem Pendataan Aset & KIR
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>

    <script>
      // Chart untuk kondisi aset
      var ctx = document.getElementById('kondisiChart').getContext('2d');
      var kondisiChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Baik', 'Rusak Ringan', 'Rusak Berat'],
          datasets: [{
            data: [<?= $aset_baik ?>, <?= $aset_rusak_ringan ?>, <?= $aset_rusak_berat ?>],
            backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true
        }
      });
    </script>
  </body>
</html>