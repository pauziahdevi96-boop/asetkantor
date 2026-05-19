<?php

include '../koneksi.php';
include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Filter
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

// Ambil data untuk dropdown filter
$query_kategori = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM aset WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori");
$query_kondisi = mysqli_query($koneksi, "SELECT DISTINCT kondisi FROM aset ORDER BY kondisi");
$query_ruangan = mysqli_query($koneksi, "SELECT * FROM ruangan ORDER BY nama_ruangan");
$query_tahun = mysqli_query($koneksi, "SELECT DISTINCT tahun_perolehan FROM aset WHERE tahun_perolehan IS NOT NULL ORDER BY tahun_perolehan DESC");

// Hitung statistik
$total_aset = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset"))['total'];
$total_nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(nilai_aset) as total FROM aset"))['total'];
$aset_baik = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE kondisi = 'Baik'"))['total'];
$aset_rusak = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM aset WHERE kondisi != 'Baik'"))['total'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Laporan Aset | SIPATRIA</title>
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
              <li class="nav-item active">
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
                      <span class="fw-bold"><?= $_SESSION['nama_user'] ?? 'Admin' ?></span>
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
                            <h4><?= $_SESSION['nama_user'] ?? 'Admin' ?></h4>
                            <p class="text-muted"><?= $_SESSION['username'] ?? 'admin' ?>@sulsel.go.id</p>
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
            <div class="page-header">
              <h3 class="fw-bold mb-3">Laporan Data Aset</h3>
              <div class="ml-auto">
                <a href="cetak_laporan_aset.php?kategori=<?= $filter_kategori ?>&kondisi=<?= $filter_kondisi ?>&id_ruangan=<?= $filter_ruangan ?>&tahun=<?= $filter_tahun ?>" 
                   class="btn btn-success" 
                   target="_blank">
                  <i class="fas fa-print"></i> Cetak Laporan
                </a>
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
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-money-bill text-info"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Total Nilai</p>
                          <h4 class="card-title">Rp <?= number_format($total_nilai, 0, ',', '.') ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Filter Form -->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Filter Laporan</h4>
                  </div>
                  <div class="card-body">
                    <form method="GET" action="">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control">
                              <option value="">Semua Kategori</option>
                              <?php while($kat = mysqli_fetch_array($query_kategori)): ?>
                                <option value="<?= $kat['kategori'] ?>" <?= $filter_kategori == $kat['kategori'] ? 'selected' : '' ?>>
                                  <?= $kat['kategori'] ?>
                                </option>
                              <?php endwhile; ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Kondisi</label>
                            <select name="kondisi" class="form-control">
                              <option value="">Semua Kondisi</option>
                              <?php 
                              mysqli_data_seek($query_kondisi, 0);
                              while($kond = mysqli_fetch_array($query_kondisi)): ?>
                                <option value="<?= $kond['kondisi'] ?>" <?= $filter_kondisi == $kond['kondisi'] ? 'selected' : '' ?>>
                                  <?= $kond['kondisi'] ?>
                                </option>
                              <?php endwhile; ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Ruangan</label>
                            <select name="id_ruangan" class="form-control">
                              <option value="">Semua Ruangan</option>
                              <?php 
                              mysqli_data_seek($query_ruangan, 0);
                              while($ruang = mysqli_fetch_array($query_ruangan)): ?>
                                <option value="<?= $ruang['id_ruangan'] ?>" <?= $filter_ruangan == $ruang['id_ruangan'] ? 'selected' : '' ?>>
                                  <?= htmlspecialchars($ruang['nama_ruangan']) ?>
                                </option>
                              <?php endwhile; ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Tahun Perolehan</label>
                            <select name="tahun" class="form-control">
                              <option value="">Semua Tahun</option>
                              <?php 
                              mysqli_data_seek($query_tahun, 0);
                              while($thn = mysqli_fetch_array($query_tahun)): ?>
                                <option value="<?= $thn['tahun_perolehan'] ?>" <?= $filter_tahun == $thn['tahun_perolehan'] ? 'selected' : '' ?>>
                                  <?= $thn['tahun_perolehan'] ?>
                                </option>
                              <?php endwhile; ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                          </button>
                          <a href="laporan_aset.php" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                          </a>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tabel Data Aset -->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Data Aset</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="basic-datatables" class="display table table-striped table-hover">
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
                            $total_nilai_filter = 0;
                            mysqli_data_seek($query, 0);
                            while($aset = mysqli_fetch_array($query)):
                              $total_nilai_filter += $aset['nilai_aset'];
                              $kondisi_class = '';
                              if($aset['kondisi'] == 'Baik') $kondisi_class = 'badge badge-success';
                              else if($aset['kondisi'] == 'Rusak Ringan') $kondisi_class = 'badge badge-warning';
                              else $kondisi_class = 'badge badge-danger';
                          ?>
                          <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($aset['kode_aset']); ?></td>
                            <td><?php echo htmlspecialchars($aset['nama_aset']); ?></td>
                            <td><?php echo htmlspecialchars($aset['kategori']); ?></td>
                            <td><?php echo htmlspecialchars($aset['merk']); ?></td>
                            <td><?php echo htmlspecialchars($aset['nama_ruangan'] ?? 'Belum Ditentukan'); ?></td>
                            <td><?php echo $aset['tahun_perolehan']; ?></td>
                            <td><span class="<?php echo $kondisi_class; ?>"><?php echo $aset['kondisi']; ?></span></td>
                            <td>Rp <?php echo number_format($aset['nilai_aset'], 0, ',', '.'); ?></td>
                          </tr>
                          <?php endwhile; ?>
                          <?php if($no == 1): ?>
                          <tr>
                            <td colspan="9" class="text-center">Tidak ada data aset yang sesuai dengan filter</td>
                          </tr>
                          <?php endif; ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="8" style="text-align: right;">TOTAL NILAI:</th>
                            <th>Rp <?php echo number_format($total_nilai_filter, 0, ',', '.'); ?></th>
                          </tr>
                        </tfoot>
                      68
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Core JS Files -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="assets/js/kaiadmin.min.js"></script>

    <script>
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});
      });
    </script>
  </body>
</html>