<?php
session_start();
require_once '../../config/db_connect.php';
include '../../includes/header.php';
include '../../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: ../../login.php');
    exit;
}

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $user_id = $_SESSION['user_id'];

    $pdo->beginTransaction();
    try {
        // Periksa stok buku
        $stmt = $pdo->prepare("SELECT stock FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();

        if ($book && $book['stock'] > 0) {
            // Kurangi stok
            $stmt = $pdo->prepare("UPDATE books SET stock = stock - 1 WHERE id = ?");
            $stmt->execute([$book_id]);

            // Tambah data peminjaman
            $stmt = $pdo->prepare("INSERT INTO borrowings (user_id, book_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $book_id]);

            $pdo->commit();
            $success = "Buku berhasil dipinjam!";
        } else {
            $pdo->rollBack();
            $error = "Buku tidak tersedia!";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Gagal meminjam buku: " . $e->getMessage();
    }
    header('Location: search_books.php?success=' . urlencode($success ?? '') . '&error=' . urlencode($error ?? ''));
    exit;
}
?>

<div class="container mx-auto mt-12 flex-grow">
    <div class="flex">
        <div class="w-1/5">
            <?php include '../../includes/sidebar.php'; ?>
        </div>
        <div class="w-4/5 pl-12">
            <h2 class="text-4xl font-bold mb-8 text-gray-800">Pinjam Buku</h2>
            <p class="text-lg text-gray-600">Silakan cari buku untuk dipinjam dari halaman pencarian.</p>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>