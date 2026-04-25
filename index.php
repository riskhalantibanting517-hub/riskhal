<?php
session_start();
require_once 'koneksi.php';

$editData = null;
if (!empty($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare('SELECT id_karyawan, nama_karyawan, jabatan, tanggal_masuk, gaji FROM karyawan WHERE id_karyawan = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
    $stmt->close();
}

$message = null;
if (!empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

$result = $conn->query('SELECT * FROM karyawan ORDER BY id_karyawan DESC');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">Employee Manager</h1>
            <p class="mb-0 text-muted">Aplikasi pengelolaan data karyawan sederhana dengan CRUD.</p>
        </div>
        <a href="index.php" class="btn btn-outline-secondary">Reset Form</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message['text']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <?php echo $editData ? 'Edit Karyawan' : 'Tambah Karyawan'; ?>
        </div>
        <div class="card-body">
            <form action="save.php" method="post" novalidate>
                <?php if ($editData): ?>
                    <input type="hidden" name="id_karyawan" value="<?php echo htmlspecialchars($editData['id_karyawan']); ?>">
                <?php endif; ?>
                <div class="row gy-3">
                    <div class="col-md-6">
                        <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" maxlength="100" required value="<?php echo $editData ? htmlspecialchars($editData['nama_karyawan']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" maxlength="50" required value="<?php echo $editData ? htmlspecialchars($editData['jabatan']) : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required value="<?php echo $editData ? htmlspecialchars($editData['tanggal_masuk']) : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="gaji" class="form-label">Gaji</label>
                        <input type="number" class="form-control" id="gaji" name="gaji" min="1000000" required value="<?php echo $editData ? htmlspecialchars($editData['gaji']) : ''; ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><?php echo $editData ? 'Update' : 'Simpan'; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Daftar Karyawan</div>
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Tanggal Masuk</th>
                        <th>Gaji</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_karyawan']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_karyawan']); ?></td>
                            <td><?php echo htmlspecialchars($row['jabatan']); ?></td>
                            <td><?php echo htmlspecialchars($row['tanggal_masuk']); ?></td>
                            <td>Rp <?php echo number_format($row['gaji'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="index.php?edit=<?php echo htmlspecialchars($row['id_karyawan']); ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                <a href="delete.php?id=<?php echo htmlspecialchars($row['id_karyawan']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus karyawan ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada data karyawan.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
