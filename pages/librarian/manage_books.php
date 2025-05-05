<?php
session_start();
require_once '../../config/db_connect.php';
include '../../includes/header.php';
include '../../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'librarian') {
    header('Location: ../../login.php');
    exit;
}

// Tambah atau edit buku
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $stock = $_POST['stock'];

    if (isset($_POST['id'])) {
        // Update buku
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, stock = ? WHERE id = ?");
        $stmt->execute([$title, $author, $stock, $id]);
    } else {
        // Tambah buku
        $stmt = $pdo->prepare("INSERT INTO books (title, author, stock) VALUES (?, ?, ?)");
        $stmt->execute([$title, $author, $stock]);
    }
}

// Ambil daftar buku
$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll();
?>

<div class="container mx-auto mt-12 flex-grow">
    <div class="flex">
        <div class="w-1/5">
            <?php include '../../includes/sidebar.php'; ?>
        </div>
        <div class="w-4/5 pl-12">
            <h2 class="text-4xl font-bold mb-8 text-gray-800">Kelola Buku</h2>
            <!-- Form Tambah/Edit Buku -->
            <form method="POST" class="mb-8 bg-white p-6 rounded-lg shadow-md">
                <input type="hidden" name="id" id="book_id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-lg mb-2">Judul</label>
                    <input type="text" name="title" id="title" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-lg mb-2">Penulis</label>
                    <input type="text" name="author" id="author" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-lg mb-2">Stok</label>
                    <input type="number" name="stock" id="stock" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>
                <button type="submit" class="bg-green-500 text-white p-3 rounded-full hover:bg-green-600 transition duration-300 text-lg">Simpan Buku</button>
            </form>
            <!-- Tabel Daftar Buku -->
            <table class="w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="p-4 text-left text-lg">Judul</th>
                        <th class="p-4 text-left text-lg">Penulis</th>
                        <th class="p-4 text-left text-lg">Stok</th>
                        <th class="p-4 text-left text-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr class="border-b">
                            <td class="p-4"><?php echo htmlspecialchars($book['title']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($book['author']); ?></td>
                            <td class="p-4"><?php echo $book['stock']; ?></td>
                            <td class="p-4">
                                <button onclick="editBook(<?php echo $book['id']; ?>, '<?php echo htmlspecialchars($book['title']); ?>', '<?php echo htmlspecialchars($book['author']); ?>', <?php echo $book['stock']; ?>)" class="bg-yellow-500 text-white p-2 rounded-full hover:bg-yellow-600 mr-2 text-lg">Edit</button>
                                <a href="delete_book.php?id=<?php echo $book['id']; ?>" onclick="return confirm('Yakin ingin menghapus buku ini?')" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 text-lg">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function editBook(id, title, author, stock) {
        document.getElementById('book_id').value = id;
        document.getElementById('title').value = title;
        document.getElementById('author').value = author;
        document.getElementById('stock').value = stock;
    }
</script>

<?php include '../../includes/footer.php'; ?>