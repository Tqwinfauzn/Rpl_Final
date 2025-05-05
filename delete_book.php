<?php
session_start();
require_once 'config/db_connect.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['librarian', 'admin'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: pages/librarian/manage_books.php?success=Buku berhasil dihapus');
} else {
    header('Location: pages/librarian/manage_books.php?error=ID buku tidak valid');
}
exit;
