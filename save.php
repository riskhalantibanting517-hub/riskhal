<?php
session_start();
require_once 'koneksi.php';

$nama = trim($_POST['nama_karyawan'] ?? '');
$jabatan = trim($_POST['jabatan'] ?? '');
$tanggal = $_POST['tanggal_masuk'] ?? '';
$gaji = intval($_POST['gaji'] ?? 0);
$id = intval($_POST['id_karyawan'] ?? 0);

if ($nama === '' || $jabatan === '') {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Nama karyawan dan jabatan tidak boleh kosong.'];
    header('Location: index.php' . ($id ? '?edit=' . $id : ''));
    exit;
}
if ($gaji < 1000000) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gaji harus minimal 1.000.000.'];
    header('Location: index.php' . ($id ? '?edit=' . $id : ''));
    exit;
}
if (!$tanggal) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Tanggal masuk harus diisi.'];
    header('Location: index.php' . ($id ? '?edit=' . $id : ''));
    exit;
}

if ($id > 0) {
    $stmt = $conn->prepare('UPDATE karyawan SET nama_karyawan = ?, jabatan = ?, tanggal_masuk = ?, gaji = ? WHERE id_karyawan = ?');
    $stmt->bind_param('sssii', $nama, $jabatan, $tanggal, $gaji, $id);
    $success = $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = ['type' => $success ? 'success' : 'danger', 'text' => $success ? 'Data karyawan berhasil diperbarui.' : 'Gagal memperbarui data karyawan.'];
} else {
    $stmt = $conn->prepare('INSERT INTO karyawan (nama_karyawan, jabatan, tanggal_masuk, gaji) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('sssi', $nama, $jabatan, $tanggal, $gaji);
    $success = $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = ['type' => $success ? 'success' : 'danger', 'text' => $success ? 'Karyawan baru berhasil ditambahkan.' : 'Gagal menambahkan data karyawan.'];
}

header('Location: index.php');
exit;
