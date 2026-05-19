<?php
include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Proses Tambah
if(isset($_POST['tambah'])) {
    $kode_ruangan = mysqli_real_escape_string($koneksi, $_POST['kode_ruangan']);
    $nama_ruangan = mysqli_real_escape_string($koneksi, $_POST['nama_ruangan']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    
    $query = mysqli_query($koneksi, "INSERT INTO ruangan (kode_ruangan, nama_ruangan, lokasi) 
                                     VALUES ('$kode_ruangan', '$nama_ruangan', '$lokasi')");
    
    if($query) {
        echo "<script>alert('Tambah ruangan sukses!'); document.location='ruangan.php';</script>";
    } else {
        echo "<script>alert('Tambah ruangan gagal!'); document.location='ruangan.php';</script>";
    }
}

// Proses Edit
if(isset($_POST['edit'])) {
    $id_ruangan = mysqli_real_escape_string($koneksi, $_POST['id_ruangan']);
    $kode_ruangan = mysqli_real_escape_string($koneksi, $_POST['kode_ruangan']);
    $nama_ruangan = mysqli_real_escape_string($koneksi, $_POST['nama_ruangan']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    
    $query = mysqli_query($koneksi, "UPDATE ruangan SET 
                                     kode_ruangan = '$kode_ruangan',
                                     nama_ruangan = '$nama_ruangan',
                                     lokasi = '$lokasi'
                                     WHERE id_ruangan = '$id_ruangan'");
    
    if($query) {
        echo "<script>alert('Edit ruangan sukses!'); document.location='ruangan.php';</script>";
    } else {
        echo "<script>alert('Edit ruangan gagal!'); document.location='ruangan.php';</script>";
    }
}
?>