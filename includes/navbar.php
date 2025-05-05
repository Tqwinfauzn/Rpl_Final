<nav class="bg-green-600 p-4 text-white">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Sistem Perpustakaan Digital</h1>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="mr-4 text-lg">Selamat datang, <?php echo htmlspecialchars($_SESSION['role']); ?>!</span>
                <a href="/digital-library/logout.php" class="px-4 py-2 bg-red-500 rounded hover:bg-red-600 text-lg">Keluar</a>
            <?php else: ?>
                <a href="/digital-library/login.php" class="px-4 py-2 bg-green-500 rounded hover:bg-green-600 mr-2 text-lg">Masuk</a>
                <a href="/digital-library/register.php" class="px-4 py-2 bg-green-400 rounded hover:bg-green-500 text-lg">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>