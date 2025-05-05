<?php
session_start();
require_once '../../config/db_connect.php';
include '../../includes/header.php';
include '../../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: ../../login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM borrowings WHERE user_id = ? AND status = 'borrowed'");
$stmt->execute([$_SESSION['user_id']]);
$borrowed_books = $stmt->fetchColumn();
?>

<div class="container mx-auto mt-12 flex-grow">
    <div class="flex">
        <div class="w-1/5">
            <?php include '../../includes/sidebar.php'; ?>
        </div>
        <div class="w-4/5 pl-12">
            <h2 class="text-4xl font-bold mb-8 text-gray-800">Dashboard Mahasiswa</h2>
            <div class="grid grid-cols-2 gap-8">
                <div class="bg-green-500 text-white p-8 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                    <h3 class="text-2xl font-semibold">Buku Dipinjam</h3>
                    <p class="text-4xl mt-4"><?php echo $borrowed_books; ?></p>
                </div>
                <div class="bg-green-500 text-white p-8 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                    <h3 class="text-2xl font-semibold">Buku Tersedia</h3>
                    <p class="text-4xl mt-4">
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) FROM books WHERE stock > 0");
                        echo $stmt->fetchColumn();
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>