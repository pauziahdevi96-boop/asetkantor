<?php

include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Proses Hapus Log
if(isset($_GET['hal']) == "hapus"){
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $hapus = mysqli_query($koneksi, "DELETE FROM scan_log WHERE id_log = '$id'");
    if($hapus){
        echo "<script>
        alert('Hapus data log sukses!');
        document.location='scan_log.php';
        </script>";
    }
}

// Proses Hapus Semua Log
if(isset($_GET['hal']) == "hapus_semua"){
    $hapus = mysqli_query($koneksi, "DELETE FROM scan_log");
    if($hapus){
        echo "<script>
        alert('Hapus semua data log sukses!');
        document.location='scan_log.php';
        </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Scan Log Data | SIPATRIA</title>
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
    
    <style>
        .badge-scan {
            background-color: #6c5ce7;
            color: white;
        }
        .filter-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>
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
              <li class="nav-item active">
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
              <h3 class="fw-bold mb-3">Scan Log Data</h3>
              <div class="ml-auto">
                <a href="scan_log.php?hal=hapus_semua" class="btn btn-danger me-2" 
                   onclick="return confirm('Apakah Anda yakin ingin menghapus SEMUA data log?')">
                  <i class="fa fa-trash"></i> Hapus Semua
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                  <i class="fa fa-filter"></i> Filter
                </button>
              </div>
            </div>
            
            <!-- Statistik Ringkas -->
            <?php
            $total_log = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM scan_log"));
            $today_log = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM scan_log WHERE DATE(waktu_scan) = CURDATE()"));
            $unique_aset = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(DISTINCT kode_aset) as total FROM scan_log"));
            ?>
            <div class="row mb-3">
              <div class="col-md-4">
                <div class="card bg-primary text-white">
                  <div class="card-body">
                    <h5 class="card-title">Total Scan</h5>
                    <h2><?= $total_log['total'] ?></h2>
                    <small>Seluruh aktivitas scan</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card bg-info text-white">
                  <div class="card-body">
                    <h5 class="card-title">Scan Hari Ini</h5>
                    <h2><?= $today_log['total'] ?></h2>
                    <small>Aktivitas hari ini</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card bg-success text-white">
                  <div class="card-body">
                    <h5 class="card-title">Aset Discan</h5>
                    <h2><?= $unique_aset['total'] ?></h2>
                    <small>Jumlah aset yang pernah discan</small>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="log-datatables" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Kode Aset</th>
                            <th>Nama Aset</th>
                            <th>Aktivitas</th>
                            <th>Lokasi Scan</th>
                            <th>Petugas</th>
                            <th>Waktu Scan</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $no = 1;
                            
                            // Proses Filter jika ada
                            $where = "1=1";
                            $tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
                            $tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';
                            $kode_aset_filter = isset($_GET['kode_aset']) ? $_GET['kode_aset'] : '';
                            
                            if(!empty($tanggal_awal) && !empty($tanggal_akhir)) {
                                $where .= " AND DATE(sl.waktu_scan) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
                            }
                            if(!empty($kode_aset_filter)) {
                                $where .= " AND sl.kode_aset = '$kode_aset_filter'";
                            }
                            
                            $query = mysqli_query($koneksi, "SELECT sl.*, u.nama_user, a.nama_aset 
                                                            FROM scan_log sl 
                                                            LEFT JOIN user u ON sl.id_user = u.id_user
                                                            LEFT JOIN aset a ON sl.kode_aset = a.kode_aset
                                                            WHERE $where
                                                            ORDER BY sl.waktu_scan DESC");
                            while($log = mysqli_fetch_array($query)) {
                              // Tentukan badge berdasarkan jenis aktivitas
                              $badge_class = 'badge-secondary';
                              if(strpos($log['lokasi_scan'], 'Mobile Scan') !== false) {
                                  $badge_class = 'badge-primary';
                              } elseif(strpos($log['lokasi_scan'], 'Mutasi') !== false) {
                                  $badge_class = 'badge-warning';
                              } elseif(strpos($log['lokasi_scan'], 'Update Kondisi') !== false) {
                                  $badge_class = 'badge-info';
                              }
                          ?>
                          <tr>
                            <td><?php echo $no++; ?></td>
                            <td><span><?php echo htmlspecialchars($log['kode_aset']); ?></span></td>
                            <td><?php echo htmlspecialchars($log['nama_aset'] ?? '-'); ?></td>
                            <td><span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($log['lokasi_scan']); ?></span></td>
                            <td>
                              <?php if(!empty($log['lokasi_scan']) && strpos($log['lokasi_scan'], 'Mutasi') !== false) {
                                  echo '<i class="fas fa-exchange-alt text-warning"></i> ';
                              } elseif(!empty($log['lokasi_scan']) && strpos($log['lokasi_scan'], 'Mobile Scan') !== false) {
                                  echo '<i class="fas fa-qrcode text-primary"></i> ';
                              } elseif(!empty($log['lokasi_scan']) && strpos($log['lokasi_scan'], 'Update Kondisi') !== false) {
                                  echo '<i class="fas fa-sync-alt text-info"></i> ';
                              }
                              echo htmlspecialchars($log['lokasi_scan'] ?? '-'); ?>
                            </td>
                            <td>
                              <?php if(!empty($log['nama_user'])): ?>
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($log['nama_user']); ?>
                              <?php else: ?>
                                <span class="text-muted">-</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <i class="fas fa-calendar-alt"></i> <?php echo date('d/m/Y H:i:s', strtotime($log['waktu_scan'])); ?>
                            </td>
                            <td>
                              <a href="scan_log.php?hal=hapus&id=<?php echo $log['id_log']; ?>" 
                                 class="btn btn-sm btn-danger" 
                                 onclick="return confirm('Apakah Anda yakin ingin menghapus log ini?')"
                                 title="Hapus">
                                <i class="fas fa-trash"></i>
                              </a>
                            </td>
                          </tr>
                          <?php } ?>
                          <?php if(mysqli_num_rows($query) == 0): ?>
                          <tr>
                            <td colspan="8" class="text-center">Belum ada data scan log</td>
                          </tr>
                          <?php endif; ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>No</th>
                            <th>Kode Aset</th>
                            <th>Nama Aset</th>
                            <th>Aktivitas</th>
                            <th>Lokasi Scan</th>
                            <th>Petugas</th>
                            <th>Waktu Scan</th>
                            <th>Aksi</th>
                          </tr>
                        </tfoot>
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

    <!-- Modal Filter -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-filter"></i> Filter Scan Log</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="GET" action="scan_log.php">
            <div class="modal-body">
              <div class="form-group">
                <label>Filter Berdasarkan Tanggal</label>
                <div class="row">
                  <div class="col-md-6">
                    <input type="date" name="tanggal_awal" class="form-control" placeholder="Tanggal Awal" 
                           value="<?php echo isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : ''; ?>">
                  </div>
                  <div class="col-md-6">
                    <input type="date" name="tanggal_akhir" class="form-control" placeholder="Tanggal Akhir"
                           value="<?php echo isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : ''; ?>">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Filter Berdasarkan Kode Aset</label>
                <input type="text" name="kode_aset" class="form-control" placeholder="Masukkan Kode Aset"
                       value="<?php echo isset($_GET['kode_aset']) ? $_GET['kode_aset'] : ''; ?>">
              </div>
              <div class="alert alert-info mt-2">
                <i class="fas fa-info-circle"></i> Kosongkan filter untuk menampilkan semua data.
              </div>
            </div>
            <div class="modal-footer">
              <a href="scan_log.php" class="btn btn-secondary">Reset Filter</a>
              <button type="submit" class="btn btn-primary">Terapkan Filter</button>
            </div>
          </form>
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
        $("#log-datatables").DataTable({
          order: [[6, 'desc']], // Urutkan berdasarkan waktu scan terbaru
          pageLength: 25,
          language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",
            paginate: {
              first: "Pertama",
              last: "Terakhir",
              next: "Selanjutnya",
              previous: "Sebelumnya"
            }
          }
        });
      });
    </script>
  </body>
</html>