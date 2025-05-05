<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
session_start();

// Bersihkan sesi sebelum login
session_unset();
session_destroy();
session_start();

require_once 'config/db_connect.php';
include 'includes/header.php';
include 'includes/navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->execute([$username, $role]);
    $user = $stmt->fetch();

    echo "<pre>";
    echo "Input POST: ";
    var_dump($_POST);
    echo "User dari database: ";
    var_dump($user);
    if ($user) {
        $passwordMatch = password_verify($password, $user['password']);
        echo "Password match: " . ($passwordMatch ? 'true' : 'false') . "<br>";
        echo "Password hash dari database: " . $user['password'] . "<br>";
    } else {
        echo "Query tidak menemukan data pengguna.\n";
    }
    echo "Session sebelum set: ";
    var_dump($_SESSION);
    if ($user && isset($passwordMatch) && $passwordMatch) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        echo "Session setelah set: ";
        var_dump($_SESSION);
        if ($user['role'] == 'admin') {
            header('Location: pages/admin/dashboard.php');
            exit;
        } elseif ($user['role'] == 'librarian') {
            header('Location: pages/librarian/dashboard.php');
            exit;
        } else {
            header('Location: pages/student/dashboard.php');
            exit;
        }
    } else {
        $error = "Username, password, atau peran salah!";
    }
    echo "</pre>";
}

?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-4xl rounded-xl shadow-lg overflow-hidden">
        <!-- Bagian Kiri: Teks Sambutan -->
        <div class="w-1/2 bg-green-500 p-12 text-white flex flex-col justify-center">
            <h1 class="text-4xl font-bold mb-4">Selamat Datang Kembali!</h1>
            <p class="text-lg">Silakan masuk dengan akun Anda sesuai peran Anda.</p>
            <a href="register.php" class="mt-8 inline-block bg-transparent border-2 border-white text-white px-6 py-3 rounded-full text-lg hover:bg-white hover:text-green-500 transition duration-300">Daftar</a>
        </div>
        <!-- Bagian Kanan: Form Login -->
        <div class="w-1/2 bg-white p-12">
            <h2 class="text-3xl font-bold mb-4">Masuk ke Sistem</h2>
            <?php if (isset($error)): ?>
                <p class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if (isset($_GET['message'])): ?>
                <p class="text-green-500 mb-4 text-center"><?php echo htmlspecialchars($_GET['message']); ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-6">
                    <select name="role" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                        <option value="">Pilih Peran</option>
                        <option value="admin">Admin</option>
                        <option value="librarian">Pustakawan</option>
                        <option value="student">Mahasiswa</option>
                    </select>
                </div>
                <div class="mb-6">
                    <input type="text" name="username" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" placeholder="Username" required>
                </div>
                <div class="mb-6">
                    <input type="password" name="password" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" placeholder="Password" required>
                </div>
                <button type="submit" class="w-full bg-green-500 text-white p-3 rounded-full hover:bg-green-600 transition duration-300 text-lg">Masuk</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>