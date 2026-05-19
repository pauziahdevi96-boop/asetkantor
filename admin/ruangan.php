<?php

include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

if(isset($_GET['hal']) == "hapus"){
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Cek apakah ruangan masih memiliki aset
    $cek_aset = mysqli_query($koneksi, "SELECT * FROM aset WHERE id_ruangan = '$id'");
    if(mysqli_num_rows($cek_aset) > 0) {
        echo "<script>
                alert('Hapus gagal! Ruangan masih memiliki aset. Pindahkan aset terlebih dahulu.');
                document.location='ruangan.php';
            </script>";
    } else {
        $hapus = mysqli_query($koneksi, "DELETE FROM ruangan WHERE id_ruangan = '$id'");
        if($hapus){
            echo "<script>
                    alert('Hapus data ruangan sukses!');
                    document.location='ruangan.php';
                </script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Data Ruangan | SIPATRIA</title>
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
              <li class="nav-item active">
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
              <h3 class="fw-bold mb-3">Data Ruangan</h3>
              <div class="ml-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                  <i class="fa fa-plus"></i> Tambah Ruangan
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
                            <th>Kode Ruangan</th>
                            <th>Nama Ruangan</th>
                            <th>Lokasi</th>
                            <th>Jumlah Aset</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "SELECT r.*, COUNT(a.id_aset) as jumlah_aset 
                                                            FROM ruangan r 
                                                            LEFT JOIN aset a ON r.id_ruangan = a.id_ruangan 
                                                            GROUP BY r.id_ruangan 
                                                            ORDER BY r.nama_ruangan");
                            while($ruangan = mysqli_fetch_array($query)):
                          ?>
                          <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($ruangan['kode_ruangan']); ?></td>
                            <td><?php echo htmlspecialchars($ruangan['nama_ruangan']); ?></td>
                            <td><?php echo htmlspecialchars($ruangan['lokasi']); ?></td>
                            <td><span class="badge badge-info"><?php echo $ruangan['jumlah_aset']; ?> Aset</span></td>
                            <td>
                              <div class="btn-group" role="group">
                                <button type="button" 
                                  class="btn btn-sm btn-warning edit-btn" 
                                  data-id="<?php echo $ruangan['id_ruangan']; ?>"
                                  data-kode="<?php echo htmlspecialchars($ruangan['kode_ruangan']); ?>"
                                  data-nama="<?php echo htmlspecialchars($ruangan['nama_ruangan']); ?>"
                                  data-lokasi="<?php echo htmlspecialchars($ruangan['lokasi']); ?>"
                                  data-bs-toggle="modal" 
                                  data-bs-target="#editModal"
                                  title="Edit">
                                  <i class="fas fa-edit"></i>
                                </button>
                                <a href="ruangan.php?hal=hapus&id=<?php echo $ruangan['id_ruangan']; ?>" 
                                  class="btn btn-sm btn-danger" 
                                  onclick="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')"
                                  title="Hapus">
                                  <i class="fas fa-trash"></i>
                                </a>
                                <a href="cetak_kir.php?id_ruangan=<?php echo $ruangan['id_ruangan']; ?>" 
                                  class="btn btn-sm btn-info" 
                                  target="_blank"
                                  title="Cetak KIR">
                                  <i class="fas fa-print"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                          <?php endwhile; ?>
                          <?php if(mysqli_num_rows($query) == 0): ?>
                          <tr>
                            <td colspan="6" class="text-center">Belum ada data ruangan</td>
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
                  <a class="nav-link" href="#">SIPATRIA</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
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

    <!-- Modal Tambah Ruangan -->
    <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Tambah Ruangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="proses_ruangan.php">
            <div class="modal-body">
              <div class="form-group">
                <label>Kode Ruangan</label>
                <input type="text" name="kode_ruangan" class="form-control" placeholder="Contoh: R001" required>
              </div>
              <div class="form-group">
                <label>Nama Ruangan</label>
                <input type="text" name="nama_ruangan" class="form-control" placeholder="Contoh: Ruang Rapat Utama" required>
              </div>
              <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lantai 2 Gedung Utama" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Edit Ruangan -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h5 class="modal-title">Edit Ruangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="proses_ruangan.php">
            <div class="modal-body">
              <input type="hidden" name="id_ruangan" id="edit_id">
              <div class="form-group">
                <label>Kode Ruangan</label>
                <input type="text" name="kode_ruangan" id="edit_kode" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Nama Ruangan</label>
                <input type="text" name="nama_ruangan" id="edit_nama" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" id="edit_lokasi" class="form-control" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" name="edit" class="btn btn-warning">Update</button>
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
        
        $(".edit-btn").click(function() {
          $("#edit_id").val($(this).data("id"));
          $("#edit_kode").val($(this).data("kode"));
          $("#edit_nama").val($(this).data("nama"));
          $("#edit_lokasi").val($(this).data("lokasi"));
        });
      });
    </script>
  </body>
</html>