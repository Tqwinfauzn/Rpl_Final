<?php
session_start();
require_once 'config/db_connect.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, username, password, role) VALUES (?, ?, ?, 'student')");
    if ($stmt->execute([$name, $username, $password])) {
        $success = "Registrasi berhasil! Silakan masuk.";
    } else {
        $error = "Registrasi gagal!";
    }
}
?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-4xl rounded-xl shadow-lg overflow-hidden">
        <!-- Bagian Kiri: Teks Sambutan -->
        <div class="w-1/2 bg-green-500 p-12 text-white flex flex-col justify-center">
            <h1 class="text-4xl font-bold mb-4">Selamat Datang Kembali!</h1>
            <p class="text-lg">Untuk tetap terhubung dengan kami, silakan masuk dengan akun Anda.</p>
            <a href="login.php" class="mt-8 inline-block bg-transparent border-2 border-white text-white px-6 py-3 rounded-full text-lg hover:bg-white hover:text-green-500 transition duration-300">Masuk</a>
        </div>
        <!-- Bagian Kanan: Form Registrasi -->
        <div class="w-1/2 bg-white p-12">
            <h2 class="text-3xl font-bold mb-4">Buat Akun</h2>
            <div class="flex justify-center space-x-4 mb-6">
                <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 hover:bg-gray-100">
                    <span class="text-xl">f</span>
                </a>
                <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 hover:bg-gray-100">
                    <span class="text-xl">G+</span>
                </a>
                <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 hover:bg-gray-100">
                    <span class="text-xl">in</span>
                </a>
            </div>
            <p class="text-center text-gray-500 mb-6">atau gunakan username dan password untuk registrasi</p>
            <?php if (isset($success)): ?>
                <p class="text-green-500 mb-4 text-center"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-4">
                    <input type="text" name="name" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Nama" required>
                </div>
                <div class="mb-4">
                    <input type="text" name="username" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Username" required>
                </div>
                <div class="mb-6">
                    <input type="password" name="password" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Password" required>
                </div>
                <button type="submit" class="w-full bg-green-500 text-white p-3 rounded-full hover:bg-green-600 transition duration-300 text-lg">Daftar</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>