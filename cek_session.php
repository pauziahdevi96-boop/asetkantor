<?php
// ======================================================
// FILE: cek_session.php
// Fungsi: Cek session dan validasi role (bisa dipanggil di semua halaman)
// ======================================================

session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("location: index.php");
    exit();
}

// Fungsi untuk cek role tertentu
function cek_role($role_yang_diperbolehkan) {
    if($_SESSION['role'] != $role_yang_diperbolehkan) {
        if($_SESSION['role'] == 'Admin Aset') {
            header("location: admin");
        } else {
            header("location: index.php");
        }
        exit();
    }
}

// Fungsi untuk mendapatkan data user session (mudah dipanggil)
function get_session_user() {
    return [
        'id_user' => $_SESSION['id_user'] ?? null,
        'nama_user' => $_SESSION['nama_user'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'role' => $_SESSION['role'] ?? null
    ];
}
?>