<?php
session_start();
require_once '../../config/db_connect.php';
include '../../includes/header.php';
include '../../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') {
    header('Location: ../../login.php');
    exit;
}

// Proses pengembalian buku
if (isset($_GET['return'])) {
    $borrow_id = $_GET['return'];
    $pdo->beginTransaction();
    try {
        // Update status peminjaman
        $stmt = $pdo->prepare("UPDATE borrowings SET status = 'returned' WHERE id = ?");
        $stmt->execute([$borrow_id]);

        // Tambah stok buku
        $stmt = $pdo->prepare("UPDATE books SET stock = stock + 1 WHERE id = (SELECT book_id FROM borrowings WHERE id = ?)");
        $stmt->execute([$borrow_id]);

        $pdo->commit();
        $success = "Buku berhasil dikembalikan!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Gagal mengembalikan buku: " . $e->getMessage();
    }
}

// Ambil daftar transaksi
$stmt = $pdo->query("SELECT b.id, u.name AS user_name, bk.title AS book_title, b.borrow_date, b.status 
                    FROM borrowings b 
                    JOIN users u ON b.user_id = u.id 
                    JOIN books bk ON b.book_id = bk.id");
$transactions = $stmt->fetchAll();
?>

<div class="container mx-auto mt-12 flex-grow">
    <div class="flex">
        <div class="w-1/5">
            <?php include '../../includes/sidebar.php'; ?>
        </div>
        <div class="w-4/5 pl-12">
            <h2 class="text-4xl font-bold mb-8 text-gray-800">Kelola Transaksi</h2>
            <?php if (isset($success)): ?>
                <p class="text-green-500 mb-4"><?php echo $success; ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p class="text-red-500 mb-4"><?php echo $error; ?></p>
            <?php endif; ?>
            <!-- Tabel Daftar Transaksi -->
            <table class="w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="p-4 text-left text-lg">Pengguna</th>
                        <th class="p-4 text-left text-lg">Judul Buku</th>
                        <th class="p-4 text-left text-lg">Tanggal Pinjam</th>
                        <th class="p-4 text-left text-lg">Status</th>
                        <th class="p-4 text-left text-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr class="border-b">
                            <td class="p-4"><?php echo htmlspecialchars($transaction['user_name']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($transaction['book_title']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($transaction['borrow_date']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($transaction['status'] == 'borrowed' ? 'Dipinjam' : 'Dikembalikan'); ?></td>
                            <td class="p-4">
                                <?php if ($transaction['status'] == 'borrowed'): ?>
                                    <a href="?return=<?php echo $transaction['id']; ?>" onclick="return confirm('Yakin ingin menandai buku ini sebagai dikembalikan?')" class="bg-green-500 text-white p-2 rounded-full hover:bg-green-600 text-lg">Kembalikan</a>
                                <?php else: ?>
                                    <span class="text-gray-500">Sudah Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>