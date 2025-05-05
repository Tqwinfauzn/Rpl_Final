<?php
session_start();

// Hapus semua data sesi
$_SESSION = array();

// Hancurkan sesi
session_destroy();

// Redirect ke halaman login dengan pesan sukses
header('Location: /digital-library/login.php?message=' . urlencode('Anda telah keluar dari sistem.'));
exit;
