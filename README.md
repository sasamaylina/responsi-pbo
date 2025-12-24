# Sistem Toko - Aplikasi CRUD dengan OOP PHP

Aplikasi manajemen data toko yang dibuat menggunakan konsep **Object Oriented Programming (OOP)** PHP dengan dua form input berbeda: **Data Produk** dan **Data Pelanggan**.

## Fitur Aplikasi

### 1. Sistem Login & Register
- Login dengan username dan password
- Register akun baru
- Session management

### 2. Form Data Produk
- Tambah produk baru (nama, material, harga)
- Edit produk
- Hapus produk
- Tampilan daftar produk dalam tabel

### 3. Form Data Pelanggan
- Tambah pelanggan baru (nama, alamat, no telepon, email)
- Edit data pelanggan
- Hapus pelanggan
- Tampilan daftar pelanggan dalam tabel
- Validasi nomor telepon (tidak boleh duplikat)

## Konsep OOP yang Diterapkan

### Class Database (`config.php`)
- Mengelola koneksi ke database MySQL
- Method: `connect()`, `getConnection()`, `close()`

### Class ProdukToko (`aksi.php`)
- Mengelola operasi CRUD untuk produk
- Method: `getAll()`, `getById()`, `insert()`, `update()`, `delete()`

### Class Pelanggan (`class_pelanggan.php`)
- Mengelola operasi CRUD untuk pelanggan
- Method: `getAll()`, `getById()`, `insert()`, `update()`, `delete()`, `isTeleponExists()`

## Struktur Database

Database: `db_pbo`

### Tabel `users`
- `id` (Primary Key)
- `username`
- `password` (MD5)

### Tabel `produk`
- `id_produk` (Primary Key)
- `nama_produk`
- `material`
- `harga`
- `created_at`

### Tabel `pelanggan`
- `id_pelanggan` (Primary Key)
- `nama_pelanggan`
- `alamat`
- `no_telepon` (Unique)
- `email`
- `tanggal_daftar`

## Cara Instalasi

### 1. Import Database
Jalankan file `database.sql` melalui phpMyAdmin atau MySQL CLI:

```bash
mysql -u root -p db_pbo < database.sql
```

Atau manual via phpMyAdmin:
1. Buka phpMyAdmin
2. Buat database baru dengan nama `db_pbo`
3. Import file `database.sql`

### 2. Konfigurasi Database
File konfigurasi ada di `config.php`. Pastikan settingan sesuai:

```php
private $host = "127.0.0.1";      // Host database
private $username = "root";        // Username MySQL
private $password = "";            // Password MySQL
private $dbname = "db_pbo";        // Nama database
```

### 3. Jalankan Aplikasi
Jika menggunakan Laravel Herd, aplikasi otomatis berjalan di:
```
http://pbo.test
```

Atau jika menggunakan built-in PHP server:
```bash
php -S localhost:8000
```

### 4. Login
- Register akun baru melalui halaman register
- Login menggunakan akun yang sudah dibuat

## Struktur File

```
pbo/
├── config.php              # Class Database untuk koneksi
├── login.php               # Halaman login
├── register.php            # Halaman register
├── logout.php              # Proses logout
├── index.php               # Halaman utama (Data Produk)
├── aksi.php                # Class ProdukToko & proses CRUD produk
├── pelanggan.php           # Halaman Data Pelanggan
├── class_pelanggan.php     # Class Pelanggan untuk CRUD
├── aksi_pelanggan.php      # Proses CRUD pelanggan
├── style.css               # File CSS untuk styling
├── database.sql            # Script SQL untuk membuat tabel
└── README.md               # Dokumentasi (file ini)
```

## Teknologi yang Digunakan

- **PHP 8.x** - Backend programming
- **MySQL** - Database
- **Bootstrap 5.3** - CSS Framework
- **OOP (Object Oriented Programming)** - Design pattern
- **Prepared Statements** - Keamanan dari SQL Injection

## Screenshot

### Halaman Login
Form login dengan validasi username dan password.

### Halaman Data Produk
Form input produk dengan field: nama produk, material, dan harga.

### Halaman Data Pelanggan
Form input pelanggan dengan field: nama, alamat, no telepon, dan email.

## Keamanan

1. **Prepared Statements** - Mencegah SQL Injection
2. **Session Management** - Proteksi halaman yang memerlukan login
3. **Password Hashing** - Password di-encrypt dengan MD5
4. **Input Sanitization** - Validasi dan sanitasi input user
5. **htmlspecialchars()** - Mencegah XSS attack

## Catatan

- Password di-encrypt menggunakan MD5 (untuk produksi sebaiknya gunakan `password_hash()`)
- Gunakan `127.0.0.1` bukan `localhost` untuk koneksi database di Herd/Valet
- Semua file sudah diberi komentar lengkap untuk memudahkan pemahaman

## Author

Project ini dibuat sebagai tugas Pemrograman Berorientasi Objek (PBO).

---

**Happy Coding!** 🚀
