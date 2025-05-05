<?php
session_start();
require_once '../../config/db_connect.php';
include '../../includes/header.php';
include '../../includes/navbar.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}

// Tambah atau edit pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    if (isset($_POST['id'])) {
        // Update pengguna
        $id = $_POST['id'];
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ?, password = ?, role = ? WHERE id = ?");
            $stmt->execute([$name, $username, $password, $role, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ?, role = ? WHERE id = ?");
            $stmt->execute([$name, $username, $role, $id]);
        }
    } else {
        // Tambah pengguna
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $username, $password, $role]);
    }
}

// Hapus pengguna
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

// Ambil daftar pengguna
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<div class="container mx-auto mt-12 flex-grow">
    <div class="flex">
        <div class="w-1/5">
            <?php include '../../includes/sidebar.php'; ?>
        </div>
        <div class="w-4/5 pl-12">
            <h2 class="text-4xl font-bold mb-8 text-gray-800">Kelola Pengguna</h2>
            <!-- Form Tambah/Edit Pengguna -->
            <form method="POST" class="mb-8 bg-white p-6 rounded-lg shadow-md">
                <input type="hidden" name="id" id="user_id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-lg mb-2">Nama</label>
                    <input type="text" name="name" id="name" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-lg mb-2">Username</label>
                    <input type="text" name="username" id="username" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-lg mb-2">Password (kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password" id="password" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-lg mb-2">Peran</label>
                    <select name="role" id="role" class="w-full p-3 border rounded-lg bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg" required>
                        <option value="student">Mahasiswa</option>
                        <option value="librarian">Pustakawan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="bg-green-500 text-white p-3 rounded-full hover:bg-green-600 transition duration-300 text-lg">Simpan Pengguna</button>
            </form>
            <!-- Tabel Daftar Pengguna -->
            <table class="w-full bg-white rounded-lg shadow-md">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="p-4 text-left text-lg">Nama</th>
                        <th class="p-4 text-left text-lg">Username</th>
                        <th class="p-4 text-left text-lg">Peran</th>
                        <th class="p-4 text-left text-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-b">
                            <td class="p-4"><?php echo htmlspecialchars($user['name']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="p-4">
                                <button onclick="editUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['name']); ?>', '<?php echo htmlspecialchars($user['username']); ?>', '<?php echo htmlspecialchars($user['role']); ?>')" class="bg-yellow-500 text-white p-2 rounded-full hover:bg-yellow-600 mr-2 text-lg">Edit</button>
                                <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Yakin ingin menghapus pengguna ini?')" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 text-lg">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function editUser(id, name, username, role) {
        document.getElementById('user_id').value = id;
        document.getElementById('name').value = name;
        document.getElementById('username').value = username;
        document.getElementById('role').value = role;
        document.getElementById('password').value = '';
    }
</script>

<?php include '../../includes/footer.php'; ?>