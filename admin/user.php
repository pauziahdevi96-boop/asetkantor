<?php

include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

if(isset($_GET['hal']) == "hapus"){
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    // Jangan hapus admin utama
    if($id == 1) {
        echo "<script>
                alert('Tidak dapat menghapus admin utama!');
                document.location='user.php';
            </script>";
    } else {
        $hapus = mysqli_query($koneksi, "DELETE FROM user WHERE id_user = '$id'");
        if($hapus){
            echo "<script>
                    alert('Hapus data pengguna sukses!');
                    document.location='user.php';
                </script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Kelola Pengguna | SIPATRIA</title>
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
              <li class="nav-item active">
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
              <h3 class="fw-bold mb-3">Kelola Pengguna</h3>
              <div class="ml-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                  <i class="fa fa-plus"></i> Tambah Pengguna
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
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "SELECT * FROM user ORDER BY id_user");
                            while($user = mysqli_fetch_array($query)):
                          ?>
                          <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($user['nama_user']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td>
                              <?php if($user['role'] == 'Admin Aset'): ?>
                                <span class="badge badge-primary">Admin Aset</span>
                              <?php else: ?>
                                <span class="badge badge-info">Petugas Inventaris</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <div class="btn-group" role="group">
                                <button type="button" 
                                  class="btn btn-sm btn-warning edit-btn" 
                                  data-id="<?php echo $user['id_user']; ?>"
                                  data-nama="<?php echo htmlspecialchars($user['nama_user']); ?>"
                                  data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                  data-role="<?php echo $user['role']; ?>"
                                  data-bs-toggle="modal" 
                                  data-bs-target="#editModal"
                                  title="Edit">
                                  <i class="fas fa-edit"></i>
                                </button>
                                <?php if($user['id_user'] != 1): ?>
                                <a href="user.php?hal=hapus&id=<?php echo $user['id_user']; ?>" 
                                  class="btn btn-sm btn-danger" 
                                  onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"
                                  title="Hapus">
                                  <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                              </div>
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

    <!-- Modal Tambah User -->
    <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Tambah Pengguna</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="proses_user.php">
            <div class="modal-body">
              <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_user" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" required>
                  <option value="">Pilih Role</option>
                  <option value="Admin Aset">Admin Aset</option>
                  <option value="Petugas Inventaris">Petugas Inventaris</option>
                </select>
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

    <!-- Modal Edit User -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h5 class="modal-title">Edit Pengguna</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="proses_user.php">
            <div class="modal-body">
              <input type="hidden" name="id_user" id="edit_id">
              <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_user" id="edit_nama" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="edit_username" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control">
              </div>
              <div class="form-group">
                <label>Role</label>
                <select name="role" id="edit_role" class="form-control" required>
                  <option value="Admin Aset">Admin Aset</option>
                  <option value="Petugas Inventaris">Petugas Inventaris</option>
                </select>
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
        