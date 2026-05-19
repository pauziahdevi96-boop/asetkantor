<?php

include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Proses Hapus
if(isset($_GET['hal']) == "hapus"){
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    // Ambil nama file barcode lama untuk dihapus
    $query_foto = mysqli_query($koneksi, "SELECT barcode FROM aset WHERE id_aset = '$id'");
    $data_foto = mysqli_fetch_array($query_foto);
    if($data_foto['barcode'] && file_exists("../uploads/".$data_foto['barcode'])) {
        unlink("../uploads/".$data_foto['barcode']);
    }
    
    $hapus = mysqli_query($koneksi, "DELETE FROM aset WHERE id_aset = '$id'");
    if($hapus){
        echo "<script>
        alert('Hapus data aset sukses!');
        document.location='aset.php';
        </script>";
    }
}

// Ambil data ruangan untuk dropdown
$query_ruangan = mysqli_query($koneksi, "SELECT * FROM ruangan ORDER BY nama_ruangan");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Data Aset | SIPATRIA</title>
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
        .error-message {
            color: red;
            font-size: 0.8rem;
            margin-top: 5px;
            display: none;
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
              <h3 class="fw-bold mb-3">Data Aset</h3>
              <div class="ml-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                  <i class="fa fa-plus"></i> Tambah Aset
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
                            <th>Kode Aset</th>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Ruangan</th>
                            <th>Kondisi</th>
                            <th>Nilai Aset</th>
                            <th>Barcode</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "SELECT a.*, r.nama_ruangan FROM aset a LEFT JOIN ruangan r ON a.id_ruangan = r.id_ruangan ORDER BY a.created_at DESC");
                            while($aset = mysqli_fetch_array($query)) {
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
                            <td><?php echo htmlspecialchars($aset['nama_ruangan'] ?? 'Belum Ditentukan'); ?></td>
                            <td><span class="<?php echo $kondisi_class; ?>"><?php echo $aset['kondisi']; ?></span></td>
                            <td>Rp <?php echo number_format($aset['nilai_aset'], 0, ',', '.'); ?></td>
                            <td>
                              <?php if(!empty($aset['barcode'])) { ?>
                                <img src="../uploads/<?php echo htmlspecialchars($aset['barcode']); ?>" 
                                    alt="Barcode" 
                                    style="max-width: 80px;">
                              <?php } else { ?>
                                <span class="badge badge-danger">Belum Generate</span>
                              <?php } ?>
                            </td>
                            <td>
                              <div class="btn-group" role="group">
                                <button type="button" 
                                  class="btn btn-sm btn-warning edit-btn" 
                                  data-id="<?php echo $aset['id_aset']; ?>"
                                  data-kode="<?php echo htmlspecialchars($aset['kode_aset']); ?>"
                                  data-nama="<?php echo htmlspecialchars($aset['nama_aset']); ?>"
                                  data-kategori="<?php echo htmlspecialchars($aset['kategori']); ?>"
                                  data-merk="<?php echo htmlspecialchars($aset['merk']); ?>"
                                  data-tahun="<?php echo $aset['tahun_perolehan']; ?>"
                                  data-kondisi="<?php echo $aset['kondisi']; ?>"
                                  data-nilai="<?php echo $aset['nilai_aset']; ?>"
                                  data-ruangan="<?php echo $aset['id_ruangan']; ?>"
                                  data-bs-toggle="modal" 
                                  data-bs-target="#editModal"
                                  title="Edit">
                                  <i class="fas fa-edit"></i>
                                </button>
                                <a href="aset.php?hal=hapus&id=<?php echo $aset['id_aset']; ?>" 
                                  class="btn btn-sm btn-danger" 
                                  onclick="return confirm('Apakah Anda yakin ingin menghapus aset ini?')"
                                  title="Hapus">
                                  <i class="fas fa-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                          <?php } ?>
                          <?php if(mysqli_num_rows($query) == 0): ?>
                          <tr>
                            <td colspan="9" class="text-center">Belum ada data aset</td>
                          </tr>
                          <?php endif; ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>No</th>
                            <th>Kode Aset</th>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Ruangan</th>
                            <th>Kondisi</th>
                            <th>Nilai Aset</th>
                            <th>Barcode</th>
                            <th>Aksi</th>
                          </tr>
                        </tfoot>
                      8</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal Tambah Aset -->
    <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-plus"></i> Tambah Aset</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="proses_aset.php">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Kode Aset <span class="text-danger">*</span></label>
                    <input type="text" name="kode_aset" class="form-control" placeholder="Contoh: AST001" required>
                  </div>
                  <div class="form-group">
                    <label>Nama Aset <span class="text-danger">*</span></label>
                    <input type="text" name="nama_aset" class="form-control" placeholder="Contoh: Komputer Desktop" required>
                  </div>
                  <div class="form-group">
                    <label>Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-control" required>
                      <option value="">Pilih Kategori</option>
                      <option value="Elektronik">Elektronik</option>
                      <option value="Furniture">Furniture</option>
                      <option value="Kendaraan">Kendaraan</option>
                      <option value="Alat Kantor">Alat Kantor</option>
                      <option value="Lainnya">Lainnya</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Merk / Tipe</label>
                    <input type="text" name="merk" class="form-control" placeholder="Contoh: Dell, HP, Olympic">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Tahun Perolehan <span class="text-danger">*</span></label>
                    <select name="tahun_perolehan" class="form-control" required>
                      <option value="">Pilih Tahun</option>
                      <?php for($year = date('Y'); $year >= 2010; $year--): ?>
                        <option value="<?= $year ?>"><?= $year ?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Kondisi <span class="text-danger">*</span></label>
                    <select name="kondisi" class="form-control" required>
                      <option value="">Pilih Kondisi</option>
                      <option value="Baik">Baik</option>
                      <option value="Rusak Ringan">Rusak Ringan</option>
                      <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Nilai Aset (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="nilai_aset" class="form-control" placeholder="Contoh: 8500000" min="0" required>
                  </div>
                  <div class="form-group">
                    <label>Ruangan <span class="text-danger">*</span></label>
                    <select name="id_ruangan" class="form-control" required>
                      <option value="">Pilih Ruangan</option>
                      <?php 
                      mysqli_data_seek($query_ruangan, 0);
                      while($ruangan = mysqli_fetch_array($query_ruangan)): ?>
                        <option value="<?= $ruangan['id_ruangan'] ?>"><?= htmlspecialchars($ruangan['nama_ruangan']) ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="alert alert-info mt-2">
                <i class="fas fa-info-circle"></i> Barcode akan digenerate nanti di halaman <strong>Generate Barcode</strong>.
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

    <!-- Modal Edit Aset -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Aset</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="proses_aset.php">
            <div class="modal-body">
              <input type="hidden" name="id_aset" id="edit_id">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Kode Aset <span class="text-danger">*</span></label>
                    <input type="text" name="kode_aset" id="edit_kode" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label>Nama Aset <span class="text-danger">*</span></label>
                    <input type="text" name="nama_aset" id="edit_nama" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label>Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" id="edit_kategori" class="form-control" required>
                      <option value="">Pilih Kategori</option>
                      <option value="Elektronik">Elektronik</option>
                      <option value="Furniture">Furniture</option>
                      <option value="Kendaraan">Kendaraan</option>
                      <option value="Alat Kantor">Alat Kantor</option>
                      <option value="Lainnya">Lainnya</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Merk / Tipe</label>
                    <input type="text" name="merk" id="edit_merk" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Tahun Perolehan <span class="text-danger">*</span></label>
                    <select name="tahun_perolehan" id="edit_tahun" class="form-control" required>
                      <option value="">Pilih Tahun</option>
                      <?php for($year = date('Y'); $year >= 2010; $year--): ?>
                        <option value="<?= $year ?>"><?= $year ?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Kondisi <span class="text-danger">*</span></label>
                    <select name="kondisi" id="edit_kondisi" class="form-control" required>
                      <option value="">Pilih Kondisi</option>
                      <option value="Baik">Baik</option>
                      <option value="Rusak Ringan">Rusak Ringan</option>
                      <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Nilai Aset (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="nilai_aset" id="edit_nilai" class="form-control" min="0" required>
                  </div>
                  <div class="form-group">
                    <label>Ruangan <span class="text-danger">*</span></label>
                    <select name="id_ruangan" id="edit_ruangan" class="form-control" required>
                      <option value="">Pilih Ruangan</option>
                      <?php 
                      mysqli_data_seek($query_ruangan, 0);
                      while($ruangan = mysqli_fetch_array($query_ruangan)): ?>
                        <option value="<?= $ruangan['id_ruangan'] ?>"><?= htmlspecialchars($ruangan['nama_ruangan']) ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                </div>
              </div>
              
              <!-- Tampilkan Barcode saat ini jika ada -->
              <div class="row" id="barcode_edit_container" style="display: none;">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Barcode Saat Ini</label><br>
                    <img id="edit_barcode_img" src="" alt="Barcode" style="max-width: 150px;">
                    <p class="text-muted small mt-1" id="edit_barcode_text"></p>
                    <input type="hidden" name="barcode_lama" id="edit_barcode_lama">
                  </div>
                </div>
              </div>
              
              <div class="alert alert-warning mt-2">
                <i class="fas fa-exclamation-triangle"></i> Jika Anda mengubah kode aset, barcode akan digenerate ulang otomatis.
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
        
        $(".edit-btn").click(function() {
          var id = $(this).data("id");
          var kode = $(this).data("kode");
          var nama = $(this).data("nama");
          var kategori = $(this).data("kategori");
          var merk = $(this).data("merk");
          var tahun = $(this).data("tahun");
          var kondisi = $(this).data("kondisi");
          var nilai = $(this).data("nilai");
          var ruangan = $(this).data("ruangan");
          
          $("#edit_id").val(id);
          $("#edit_kode").val(kode);
          $("#edit_nama").val(nama);
          $("#edit_kategori").val(kategori);
          $("#edit_merk").val(merk);
          $("#edit_tahun").val(tahun);
          $("#edit_kondisi").val(kondisi);
          $("#edit_nilai").val(nilai);
          $("#edit_ruangan").val(ruangan);
          
          // Sembunyikan container barcode karena barcode tidak perlu diedit di sini
          $("#barcode_edit_container").hide();
        });
      });
    </script>
  </body>
</html>