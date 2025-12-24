<?php
session_start();

if (!isset($_SESSION['users'])) {
    header('location: login.php');
    exit;
}

require_once 'config.php';
require_once 'aksi.php';

$db = new Database();
$conn = $db->getConnection();
$barang = new ProdukToko($conn);

$editData = null;
if (isset($_GET['edit'])) {
    $editData = $barang->getByID($_GET['edit']);
}

$dataProduk = $barang->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Sistem Toko</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

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
          <a class="nav-link active" href="index.php">Data Produk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pelanggan.php">Data Pelanggan</a>
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
                    <i class="bi bi-pencil-square"></i> Edit Data Produk
                <?php else: ?>
                    <i class="bi bi-plus-circle"></i> Tambah Data Produk
                <?php endif; ?>
            </h5>
        </div>
        <div class="card-body">
            <form action="aksi.php" method="post">
                <?php if ($editData): ?>
                    <input type="hidden" name="id_produk" value="<?= htmlspecialchars($editData['id_produk']) ?>">
                <?php endif; ?>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Nama Produk</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nama_produk"
                               value="<?= $editData ? htmlspecialchars($editData['nama_produk']) : '' ?>"
                               placeholder="Contoh: Meja, Kursi, Lemari" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Material</label>
                    <div class="col-sm-9">
                        <select class="form-select" name="material" required>
                            <?php if (!$editData): ?>
                                <option value="">-- Pilih Material --</option>
                            <?php endif; ?>
                            <option value="Kayu" <?= ($editData && $editData['material'] == 'Kayu') ? 'selected' : '' ?>>Kayu</option>
                            <option value="Besi" <?= ($editData && $editData['material'] == 'Besi') ? 'selected' : '' ?>>Besi</option>
                            <option value="Bambu" <?= ($editData && $editData['material'] == 'Bambu') ? 'selected' : '' ?>>Bambu</option>
                            <option value="Tanah" <?= ($editData && $editData['material'] == 'Tanah') ? 'selected' : '' ?>>Tanah</option>
                            <option value="PVC" <?= ($editData && $editData['material'] == 'PVC') ? 'selected' : '' ?>>PVC</option>
                            <option value="Plastik" <?= ($editData && $editData['material'] == 'Plastik') ? 'selected' : '' ?>>Plastik</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Harga Satuan</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="harga"
                               value="<?= $editData ? htmlspecialchars($editData['harga']) : '' ?>"
                               placeholder="Contoh: 500000" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-9 offset-sm-3">
                        <?php if ($editData): ?>
                            <button type="submit" name="update" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Update
                            </button>
                            <a href="index.php" class="btn btn-secondary">
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
            <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Produk</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Produk</th>
                            <th>Material</th>
                            <th>Harga Satuan</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dataProduk)): ?>
                            <?php $no = 1; foreach ($dataProduk as $row): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                <td><?= htmlspecialchars($row['material'] ?? '-') ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <a href="index.php?edit=<?= $row['id_produk'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="aksi.php?delete=<?= $row['id_produk'] ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin hapus data ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada data produk.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
