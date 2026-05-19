<?php
include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Proses Tambah Aset (tanpa generate barcode otomatis)
if(isset($_POST['tambah'])) {
    $kode_aset = mysqli_real_escape_string($koneksi, $_POST['kode_aset']);
    $nama_aset = mysqli_real_escape_string($koneksi, $_POST['nama_aset']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $merk = mysqli_real_escape_string($koneksi, $_POST['merk']);
    $tahun_perolehan = mysqli_real_escape_string($koneksi, $_POST['tahun_perolehan']);
    $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
    $nilai_aset = mysqli_real_escape_string($koneksi, $_POST['nilai_aset']);
    $id_ruangan = mysqli_real_escape_string($koneksi, $_POST['id_ruangan']);
    
    // Barcode dikosongkan dulu, akan digenerate di halaman generate_barcode.php
    $barcode = NULL;
    
    $query = mysqli_query($koneksi, "INSERT INTO aset 
        (kode_aset, nama_aset, kategori, merk, tahun_perolehan, kondisi, nilai_aset, barcode, id_ruangan) 
        VALUES 
        ('$kode_aset', '$nama_aset', '$kategori', '$merk', '$tahun_perolehan', '$kondisi', '$nilai_aset', NULL, '$id_ruangan')");
    
    if($query) {
        echo "<script>
                alert('Tambah data aset sukses! Silakan generate barcode di halaman Generate Barcode.');
                document.location='aset.php';
            </script>";
    } else {
        echo "<script>
                alert('Tambah data aset gagal! " . mysqli_error($koneksi) . "');
                document.location='aset.php';
            </script>";
    }
}

// Proses Edit Aset
if(isset($_POST['edit'])) {
    $id_aset = mysqli_real_escape_string($koneksi, $_POST['id_aset']);
    $kode_aset = mysqli_real_escape_string($koneksi, $_POST['kode_aset']);
    $nama_aset = mysqli_real_escape_string($koneksi, $_POST['nama_aset']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $merk = mysqli_real_escape_string($koneksi, $_POST['merk']);
    $tahun_perolehan = mysqli_real_escape_string($koneksi, $_POST['tahun_perolehan']);
    $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
    $nilai_aset = mysqli_real_escape_string($koneksi, $_POST['nilai_aset']);
    $id_ruangan = mysqli_real_escape_string($koneksi, $_POST['id_ruangan']);
    $barcode_lama = mysqli_real_escape_string($koneksi, $_POST['barcode_lama']);
    
    // Ambil kode aset lama untuk cek perubahan
    $query_lama = mysqli_query($koneksi, "SELECT kode_aset FROM aset WHERE id_aset = '$id_aset'");
    $data_lama = mysqli_fetch_array($query_lama);
    
    // Jika kode aset berubah, update barcode jadi NULL agar digenerate ulang
    if($data_lama['kode_aset'] != $kode_aset) {
        // Hapus file barcode lama jika ada
        if(!empty($barcode_lama) && file_exists("../uploads/".$barcode_lama)) {
            unlink("../uploads/".$barcode_lama);
        }
        
        $query = mysqli_query($koneksi, "UPDATE aset SET 
            kode_aset = '$kode_aset',
            nama_aset = '$nama_aset',
            kategori = '$kategori',
            merk = '$merk',
            tahun_perolehan = '$tahun_perolehan',
            kondisi = '$kondisi',
            nilai_aset = '$nilai_aset',
            barcode = NULL,
            id_ruangan = '$id_ruangan'
            WHERE id_aset = '$id_aset'");
    } else {
        // Jika kode aset tidak berubah, barcode tetap
        $query = mysqli_query($koneksi, "UPDATE aset SET 
            kode_aset = '$kode_aset',
            nama_aset = '$nama_aset',
            kategori = '$kategori',
            merk = '$merk',
            tahun_perolehan = '$tahun_perolehan',
            kondisi = '$kondisi',
            nilai_aset = '$nilai_aset',
            id_ruangan = '$id_ruangan'
            WHERE id_aset = '$id_aset'");
    }
    
    if($query) {
        echo "<script>
                alert('Edit data aset sukses!');
                document.location='aset.php';
            </script>";
    } else {
        echo "<script>
                alert('Edit data aset gagal! " . mysqli_error($koneksi) . "');
                document.location='aset.php';
            </script>";
    }
}
?>