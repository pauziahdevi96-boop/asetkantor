<?php
include '../koneksi.php';
include '../cek_session.php';

// Cek apakah role yang mengakses adalah Admin Aset
cek_role('Admin Aset');

// Proses Tambah
if(isset($_POST['tambah'])) {
    $nama_user = mysqli_real_escape_string($koneksi, $_POST['nama_user']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    
    $query = mysqli_query($koneksi, "INSERT INTO user (nama_user, username, password, role) 
                                     VALUES ('$nama_user', '$username', '$password', '$role')");
    
    if($query) {
        echo "<script>alert('Tambah pengguna sukses!'); document.location='user.php';</script>";
    } else {
        echo "<script>alert('Tambah pengguna gagal! Username mungkin sudah terdaftar.'); document.location='user.php';</script>";
    }
}

// Proses Edit
if(isset($_POST['edit'])) {
    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $nama_user = mysqli_real_escape_string($koneksi, $_POST['nama_user']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    
    if(!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $query = mysqli_query($koneksi, "UPDATE user SET 
                                         nama_user = '$nama_user',
                                         username = '$username',
                                         password = '$password',
                                         role = '$role'
                                         WHERE id_user = '$id_user'");
    } else {
        $query = mysqli_query($koneksi, "UPDATE user SET 
                                         nama_user = '$nama_user',
                                         username = '$username',
                                         role = '$role'
                                         WHERE id_user = '$id_user'");
    }
    
    if($query) {
        echo "<script>alert('Edit pengguna sukses!'); document.location='user.php';</script>";
    } else {
        echo "<script>alert('Edit pengguna gagal!'); document.location='user.php';</script>";
    }
}
?>