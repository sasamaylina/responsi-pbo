<?php
// Mulai session untuk menyimpan data user yang login
session_start();

// Import file config.php yang berisi class Database
include "config.php";

// Membuat instance/object dari class Database
$db = new Database();

// Mendapatkan koneksi mysqli dari class Database
$koneksi = $db->getConnection();

// Cek jika koneksi gagal
if(!$koneksi){
    die("Koneksi database gagal!");
}

// Redirect ke index.php jika user sudah login
if(isset($_SESSION['users'])){
    if(!empty($_SESSION['users'])){
        header("Location: index.php");
        exit;
    }
}

// Proses login ketika form disubmit
if(isset($_POST['username'])) {

    // Sanitasi input username untuk mencegah SQL injection
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);

    // Enkripsi password dengan MD5 (harus sama dengan saat register)
    $password = md5($_POST['password']);

    // Query untuk cek username dan password di database
    $query = mysqli_query($koneksi,
        "SELECT * FROM users WHERE username='$username' AND password='$password'"
    );

    // Jika data ditemukan (login berhasil)
    if(mysqli_num_rows($query) > 0){
        // Ambil data user
        $data = mysqli_fetch_assoc($query);

        // Simpan data user ke session
        $_SESSION['users'] = $data;

        // Tampilkan alert selamat datang dan redirect ke index.php
        echo '<script>
                alert("Selamat datang '.$data['username'].'");
                window.location="index.php";
              </script>';
        exit;

    } else {
        // Jika username/password salah
        echo '<script>alert("Username atau password tidak sesuai");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card p-4 shadow login-card" style="width: 350px; border-radius: 12px;">
        <h3 class="text-center mb-4">Login</h3>

        <form method="post">

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-2">Masuk</button>
            <p class="login-link">
                Belum punya akun?<a href="register.php">Daftar di sini</a></p>
        </form>
    </div>
</div>

</body>
</html>
