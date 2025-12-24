<?php
/**
 * File: aksi_pelanggan.php
 * File untuk memproses aksi CRUD (Create, Read, Update, Delete) data pelanggan
 * Semua operasi database dilakukan melalui class Pelanggan (konsep OOP)
 */

// Import file yang diperlukan
require_once 'config.php';
require_once 'class_pelanggan.php';

// Membuat instance class Database dan Pelanggan
$db = new Database();
$conn = $db->getConnection();
$pelanggan = new Pelanggan($conn);

/**
 * PROSES INSERT (Tambah Data Baru)
 * Dipanggil ketika tombol 'submit' ditekan di form
 */
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama_pelanggan = trim($_POST['nama_pelanggan']);
    $alamat = trim($_POST['alamat']);
    $no_telepon = trim($_POST['no_telepon']);
    $email = trim($_POST['email']);

    // Validasi: cek apakah semua field wajib sudah diisi
    if (empty($nama_pelanggan) || empty($alamat) || empty($no_telepon)) {
        echo "
        <div style='text-align:center; padding:50px;'>
            <h3>❗ Error</h3>
            <p>Nama, Alamat, dan No. Telepon harus diisi!</p>
            <a href='pelanggan.php' class='btn btn-primary'>Kembali</a>
        </div>";
        exit;
    }

    // Validasi: cek apakah nomor telepon sudah terdaftar
    if ($pelanggan->isTeleponExists($no_telepon)) {
        echo "
        <div style='text-align:center; padding:50px;'>
            <h3>❗ Error</h3>
            <p>Nomor telepon sudah terdaftar!</p>
            <a href='pelanggan.php' class='btn btn-primary'>Kembali</a>
        </div>";
        exit;
    }

    // Proses insert data menggunakan method dari class Pelanggan
    if ($pelanggan->insert($nama_pelanggan, $alamat, $no_telepon, $email)) {
        // Jika berhasil, redirect ke halaman pelanggan dengan pesan sukses
        echo "<script>
                alert('Data pelanggan berhasil ditambahkan!');
                window.location='pelanggan.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "
        <div style='text-align:center; padding:50px;'>
            <h3>❗ Error</h3>
            <p>Gagal menambahkan data pelanggan!</p>
            <a href='pelanggan.php' class='btn btn-primary'>Kembali</a>
        </div>";
    }
}

/**
 * PROSES UPDATE (Edit Data)
 * Dipanggil ketika tombol 'update' ditekan di form edit
 */
if (isset($_POST['update'])) {
    // Ambil data dari form
    $id_pelanggan = $_POST['id_pelanggan'];
    $nama_pelanggan = trim($_POST['nama_pelanggan']);
    $alamat = trim($_POST['alamat']);
    $no_telepon = trim($_POST['no_telepon']);
    $email = trim($_POST['email']);

    // Validasi: cek apakah semua field wajib sudah diisi
    if (empty($nama_pelanggan) || empty($alamat) || empty($no_telepon)) {
        echo "
        <div style='text-align:center; padding:50px;'>
            <h3>❗ Error</h3>
            <p>Nama, Alamat, dan No. Telepon harus diisi!</p>
            <a href='pelanggan.php?edit=$id_pelanggan' class='btn btn-primary'>Kembali</a>
        </div>";
        exit;
    }

    // Validasi: cek apakah nomor telepon sudah digunakan pelanggan lain
    if ($pelanggan->isTeleponExists($no_telepon, $id_pelanggan)) {
        echo "
        <div style='text-align:center; padding:50px;'>
            <h3>❗ Error</h3>
            <p>Nomor telepon sudah digunakan oleh pelanggan lain!</p>
            <a href='pelanggan.php?edit=$id_pelanggan' class='btn btn-primary'>Kembali</a>
        </div>";
        exit;
    }

    // Proses update data menggunakan method dari class Pelanggan
    if ($pelanggan->update($id_pelanggan, $nama_pelanggan, $alamat, $no_telepon, $email)) {
        // Jika berhasil, redirect ke halaman pelanggan dengan pesan sukses
        echo "<script>
                alert('Data pelanggan berhasil diupdate!');
                window.location='pelanggan.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "
        <div style='text-align:center; padding:50px;'>
            <h3>❗ Error</h3>
            <p>Gagal mengupdate data pelanggan!</p>
            <a href='pelanggan.php' class='btn btn-primary'>Kembali</a>
        </div>";
    }
}

/**
 * PROSES DELETE (Hapus Data)
 * Dipanggil ketika link 'Hapus' diklik di tabel
 */
if (isset($_GET['delete'])) {
    // Ambil ID pelanggan dari parameter URL
    $id_pelanggan = $_GET['delete'];

    // Proses delete data menggunakan method dari class Pelanggan
    if ($pelanggan->delete($id_pelanggan)) {
        // Jika berhasil, redirect ke halaman pelanggan
        header("Location: pelanggan.php");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "
        <div style='text-align:center; padding:50px;'>
            <h3>❗ Error</h3>
            <p>Gagal menghapus data pelanggan!</p>
            <a href='pelanggan.php' class='btn btn-primary'>Kembali</a>
        </div>";
    }
}
?>
