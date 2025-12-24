<?php
// Import file config.php yang berisi class Database
include "config.php";

// Membuat instance/object dari class Database
$db = new Database();

// Mendapatkan koneksi mysqli dari class Database
// $koneksi ini yang akan digunakan untuk query database
$koneksi = $db->getConnection();

// Variabel untuk menyimpan pesan error/sukses
$message = "";

// Cek apakah tombol 'daftar' ditekan (form disubmit)
if (isset($_POST['daftar'])) {

    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];    // Konfirmasi password

    // Validasi: cek apakah password dan konfirmasi password sama
    if ($password !== $password2) {
        $message = "Password tidak sama!";
    } else {

        // Cek apakah username sudah terdaftar di database
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
        $userAda = mysqli_fetch_array($cek);

        // Jika username sudah ada
        if ($userAda) {
            $message = "Username sudah digunakan!";
        } else {

            // Enkripsi password menggunakan MD5 (sebaiknya gunakan password_hash() untuk lebih aman)
            $passmd5 = md5($password);

            // Simpan user baru ke database
            $save = mysqli_query($koneksi, "INSERT INTO users (username, password)
            VALUES ('$username', '$passmd5')");

            // Jika berhasil disimpan
            if ($save) {
                // Tampilkan alert sukses dan redirect ke halaman login
                echo '<script>alert("Pendaftaran berhasil! Silakan login.");location.href="login.php"</script>';
                exit;
            } else {
                $message = "Gagal mendaftar!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Daftar Akun Baru</h2>

    <?php if($message != "") : ?>
        <div class="msg"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="password" name="password2" placeholder="Ulangi Password" required>

        <button type="submit" name="daftar" class="btn-register">Daftar</button>

        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </form>
</div>

</body>
</html>