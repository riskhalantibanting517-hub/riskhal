CREATE DATABASE IF NOT EXISTS employee_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE employee_manager;

CREATE TABLE IF NOT EXISTS karyawan (
    id_karyawan INT AUTO_INCREMENT PRIMARY KEY,
    nama_karyawan VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50) NOT NULL,
    tanggal_masuk DATE NOT NULL,
    gaji INT NOT NULL
);
