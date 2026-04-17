<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Fetch Current Data
$stmt = $pdo->prepare("SELECT config_value FROM settings WHERE config_key = ?");
$stmt->execute(['web_data']);
$row = $stmt->fetch();
$webData = json_decode($row['config_value'], true);

// Pre-fill defaults if empty
if (!$webData) {
    $webData = [
        "brand" => "", "heroTitle" => "", "heroDesc" => "", "heroImg" => "",
        "wa" => "", "iup" => "", "products" => [], "gallery" => []
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | PT UTAMA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f0f0f; color: #e5e5e5; }
        h2, h3 { font-family: 'Space Grotesk', sans-serif; }
        .input-box { width: 100%; background: #1a1a1a; border: 1px solid #333; padding: 12px; border-radius: 4px; margin-bottom: 12px; font-size: 14px; color: #fff; }
        .input-box:focus { border-color: #f59e0b; outline: none; }
        label { font-size: 11px; font-weight: 800; color: #a3a3a3; text-transform: uppercase; margin-bottom: 4px; display: block; tracking-widest: 0.1em; }
    </style>
</head>
<body class="p-6 md:p-12">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 border-b border-white/10 pb-5 gap-4">
            <h2 class="text-2xl font-black uppercase italic text-white text-center md:text-left">
                Master <span class="text-amber-500 underline">Dashboard</span>
            </h2>
            <div class="flex gap-4">
                <a href="change_password.php" class="bg-gray-800 text-gray-300 border border-white/10 px-6 py-2 font-bold text-xs uppercase hover:bg-gray-700 transition">Ganti Password</a>
                <a href="logout.php" class="bg-red-900/50 text-red-200 border border-red-900 px-6 py-2 font-bold text-xs uppercase hover:bg-red-800 transition">Keluar</a>
            </div>
        </div>

        <form action="update.php" method="POST" id="cms-form">
            <!-- Row 1: Basic Info -->
            <div class="grid md:grid-cols-2 gap-10 mb-10">
                <div class="bg-[#1a1a1a] p-8 border border-white/10 shadow-sm">
                    <h3 class="text-xs font-black text-amber-500 mb-6 uppercase italic tracking-[0.2em]">Informasi Utama</h3>
                    <label>Nama Perusahaan (Brand)</label>
                    <input type="text" name="brand" value="<?php echo htmlspecialchars($webData['brand']); ?>" class="input-box">
                    
                    <label>WhatsApp (Contoh: 628...)</label>
                    <input type="text" name="wa" value="<?php echo htmlspecialchars($webData['wa']); ?>" class="input-box">
                    
                    <label>Nomor SK IUP (Legalitas)</label>
                    <input type="text" name="iup" value="<?php echo htmlspecialchars($webData['iup']); ?>" class="input-box">

                    <h3 class="text-xs font-black text-amber-500 mt-8 mb-6 uppercase italic tracking-[0.2em]">Optimasi SEO</h3>
                    <label>Meta Description (Deskripsi di Google)</label>
                    <textarea name="seoDesc" class="input-box" rows="3"><?php echo htmlspecialchars($webData['seoDesc'] ?? ''); ?></textarea>
                    
                    <label>Meta Keywords (Pisahkan dengan koma)</label>
                    <textarea name="seoKey" class="input-box" rows="2"><?php echo htmlspecialchars($webData['seoKey'] ?? ''); ?></textarea>
                </div>
                <div class="bg-[#1a1a1a] p-8 border border-white/10 shadow-sm">
                    <h3 class="text-xs font-black text-amber-500 mb-6 uppercase italic tracking-[0.2em]">Konten Hero</h3>
                    <label>Judul Hero (Headline)</label>
                    <input type="text" name="heroTitle" value="<?php echo htmlspecialchars($webData['heroTitle']); ?>" class="input-box">
                    
                    <label>Deskripsi Pendek</label>
                    <textarea name="heroDesc" class="input-box" rows="2"><?php echo htmlspecialchars($webData['heroDesc']); ?></textarea>
                    
                    <label>URL Gambar Utama (Hero)</label>
                    <input type="text" name="heroImg" value="<?php echo htmlspecialchars($webData['heroImg']); ?>" class="input-box">
                </div>
            </div>

            <!-- Row 2: Products -->
            <div class="bg-[#1a1a1a] p-8 border border-white/10 shadow-sm mb-10">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xs font-black uppercase text-amber-500 italic tracking-[0.2em]">Manajemen Katalog Produk</h3>
                    <button type="button" onclick="addProduct()" class="bg-amber-500 text-black px-5 py-2 text-[10px] font-black uppercase hover:bg-white transition">TAMBAH PRODUK +</button>
                </div>
                <div id="product-container" class="space-y-4">
                    <?php foreach (($webData['products'] ?? []) as $index => $product): ?>
                    <div class="flex flex-col md:flex-row gap-4 items-end bg-[#262626] p-4 border border-white/10 rounded-sm product-item">
                        <div class="flex-1 w-full">
                            <label>Nama Produk</label>
                            <input type="text" name="products[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($product['title']); ?>" class="input-box !mb-0 text-xs">
                        </div>
                        <div class="flex-[2] w-full">
                            <label>URL Gambar Produk</label>
                            <input type="text" name="products[<?php echo $index; ?>][img]" value="<?php echo htmlspecialchars($product['img']); ?>" class="input-box !mb-0 text-xs">
                        </div>
                        <button type="button" onclick="this.closest('.product-item').remove()" class="text-red-500 p-2 hover:bg-red-900/20 transition mb-1"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Row 3: Gallery -->
            <div class="bg-[#1a1a1a] p-8 border border-white/10 shadow-sm mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xs font-black uppercase text-amber-500 italic tracking-[0.2em]">Galeri Dokumentasi</h3>
                    <button type="button" onclick="addGallery()" class="bg-amber-500 text-black px-5 py-2 text-[10px] font-black uppercase hover:bg-white transition">TAMBAH FOTO +</button>
                </div>
                <div id="gallery-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach (($webData['gallery'] ?? []) as $index => $img): ?>
                    <div class="flex gap-2 bg-[#262626] p-3 border border-white/10 rounded-sm items-center gallery-item">
                        <input type="text" name="gallery[]" value="<?php echo htmlspecialchars($img); ?>" class="input-box !mb-0 text-xs">
                        <button type="button" onclick="this.closest('.gallery-item').remove()" class="text-red-500 p-2"><i class="fas fa-times"></i></button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="w-full bg-amber-600 py-6 text-black font-black uppercase tracking-[0.2em] shadow-2xl hover:bg-amber-500 transition text-sm sticky bottom-6 z-50">
                Update Seluruh Website & Data Cloud
            </button>
        </form>
    </div>

    <script>
        function addProduct() {
            const container = document.getElementById('product-container');
            const index = container.children.length;
            const div = document.createElement('div');
            div.className = 'flex flex-col md:flex-row gap-4 items-end bg-[#262626] p-4 border border-white/10 rounded-sm product-item';
            div.innerHTML = `
                <div class="flex-1 w-full">
                    <label>Nama Produk</label>
                    <input type="text" name="products[${index}][title]" value="" class="input-box !mb-0 text-xs" placeholder="Contoh: Andesit 1/2">
                </div>
                <div class="flex-[2] w-full">
                    <label>URL Gambar Produk</label>
                    <input type="text" name="products[${index}][img]" value="" class="input-box !mb-0 text-xs" placeholder="img/nama_file.webp">
                </div>
                <button type="button" onclick="this.closest('.product-item').remove()" class="text-red-500 p-2 hover:bg-red-900/20 transition mb-1"><i class="fas fa-trash-alt"></i></button>
            `;
            container.appendChild(div);
        }

        function addGallery() {
            const container = document.getElementById('gallery-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 bg-[#262626] p-3 border border-white/10 rounded-sm items-center gallery-item';
            div.innerHTML = `
                <input type="text" name="gallery[]" value="" class="input-box !mb-0 text-xs" placeholder="img/nama_file.webp">
                <button type="button" onclick="this.closest('.gallery-item').remove()" class="text-red-500 p-2"><i class="fas fa-times"></i></button>
            `;
            container.appendChild(div);
        }
    </script>
</body>
</html>
