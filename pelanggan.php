<?php
/**
 * File: pelanggan.php
 * Halaman untuk mengelola data pelanggan toko
 * Berisi form input dan tabel daftar pelanggan
 */

// Mulai session untuk cek apakah user sudah login
session_start();

// Redirect ke login jika belum login
if (!isset($_SESSION['users'])) {
    header('location: login.php');
    exit;
}

// Import file yang diperlukan
require_once 'config.php';
require_once 'class_pelanggan.php';

// Membuat instance class Database dan Pelanggan
$db = new Database();
$conn = $db->getConnection();
$pelanggan = new Pelanggan($conn);

// Variabel untuk menyimpan data pelanggan yang akan diedit
$editData = null;

// Jika ada parameter edit di URL, ambil data pelanggan tersebut
if (isset($_GET['edit'])) {
    $editData = $pelanggan->getByID($_GET['edit']);
}

// Ambil semua data pelanggan untuk ditampilkan di tabel
$dataPelanggan = $pelanggan->getAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Toko</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <strong>Sistem Toko</strong>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Data Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="pelanggan.php">Data Pelanggan</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <!-- Card Form Input -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <?php if ($editData): ?>
                    <i class="bi bi-pencil-square"></i> Edit Data Pelanggan
                <?php else: ?>
                    <i class="bi bi-plus-circle"></i> Tambah Data Pelanggan
                <?php endif; ?>
            </h5>
        </div>
        <div class="card-body">
            <form action="aksi_pelanggan.php" method="post">
                <?php if ($editData): ?>
                    <input type="hidden" name="id_pelanggan" value="<?= htmlspecialchars($editData['id_pelanggan']) ?>">
                <?php endif; ?>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Nama Pelanggan</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nama_pelanggan"
                               value="<?= $editData ? htmlspecialchars($editData['nama_pelanggan']) : '' ?>"
                               placeholder="Contoh: Budi Santoso" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Alamat</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="alamat" rows="3"
                                  placeholder="Jl. Contoh No. 123, Jakarta" required><?= $editData ? htmlspecialchars($editData['alamat']) : '' ?></textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">No. Telepon</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="no_telepon"
                               value="<?= $editData ? htmlspecialchars($editData['no_telepon']) : '' ?>"
                               placeholder="Contoh: 081234567890" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" name="email"
                               value="<?= $editData ? htmlspecialchars($editData['email']) : '' ?>"
                               placeholder="Contoh: pelanggan@email.com">
                        <small class="text-muted">Opsional</small>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-9 offset-sm-3">
                        <?php if ($editData): ?>
                            <button type="submit" name="update" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Update
                            </button>
                            <a href="pelanggan.php" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        <?php else: ?>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Tabel Data -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Pelanggan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Pelanggan</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th>Tanggal Daftar</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dataPelanggan)): ?>
                            <?php $no = 1; foreach ($dataPelanggan as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td><?= $row['email'] ? htmlspecialchars($row['email']) : '-' ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal_daftar'])) ?></td>
                                <td class="text-center">
                                    <a href="pelanggan.php?edit=<?= $row['id_pelanggan'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="aksi_pelanggan.php?delete=<?= $row['id_pelanggan'] ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin hapus data pelanggan ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada data pelanggan.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
