<?php
// Konfigurasi database
$host     = 'localhost';
$dbname   = 'digital_library';
$username = 'root';      // Ganti sesuai konfigurasi MySQL Anda
$password = '';          // Kosongkan jika tidak pakai password (XAMPP default)

// Inisialisasi PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
