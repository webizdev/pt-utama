<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (empty($new_pass) || strlen($new_pass) < 5) {
        $error = 'Password minimal 5 karakter!';
    } elseif ($new_pass !== $confirm_pass) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
            $stmt->execute([$hashed_pass]);
            $message = 'Password berhasil diubah!';
        } catch (Exception $e) {
            $error = 'Gagal menyimpan ke database.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password | PT UTAMA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f0f0f; }
        h2 { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-vh-100 h-screen text-white">
    <div class="bg-[#1a1a1a] p-10 border border-white/10 w-full max-w-md shadow-2xl">
        <h2 class="text-2xl font-black uppercase italic mb-8 text-center">Security <span class="text-amber-500">Center</span></h2>
        
        <?php if ($message): ?>
            <div class="bg-green-900/20 border border-green-500 text-green-500 p-4 mb-6 text-xs font-bold uppercase tracking-widest text-center">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-900/20 border border-red-500 text-red-500 p-4 mb-6 text-xs font-bold uppercase tracking-widest text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-6">
                <label class="text-[10px] font-black uppercase text-gray-500 mb-2 block tracking-widest">Password Baru</label>
                <input type="password" name="new_password" required autofocus
                    class="w-full bg-[#262626] border border-white/10 p-4 text-white focus:border-amber-500 outline-none transition tracking-widest text-sm">
            </div>
            <div class="mb-8">
                <label class="text-[10px] font-black uppercase text-gray-500 mb-2 block tracking-widest">Konfirmasi Password</label>
                <input type="password" name="confirm_password" required
                    class="w-full bg-[#262626] border border-white/10 p-4 text-white focus:border-amber-500 outline-none transition tracking-widest text-sm">
            </div>
            <div class="flex flex-col gap-4">
                <button type="submit" 
                    class="w-full bg-amber-500 text-black font-black py-4 uppercase tracking-[0.2em] hover:bg-white transition text-xs">
                    Simpan Perubahan
                </button>
                <a href="index.php" class="text-center text-[10px] font-black uppercase text-gray-400 hover:text-white transition tracking-widest">
                    Kembali Ke Dashboard
                </a>
            </div>
        </form>
    </div>
</body>
</html>
