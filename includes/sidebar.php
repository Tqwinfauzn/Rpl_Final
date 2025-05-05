<?php

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit;
}
?>

<aside class="bg-green-700 text-white h-screen p-6">
    <h2 class="text-2xl font-bold mb-6">Menu</h2>
    <ul>
        <?php if ($_SESSION['role'] == 'student'): ?>
            <li class="mb-4"><a href="dashboard.php" class="block p-4 hover:bg-green-600 rounded-lg text-xl">Dashboard</a></li>
            <li class="mb-4"><a href="search_books.php" class="block p-4 hover:bg-green-600 rounded-lg text-xl">Cari Buku</a></li>
            <li class="mb-4"><a href="history.php" class="block p-4 hover:bg-green-600 rounded-lg text-xl">Riwayat Peminjaman</a></li>
        <?php elseif ($_SESSION['role'] == 'librarian'): ?>
            <li class="mb-4"><a href="dashboard.php" class="block p-4 hover:bg-green-600 rounded-lg text-xl">Dashboard</a></li>
            <li class="mb-4"><a href="manage_books.php" class="block p-4 hover:bg-green-600 rounded-lg text-xl">Kelola Buku</a></li>
            <li class="mb-4"><a href="manage_transactions.php" class="block p-4 hover:bg-green-600 rounded-lg text-xl">Kelola Transaksi</a></li>
        <?php elseif ($_SESSION['role'] == 'admin'): ?>
            <li class="mb-4"><a href="dashboard.php" class="block p-4 hover:bg-green-600 rounded-lg text-xl">Dashboard</a></li>
            <li class="mb-4"><a href="manage_users.php" class="block p-4 hover:bg-green-600 rounded-lg text-xl">Kelola Pengguna</a></li>
            <li class="mb-4"><a href="manage_books.php" class="block p-4 hover:bg-green-600 rounded-lg text-xl">Kelola Buku</a></li>
        <?php endif; ?>
    </ul>
</aside>