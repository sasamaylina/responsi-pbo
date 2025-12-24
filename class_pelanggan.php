<?php
/**
 * Class Pelanggan
 * Class untuk mengelola data pelanggan toko
 * Menggunakan konsep OOP (Object Oriented Programming)
 * dengan method CRUD (Create, Read, Update, Delete)
 */

require_once 'config.php';

class Pelanggan {
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
     * Method untuk mengambil semua data pelanggan
     * @return array - Array berisi semua data pelanggan
     */
    public function getAll() {
        // Query untuk select semua data dari tabel pelanggan
        $result = $this->conn->query("SELECT * FROM pelanggan ORDER BY tanggal_daftar DESC");

        // Array untuk menampung data
        $data = [];

        // Loop untuk memasukkan setiap row ke dalam array
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Method untuk mengambil data pelanggan berdasarkan ID
     * @param int $id - ID pelanggan yang ingin diambil
     * @return array|null - Data pelanggan atau null jika tidak ditemukan
     */
    public function getById($id) {
        // Menggunakan prepared statement untuk keamanan (mencegah SQL injection)
        $stmt = $this->conn->prepare("SELECT * FROM pelanggan WHERE id_pelanggan=?");

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
     * Method untuk menambah data pelanggan baru
     * @param string $nama - Nama pelanggan
     * @param string $alamat - Alamat pelanggan
     * @param string $no_telepon - Nomor telepon pelanggan
     * @param string $email - Email pelanggan (opsional)
     * @return bool - True jika berhasil, false jika gagal
     */
    public function insert($nama, $alamat, $no_telepon, $email) {
        // Prepared statement untuk insert data
        $stmt = $this->conn->prepare(
            "INSERT INTO pelanggan (nama_pelanggan, alamat, no_telepon, email)
             VALUES (?, ?, ?, ?)"
        );

        // Bind parameter (s = string)
        $stmt->bind_param("ssss", $nama, $alamat, $no_telepon, $email);

        // Eksekusi query dan simpan hasilnya
        $result = $stmt->execute();

        // Tutup statement
        $stmt->close();

        return $result;
    }

    /**
     * Method untuk mengupdate data pelanggan
     * @param int $id - ID pelanggan yang akan diupdate
     * @param string $nama - Nama pelanggan baru
     * @param string $alamat - Alamat pelanggan baru
     * @param string $no_telepon - Nomor telepon baru
     * @param string $email - Email baru
     * @return bool - True jika berhasil, false jika gagal
     */
    public function update($id, $nama, $alamat, $no_telepon, $email) {
        // Prepared statement untuk update data
        $stmt = $this->conn->prepare(
            "UPDATE pelanggan SET
             nama_pelanggan=?, alamat=?, no_telepon=?, email=?
             WHERE id_pelanggan=?"
        );

        // Bind parameter (s = string, i = integer)
        $stmt->bind_param("ssssi", $nama, $alamat, $no_telepon, $email, $id);

        // Eksekusi query dan simpan hasilnya
        $result = $stmt->execute();

        // Tutup statement
        $stmt->close();

        return $result;
    }

    /**
     * Method untuk menghapus data pelanggan
     * @param int $id - ID pelanggan yang akan dihapus
     * @return bool - True jika berhasil, false jika gagal
     */
    public function delete($id) {
        // Prepared statement untuk delete data
        $stmt = $this->conn->prepare("DELETE FROM pelanggan WHERE id_pelanggan=?");

        // Bind parameter (i = integer)
        $stmt->bind_param("i", $id);

        // Eksekusi query dan simpan hasilnya
        $result = $stmt->execute();

        // Tutup statement
        $stmt->close();

        return $result;
    }

    /**
     * Method untuk cek apakah nomor telepon sudah terdaftar
     * @param string $no_telepon - Nomor telepon yang akan dicek
     * @param int $exclude_id - ID yang dikecualikan (untuk update)
     * @return bool - True jika sudah ada, false jika belum
     */
    public function isTeleponExists($no_telepon, $exclude_id = null) {
        if ($exclude_id) {
            // Cek nomor telepon kecuali untuk ID tertentu (saat update)
            $stmt = $this->conn->prepare(
                "SELECT id_pelanggan FROM pelanggan
                 WHERE no_telepon=? AND id_pelanggan!=?"
            );
            $stmt->bind_param("si", $no_telepon, $exclude_id);
        } else {
            // Cek nomor telepon (saat insert)
            $stmt = $this->conn->prepare(
                "SELECT id_pelanggan FROM pelanggan WHERE no_telepon=?"
            );
            $stmt->bind_param("s", $no_telepon);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();

        return $exists;
    }
}
?>
