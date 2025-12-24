<?php
/**
 * File: aksi.php
 * File ini berisi class ProdukToko dan logic untuk proses CRUD produk
 * Menggunakan konsep OOP (Object Oriented Programming)
 */

require_once 'config.php';

/**
 * Class ProdukToko
 * Class untuk mengelola data produk toko
 * Menggunakan konsep OOP dengan method CRUD (Create, Read, Update, Delete)
 */
class ProdukToko {
    // Properti untuk menyimpan koneksi database
    private $conn;

    /**
     * Constructor - menerima koneksi database sebagai parameter
     * @param mysqli $db - Object koneksi database
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Method untuk mengambil semua data produk
     * @return array - Array berisi semua data produk
     */
    public function getAll() {
        // Query untuk select semua data dari tabel produk
        $result = $this->conn->query("SELECT * FROM produk");

        // Array untuk menampung data
        $data = [];

        // Loop untuk memasukkan setiap row ke dalam array
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Method untuk mengambil data produk berdasarkan ID
     * @param int $id - ID produk yang ingin diambil
     * @return array|null - Data produk atau null jika tidak ditemukan
     */
    public function getById($id) {
        // Menggunakan prepared statement untuk keamanan (mencegah SQL injection)
        $stmt = $this->conn->prepare("SELECT * FROM produk WHERE id_produk=?");

        // Bind parameter (i = integer)
        $stmt->bind_param("i", $id);

        // Eksekusi query
        $stmt->execute();

        // Ambil hasil query
        $result = $stmt->get_result();

        // Fetch data sebagai array asosiatif
        $data = $result->fetch_assoc();

        // Tutup statement
        $stmt->close();

        return $data;
    }

    /**
     * Method untuk menambah data produk baru
     * @param string $nama_produk - Nama produk
     * @param string $material - Material produk
     * @param string $harga - Harga produk
     * @return bool - True jika berhasil, false jika gagal
     */
    public function insert($nama_produk, $material, $harga) {
        // Prepared statement untuk insert data
        $stmt = $this->conn->prepare("INSERT INTO produk (nama_produk, material, harga) VALUES (?, ?, ?)");

        // Bind parameter (s = string)
        $stmt->bind_param("sss", $nama_produk, $material, $harga);

        // Eksekusi query dan simpan hasilnya
        $result = $stmt->execute();

        // Tutup statement
        $stmt->close();

        return $result;
    }

    /**
     * Method untuk mengupdate data produk
     * @param int $id - ID produk yang akan diupdate
     * @param string $nama_produk - Nama produk baru
     * @param string $material - Material produk baru
     * @param string $harga - Harga produk baru
     * @return bool - True jika berhasil, false jika gagal
     */
    public function update($id, $nama_produk, $material, $harga) {
        // Prepared statement untuk update data
        $stmt = $this->conn->prepare("UPDATE produk SET
        nama_produk=?, material=?, harga=? WHERE id_produk=?");

        // Bind parameter (s = string, i = integer)
        $stmt->bind_param("sssi", $nama_produk, $material, $harga, $id);

        // Eksekusi query dan simpan hasilnya
        $result = $stmt->execute();

        // Tutup statement
        $stmt->close();

        return $result;
    }

    /**
     * Method untuk menghapus data produk
     * @param int $id - ID produk yang akan dihapus
     * @return bool - True jika berhasil, false jika gagal
     */
    public function delete($id) {
        // Prepared statement untuk delete data
        $stmt = $this->conn->prepare("DELETE FROM produk WHERE id_produk=?");

        // Bind parameter (i = integer)
        $stmt->bind_param("i", $id);

        // Eksekusi query dan simpan hasilnya
        $result = $stmt->execute();

        // Tutup statement
        $stmt->close();

        return $result;
    }
}

/**
 * PROSES INSERT (Tambah Data Baru)
 * Dipanggil ketika tombol 'submit' ditekan di form
 */
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama_produk = $_POST['nama_produk'];
    $material = $_POST['material'];
    $harga = $_POST['harga'];

    // Membuat instance class Database dan ProdukToko
    $db = new Database();
    $conn = $db->getConnection();
    $barang = new ProdukToko($conn);

    // Validasi: cek apakah semua field sudah diisi dengan benar
    if (
        empty($nama_produk) ||
        $material == '--pilih--' ||
        empty($harga)
    ) {
        echo "
        <div class='card'>
            <h3>❗ Error</h3>
            <p>Semua field harus diisi!</p>
            <a class='btn-back' href='index.php'>Kembali</a>
        </div>";

    } else {
        // Proses insert data menggunakan method dari class ProdukToko
        if ($barang->insert($nama_produk, $material, $harga)) {
            echo "Data berhasil disimpan. <a href='index.php'>Kembali</a>";
        } else {
            echo "Gagal menyimpan data. <a href='index.php'>Kembali</a>";
        }
    }
}

/**
 * PROSES DELETE (Hapus Data)
 * Dipanggil ketika link 'Delete' diklik di tabel
 */
if (isset($_GET['delete'])) {

    // Ambil ID produk dari parameter URL
    $id = $_GET['delete'];

    // Membuat instance class Database dan ProdukToko
    $db = new Database();
    $conn = $db->getConnection();
    $barang = new ProdukToko($conn);

    // Proses delete data menggunakan method dari class ProdukToko
    if ($barang->delete($id)) {
        // Jika berhasil, redirect ke halaman index
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menghapus data. <a href='index.php'>Kembali</a>";
    }
}

/**
 * PROSES UPDATE (Edit Data)
 * Dipanggil ketika tombol 'update' ditekan di form edit
 */
if (isset($_POST['update'])) {
    // Ambil data dari form
    $id = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $material = $_POST['material'];
    $harga = $_POST['harga'];

    // Membuat instance class Database dan ProdukToko
    $db = new Database();
    $conn = $db->getConnection();
    $barang = new ProdukToko($conn);

    // Proses update data menggunakan method dari class ProdukToko
    if ($barang->update($id, $nama_produk, $material, $harga)) {
        // Jika berhasil, redirect ke halaman index
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal update data. <a href='index.php'>Kembali</a>";
    }
}

?>