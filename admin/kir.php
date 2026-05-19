<?php

include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Hapus KIR
if(isset($_GET['hal']) == "hapus"){
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $hapus = mysqli_query($koneksi, "DELETE FROM kir WHERE id_kir = '$id'");
    if($hapus){
        echo "<script>
                alert('Hapus KIR sukses!');
                document.location='kir.php';
            </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Kartu Inventaris Ruangan | SIPATRIA</title>
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
              <li class="nav-item active">
                <a href="kir.php">
                  <i class="fas fa-file-alt"></i>
                  <p>Kartu Inventaris Ruangan (KIR)</p>
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
              <h3 class="fw-bold mb-3">Kartu Inventaris Ruangan (KIR)</h3>
              <div class="ml-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal">
                  <i class="fa fa-plus"></i> Generate KIR
                </button>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Ruangan</th>
                            <th>Lokasi</th>
                            <th>Tanggal Cetak</th>
                            <th>Jumlah Aset</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "SELECT k.*, r.nama_ruangan, r.lokasi, 
                                                            (SELECT COUNT(*) FROM detail_kir dk WHERE dk.id_kir = k.id_kir) as jumlah_aset
                                                            FROM kir k 
                                                            JOIN ruangan r ON k.id_ruangan = r.id_ruangan 
                                                            ORDER BY k.tanggal_cetak DESC");
                            while($kir = mysqli_fetch_array($query)):
                          ?>
                          <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($kir['nama_ruangan']); ?></td>
                            <td><?php echo htmlspecialchars($kir['lokasi']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($kir['tanggal_cetak'])); ?></td>
                            <td><span class="badge badge-info"><?php echo $kir['jumlah_aset']; ?> Aset</span></td>
                            <td>
                              <div class="btn-group" role="group">
                                <a href="cetak_kir.php?id_kir=<?php echo $kir['id_kir']; ?>" 
                                  class="btn btn-sm btn-info" 
                                  target="_blank"
                                  title="Cetak KIR">
                                  <i class="fas fa-print"></i>
                                </a>
                                <a href="kir.php?hal=hapus&id=<?php echo $kir['id_kir']; ?>" 
                                  class="btn btn-sm btn-danger" 
                                  onclick="return confirm('Apakah Anda yakin ingin menghapus KIR ini?')"
                                  title="Hapus">
                                  <i class="fas fa-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                          <?php endwhile; ?>
                          <?php if(mysqli_num_rows($query) == 0): ?>
                          <tr>
                            <td colspan="6" class="text-center">Belum ada data KIR</td>
                          </tr>
                          <?php endif; ?>
                        </tbody>
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

    <!-- Modal Generate KIR -->
    <div class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Generate Kartu Inventaris Ruangan (KIR)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="proses_kir.php">
            <div class="modal-body">
              <div class="form-group">
                <label>Pilih Ruangan</label>
                <select name="id_ruangan" class="form-control" required>
                  <option value="">-- Pilih Ruangan --</option>
                  <?php
                  $ruangan_query = mysqli_query($koneksi, "SELECT * FROM ruangan ORDER BY nama_ruangan");
                  while($ruangan = mysqli_fetch_array($ruangan_query)):
                  ?>
                  <option value="<?= $ruangan['id_ruangan'] ?>"><?= htmlspecialchars($ruangan['nama_ruangan']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> KIR akan berisi daftar semua aset yang berada di ruangan tersebut.
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" name="generate" class="btn btn-primary">Generate KIR</button>
            </div>
          </form>
        </div>
      </div>
    </div>

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