<?php

/**
 * Class Database
 * Class untuk mengelola koneksi database MySQL
 * Menggunakan konsep OOP (Object Oriented Programming)
 */
class Database {
    // Properti untuk menyimpan konfigurasi database (private = hanya bisa diakses dalam class ini)
    private $host = "127.0.0.1";      // IP localhost (gunakan 127.0.0.1 untuk TCP/IP, bukan "localhost")
    private $username = "root";        // Username MySQL
    private $password = "";            // Password MySQL (kosong untuk default)
    private $dbname = "db_pbo";        // Nama database yang digunakan
    private $conn;                     // Variabel untuk menyimpan koneksi database

    /**
     * Constructor - otomatis dijalankan saat class dibuat
     * Langsung membuat koneksi ke database
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * Method untuk membuat koneksi ke database
     * Menggunakan mysqli (MySQL Improved Extension)
     */
    public function connect() {
        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->dbname,
        );

        // Cek apakah koneksi berhasil atau tidak
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    /**
     * Method untuk mendapatkan koneksi database
     * Return: object mysqli yang bisa digunakan untuk query
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * Method untuk menutup koneksi database
     * Sebaiknya dipanggil setelah selesai menggunakan database
     */
    public function close() {
        $this->conn->close();
    }
}
?>