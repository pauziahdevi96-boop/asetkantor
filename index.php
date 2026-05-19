<?php

include 'koneksi.php';

session_start();

// Jika sudah login, redirect ke dashboard sesuai role
if(isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    if($_SESSION['role'] == 'Admin Aset') {
        header("location: admin");
    } else if($_SESSION['role'] == 'Petugas Inventaris') {
        header("location: petugas");
    }
    exit();
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);
    
    // Query untuk mencocokkan username dan password dari tabel user
    $query = mysqli_query($koneksi, "SELECT * FROM `user` 
                                     WHERE `username` = '$username' 
                                     AND `password` = '$password'");
    
    $cek = mysqli_num_rows($query);
    
    if ($cek > 0) {
        // Ambil data user
        $user_data = mysqli_fetch_assoc($query);
        
        // Simpan data ke dalam session
        $_SESSION['id_user'] = $user_data['id_user'];
        $_SESSION['nama_user'] = $user_data['nama_user'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user_data['role'];
        $_SESSION['status'] = "login";
        
        // Redirect berdasarkan role
        if($user_data['role'] == 'Admin Aset') {
            header('location: admin');
        } else if($user_data['role'] == 'Petugas Inventaris') {
            header('location: petugas');
        } else {
            // Jika role tidak dikenali
            echo "<script>
                alert('Role tidak dikenali!');
                window.location.href = 'index.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Login Gagal! Periksa Username dan Password Anda!');
            window.location.href = 'index.php';
        </script>";
    }
}
?>

<!doctype html>
<html lang="id" dir="ltr" data-bs-theme="auto">
<head>

    <!-- Include JavaScript for color modes -->
    <script src="./assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Halaman Login Sistem Pendataan Aset dan Kartu Inventaris Ruangan">
    <meta name="keywords" content="login, aset, inventaris, barcode, KIR">
    <meta name="author" content="Fauziah Deviani Imani Halim">
    <meta name="generator" content="Bootstrap">

    <title>Login | Sistem Pendataan Aset & KIR</title>


    <!-- Stylesheets -->
    <link rel="stylesheet" href="./assets/libraries/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/main.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            text-align: center;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 40px;
            border-radius: 25px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
            transform: scale(1.02);
        }
        .logo-title {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 10px;
        }
    </style>

</head>
<body>

    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">🏢 SI PATRIA</h3>
                        <small>Sistem Pendataan Aset & Kartu Inventaris Ruangan</small>
                    </div>
                    <div class="card-body" style="padding: 30px;">
                        <h4 class="text-center mb-4">Login Sistem</h4>
                        <p class="text-center text-muted mb-4">Silakan masukkan username dan password Anda</p>
                        
                        <form class="needs-validation" id="loginForm" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person"></i> Username
                                </label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Masukkan username" required autofocus>
                                <div class="invalid-feedback">
                                    Silakan masukkan username.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Masukkan password" required>
                                <div class="invalid-feedback">
                                    Silakan masukkan password.
                                </div>
                            </div>
                            <div class="mb-4 text-center">
                                <button type="submit" name="login" class="btn btn-primary w-100">
                                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                                </button>
                            </div>
                            <div class="text-center text-muted small">
                                <hr>
                                <p class="mb-0">Kantor Gubernur Provinsi Sulawesi Selatan</p>
                                <p class="mb-0">Biro Umum dan Perlengkapan</p>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3 text-white">
                    <small>&copy; 2026 - Sistem Pendataan Aset & KIR Berbasis Barcode</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript: Bundle with Popper -->
    <script src="./assets/libraries/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/scripts.js"></script>

    <!-- Validasi form client-side -->
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

</body>
</html>