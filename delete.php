<?php
session_start();
require_once 'koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare('DELETE FROM karyawan WHERE id_karyawan = ?');
    $stmt->bind_param('i', $id);
    $success = $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = ['type' => $success ? 'success' : 'danger', 'text' => $success ? 'Data karyawan berhasil dihapus.' : 'Gagal menghapus data karyawan.'];
} else {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'ID karyawan tidak valid.'];
}

header('Location: index.php');
exit;
