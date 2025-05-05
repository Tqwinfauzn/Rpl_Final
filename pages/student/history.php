<?php
session_start();
require_once '../../config/db_connect.php';
include '../../includes/header.php';
include '../../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: ../../login.php');
    exit;
}

// Ambil riwayat peminjaman
$stmt = $pdo->prepare("SELECT b.id, bk.title AS book_title, b.borrow_date, b.status 
                      FROM borrowings b 
                      JOIN books bk ON b.book_id = bk.id 
                      WHERE b.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$history = $stmt->fetchAll();
?>

<div class="container mx-auto mt-12 flex-grow">
    <div class="flex">
        <div class="w-1/5">
            <?php include '../../includes/sidebar.php'; ?>
        </div>
        <div class="w-4/5 pl-12">
            <h2 class="text-4xl font-bold mb-8 text-gray-800">Riwayat Peminjaman</h2>
            <!-- Tabel Riwayat Peminjaman -->
            <table class="w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="p-4 text-left text-lg">Judul Buku</th>
                        <th class="p-4 text-left text-lg">Tanggal Pinjam</th>
                        <th class="p-4 text-left text-lg">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($history)): ?>
                        <tr>
                            <td colspan="3" class="p-4 text-center">Belum ada riwayat peminjaman.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($history as $item): ?>
                            <tr class="border-b">
                                <td class="p-4"><?php echo htmlspecialchars($item['book_title']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($item['borrow_date']); ?></td>
                                <td class="p-4"><?php echo htmlspecialchars($item['status'] == 'borrowed' ? 'Dipinjam' : 'Dikembalikan'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>