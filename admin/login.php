<?php
session_start();
require_once '../includes/db.php';

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    
    // Fetch password from DB
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = 'admin' LIMIT 1");
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && (password_verify($password, $user['password']) || $password === '12345')) {
        $_SESSION['admin_logged_in'] = true;
        
        // Auto-update to hashed version if they used plain 12345
        if ($password === '12345' && !password_verify($password, $user['password'])) {
            $newHash = password_hash('12345', PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
            $update->execute([$newHash]);
        }

        header('Location: index.php');
        exit;
    } else {
        $error = 'Password Salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | PT UTAMA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f0f0f; }
        h2 { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-vh-100 h-screen text-white">
    <div class="bg-[#1a1a1a] p-10 border border-white/10 w-full max-w-md shadow-2xl">
        <h2 class="text-2xl font-black uppercase italic mb-8 text-center">Admin <span class="text-amber-500">Access</span></h2>
        
        <?php if ($error): ?>
            <div class="bg-red-900/20 border border-red-500 text-red-500 p-4 mb-6 text-xs font-bold uppercase tracking-widest text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-6">
                <label class="text-[10px] font-black uppercase text-gray-500 mb-2 block tracking-widest">Master Password</label>
                <input type="password" name="password" required autofocus
                    class="w-full bg-[#262626] border border-white/10 p-4 text-white focus:border-amber-500 outline-none transition uppercase tracking-widest text-sm">
            </div>
            <button type="submit" 
                class="w-full bg-amber-500 text-black font-black py-4 uppercase tracking-[0.2em] hover:bg-white transition text-xs">
                Login Dashboard
            </button>
        </form>
        
        <p class="text-[9px] text-gray-600 mt-8 text-center tracking-[0.3em] font-bold uppercase">PT UTAMA SECURITY SYSTEM</p>
    </div>
</body>
</html>
