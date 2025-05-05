<?php
session_start();
require_once '../../config/db_connect.php';
include '../../includes/header.php';
include '../../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: ../../login.php');
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
$stmt->execute(["%$search%", "%$search%"]);
$books = $stmt->fetchAll();
?>

<div class="container mx-auto mt-12 flex-grow">
    <div class="flex">
        <div class="w-1/5">
            <?php include '../../includes/sidebar.php'; ?>
        </div>
        <div class="w-4/5 pl-12">
            <h2 class="text-4xl font-bold mb-8 text-gray-800">Cari Buku</h2>
            <?php if (isset($_GET['success'])): ?>
                <p class="text-green-500 mb-4"><?php echo htmlspecialchars($_GET['success']); ?></p>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <p class="text-red-500 mb-4"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            <form method="GET" class="mb-8">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="p-4 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 w-1/2 text-lg" placeholder="Cari berdasarkan judul atau penulis">
                <button type="submit" class="bg-green-500 text-white p-4 rounded-full hover:bg-green-600 transition duration-300 text-lg ml-2">Cari</button>
            </form>
            <div class="grid grid-cols-3 gap-8">
                <?php if (empty($books)): ?>
                    <p class="text-gray-600 text-lg">Tidak ada buku yang ditemukan.</p>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <div class="bg-white p-6 rounded-lg shadow-lg">
                            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p class="text-gray-600">Penulis: <?php echo htmlspecialchars($book['author']); ?></p>
                            <p class="text-gray-600">Stok: <?php echo $book['stock']; ?></p>
                            <?php if ($book['stock'] > 0): ?>
                                <a href="borrow.php?book_id=<?php echo $book['id']; ?>" class="bg-green-500 text-white p-3 rounded-full hover:bg-green-600 mt-4 inline-block text-lg">Pinjam</a>
                            <?php else: ?>
                                <span class="bg-gray-500 text-white p-3 rounded-full mt-4 inline-block text-lg">Habis</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>