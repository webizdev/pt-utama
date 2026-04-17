<?php
require_once 'includes/db.php';

// Fetch Website Content
try {
    $stmt = $pdo->prepare("SELECT config_value FROM settings WHERE config_key = ?");
    $stmt->execute(['web_data']);
    $row = $stmt->fetch();
    $webData = json_decode($row['config_value'], true);
} catch (Exception $e) {
    // Fallback if DB fails
    $webData = [
        "brand" => "UTAMA",
        "heroTitle" => "MATERIAL JALAN & BETON RIAU",
        "heroDesc" => "Penyedia material andesit berkualitas...",
        "heroImg" => "img/utama1.webp",
        "wa" => "6285272343918",
        "iup" => "...",
        "products" => [],
        "gallery" => []
    ];
}

// SEO Variables
$pageTitle = "PT " . $webData['brand'] . " | Material Jalan & Beton - Suplier Andesit Riau";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="google-site-verification" content="R400STv-oZo7-XP6E9nQXh6P3JFlTBMla0im7XMxkVI" />
    
    <?php
        $seoDesc = $webData['seoDesc'] ?? "PT UTAMA - Supplier Andesit & Material Jalan terbaik di Riau. Melayani kebutuhan konstruksi jalan dan beton di Indragiri Hulu, Rengat, Inhu, Tembilahan, dan Pekanbaru. Hubungi: " . $webData['wa'];
        $seoKey = $webData['seoKey'] ?? "andesit riau, supplier material jalan inhu, jual batu split rengat, quarry andesit batang gansal, material agregat riau, pt utama andesit";
        $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    ?>
    <meta name="description" content="<?php echo htmlspecialchars($seoDesc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($seoKey); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($currentUrl); ?>">

    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($currentUrl); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seoDesc); ?>">
    <meta property="og:image" content="img/og-image.webp">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "PT UTAMA",
      "image": "img/og-image.webp",
      "@id": "<?php echo $currentUrl; ?>",
      "url": "<?php echo $currentUrl; ?>",
      "telephone": "<?php echo $webData['wa']; ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Desa Usul, Kec. Batang Gansal",
        "addressLocality": "Indragiri Hulu",
        "addressRegion": "Riau",
        "postalCode": "29371",
        "addressCountry": "ID"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": -0.6922258,
        "longitude": 102.4342417
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
        ],
        "opens": "08:00",
        "closes": "17:00"
      }
    }
    </script>
    
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- LCP Preload -->
    <link rel="preload" as="image" href="<?php echo htmlspecialchars($webData['heroImg']); ?>" fetchpriority="high">
    
    <!-- Performance Optimization: Static CSS & Essential Styles -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Deferred Secondary Resources -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    </noscript>

    <style>
        /* CRITICAL CSS: Inline to prevent blank screen and layout shift */
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background-color: #0f0f0f; color: #e5e5e5; margin: 0; opacity: 1; }
        h1, h2, h3, h4 { font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em; }
        
        /* Layout Fixes for Mobile Speed */
        #website-view { display: block !important; }
        
        /* Utilities */
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .tab-btn { color: #525252; transition: all 0.3s; }
        .tab-btn.active { border-bottom: 4px solid #f59e0b; color: #ffffff; font-weight: 800; }
        .card-produk { background: #1a1a1a; border: 1px solid #333; transition: all 0.3s ease; position: relative; }
        .card-produk:hover { border-color: #f59e0b; transform: translateY(-4px); box-shadow: 0 10px 30px -10px rgba(245, 158, 11, 0.2); }
        .img-container { width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background: #262626; border-bottom: 1px solid #333; }
        .img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; filter: grayscale(20%); }
        .card-produk:hover img { transform: scale(1.1); filter: grayscale(0%); }
        .map-container { position: relative; width: 100%; height: 100%; min-height: 200px; border-radius: 4px; overflow: hidden; filter: grayscale(1) invert(1) contrast(1.2); border: 1px solid #333; }
    </style>
</head>
<body>

    <div id="website-view">
        <!-- Header -->
        <header class="sticky top-0 z-[100] bg-[#0f0f0f]/90 backdrop-blur-md border-b border-white/10">
            <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
                <span class="text-2xl font-black uppercase italic tracking-tighter text-white">PT <span class="text-amber-500"><?php echo htmlspecialchars($webData['brand']); ?></span></span>
                <nav class="hidden md:flex gap-10 text-[11px] font-black uppercase tracking-widest text-gray-400">
                    <a href="#home" class="hover:text-white transition">Home</a>
                    <a href="#aplikasi" class="hover:text-white transition">Manfaat</a>
                    <a href="#katalog-section" class="hover:text-white transition">Katalog</a>
                    <a href="#katalog-section" onclick="showGaleri()" class="hover:text-white transition">Galeri</a>
                    <a href="https://wa.me/<?php echo $webData['wa']; ?>" class="text-amber-500 border-b border-amber-500/50 hover:text-amber-400 hover:border-amber-400 transition">Order</a>
                </nav>
            </div>
        </header>

        <!-- Hero -->
        <section id="home" class="relative py-20 px-6 bg-gradient-to-b from-[#1a1a1a] to-[#0f0f0f] border-b border-white/5">
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 40px 40px;"></div>
            
            <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center relative z-10">
                <div class="text-left">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-12 h-[2px] bg-amber-500"></span>
                        <span class="text-amber-500 text-[10px] font-black uppercase tracking-[0.3em]">Direct Quarry Riau</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black uppercase leading-[0.9] mb-6 italic text-white leading-tight"><?php echo htmlspecialchars($webData['heroTitle']); ?></h1>
                    <p class="text-lg md:text-xl text-gray-400 mb-10 font-medium leading-relaxed max-w-lg"><?php echo htmlspecialchars($webData['heroDesc']); ?></p>
                    
                    <a href="#katalog-section" class="group relative inline-flex items-center justify-center px-8 py-4 font-black text-xs uppercase tracking-[0.2em] text-black bg-amber-500 overflow-hidden transition-all hover:bg-white hover:text-black">
                        <span class="relative z-10">Lihat Katalog</span>
                        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-0 bg-white transition-transform duration-300 ease-in-out"></div>
                    </a>
                </div>
                <div class="rounded-sm overflow-hidden shadow-2xl bg-[#262626] aspect-video border border-white/10 relative group">
                    <div class="absolute inset-0 border-2 border-amber-500/20 z-20 pointer-events-none"></div>
                    <img src="<?php echo htmlspecialchars($webData['heroImg']); ?>" width="800" height="450" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition duration-500">
                </div>
            </div>
        </section>

        <!-- Manfaat Material -->
        <section id="aplikasi" class="py-24 bg-[#0f0f0f]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="flex flex-col items-start p-6 border border-white/5 hover:border-amber-500/50 bg-[#161616] hover:bg-[#1a1a1a] transition duration-300 group">
                        <div class="w-12 h-12 bg-amber-500/10 flex items-center justify-center mb-6 group-hover:bg-amber-500 transition duration-300">
                            <i class="fas fa-road text-amber-500 text-xl group-hover:text-black"></i>
                        </div>
                        <h4 class="text-xl font-black uppercase mb-3 text-white">Campuran Aspal</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Andesit berkualitas untuk campuran aspal hotmix infrastruktur jalan raya nasional yang kokoh.</p>
                    </div>
                    <div class="flex flex-col items-start p-6 border border-white/5 hover:border-amber-500/50 bg-[#161616] hover:bg-[#1a1a1a] transition duration-300 group">
                        <div class="w-12 h-12 bg-amber-500/10 flex items-center justify-center mb-6 group-hover:bg-amber-500 transition duration-300">
                            <i class="fas fa-city text-amber-500 text-xl group-hover:text-black"></i>
                        </div>
                        <h4 class="text-xl font-black uppercase mb-3 text-white">Campuran Beton</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Material agregat kasar terbaik untuk campuran semen gedung bertingkat dan beton sipil.</p>
                    </div>
                    <div class="flex flex-col items-start p-6 border border-white/5 hover:border-amber-500/50 bg-[#161616] hover:bg-[#1a1a1a] transition duration-300 group">
                        <div class="w-12 h-12 bg-amber-500/10 flex items-center justify-center mb-6 group-hover:bg-amber-500 transition duration-300">
                            <i class="fas fa-truck-pickup text-amber-500 text-xl group-hover:text-black"></i>
                        </div>
                        <h4 class="text-xl font-black uppercase mb-3 text-white">Timbunan Jalan</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Material raw kotor untuk solusi ekonomis pengerasan akses jalan perkebunan sawit.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Katalog & Galeri -->
        <section id="katalog-section" class="py-24 bg-[#141414] border-y border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-center gap-12 mb-20 border-b border-white/10">
                    <button onclick="openTab(event, 'produk-katalog')" id="tab-produk" class="tab-btn active pb-6 font-black uppercase text-2xl md:text-3xl tracking-tighter italic">Katalog Produk</button>
                    <button onclick="openTab(event, 'galeri-kegiatan')" id="tab-galeri" class="tab-btn pb-6 font-black uppercase text-2xl md:text-3xl tracking-tighter italic">Galeri Dokumentasi</button>
                </div>

                <!-- Tab 1: Katalog Produk -->
                <div id="produk-katalog" class="tab-content active">
                    <div id="view-product-list" class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                        <?php foreach (($webData['products'] ?? []) as $p): ?>
                        <div class="card-produk group cursor-pointer" onclick="window.location.href='https://wa.me/<?php echo $webData['wa']; ?>?text=Halo%20PT%20UTAMA,%20saya%20tertarik%20dengan%20<?php echo urlencode($p['title']); ?>'">
                            <div class="img-container">
                                <img src="<?php echo htmlspecialchars($p['img']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>" loading="lazy">
                            </div>
                            <div class="p-4 text-center">
                                <h4 class="text-[11px] font-black uppercase tracking-widest text-gray-200 group-hover:text-amber-500 transition"><?php echo htmlspecialchars($p['title']); ?></h4>
                                <p class="text-[9px] font-bold text-gray-500 mt-2 italic uppercase group-hover:text-white transition">Cek Harga & Stok</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tab 2: Dokumentasi -->
                <div id="galeri-kegiatan" class="tab-content">
                    <div id="view-gallery-list" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php foreach (($webData['gallery'] ?? []) as $g): ?>
                        <img src="<?php echo htmlspecialchars($g); ?>" class="w-full h-40 object-cover opacity-70 hover:opacity-100 transition duration-300 border border-transparent hover:border-amber-500">
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-12 p-8 bg-[#1a1a1a] border border-amber-500/20 flex flex-col md:row justify-between items-center gap-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-2 h-full bg-repeating-linear-gradient(45deg, #f59e0b, #f59e0b 10px, #000 10px, #000 20px)"></div>
                        <div class="pl-6 text-center md:text-left z-10">
                            <h4 class="text-2xl font-black uppercase italic mb-2 text-white">Keamanan Kerja (K3) Prioritas Kami</h4>
                            <p class="text-gray-400 text-sm">Proses tambang profesional dengan alat berat modern.</p>
                        </div>
                        <div class="px-6 py-3 border border-amber-500 text-amber-500 font-black italic tracking-widest bg-amber-500/5 hover:bg-amber-500 hover:text-black transition cursor-default">ZERO ACCIDENT</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-[#0a0a0a] text-white pt-24 pb-12 border-t border-white/10">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-20">
                    <div class="md:col-span-4">
                        <span class="text-3xl font-black italic tracking-tighter uppercase mb-6 block text-white">PT <span class="text-amber-500"><?php echo htmlspecialchars($webData['brand']); ?></span></span>
                        <p class="text-gray-500 text-sm leading-relaxed mb-8 italic">Produsen andesit tangan pertama di Batang Gansal, Riau. Melayani kebutuhan konstruksi jalan dan beton di seluruh Sumatera & Jawa.</p>
                        <div class="p-5 border border-white/10 bg-[#141414]">
                            <p class="text-[10px] text-amber-500 font-black uppercase mb-1 tracking-widest italic">Legalitas Tambang Resmi:</p>
                            <p class="text-sm font-bold text-gray-300 tracking-widest uppercase italic"><?php echo htmlspecialchars($webData['iup']); ?></p>
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <h4 class="text-white font-black text-xs uppercase tracking-widest mb-10 border-b border-white/10 pb-4">Kontak Kami</h4>
                        <p class="text-sm text-gray-400 mb-6 italic">Desa Usul, Kec. Batang Gansal,<br>Indragiri Hulu, Riau.</p>
                        <p class="text-2xl font-black text-white tracking-tighter mb-2"><?php echo htmlspecialchars($webData['wa']); ?></p>
                        <a href="https://maps.app.goo.gl/uTjEsaoUf7m415bs9" target="_blank" class="text-xs font-bold text-amber-500 uppercase tracking-widest flex items-center gap-2 mt-4 hover:text-white transition">
                            <i class="fas fa-map-marker-alt"></i> Petunjuk Navigasi
                        </a>
                    </div>
                    <div class="md:col-span-5 h-64">
                        <h4 class="text-white font-black text-xs uppercase tracking-widest mb-10 border-b border-white/10 pb-4">Lokasi Operasional</h4>
                        <div class="map-container">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31916.16123456!2d102.4342417!3d-0.6922258!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e2eb99a26ed0b39%3A0x38f07006a9af82f2!2sUsul%2C%20Kec.%20Batang%20Gangsal%2C%20Kabupaten%20Indragiri%20Hulu%2C%20Riau!5e0!3m2!1sid!2sid!4v1700000000000" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
                <div class="border-t border-white/5 pt-12 flex flex-col md:row justify-between items-center text-[10px] font-black uppercase tracking-[0.5em] text-gray-600">
                    <p class="cursor-pointer hover:text-amber-500 transition">
                        <a href="admin/" class="hover:text-amber-500 transition">© 2026 PT USUL TAMBANG MANDIRI. ALL RIGHTS RESERVED.</a>
                    </p>
                    <div class="flex gap-8 mt-6 md:mt-0 italic">
                        <a href="#"><i class="fab fa-facebook-f text-base hover:text-amber-500 transition"></i></a>
                        <a href="#"><i class="fab fa-instagram text-base hover:text-amber-500 transition"></i></a>
                        <a href="#"><i class="fab fa-tiktok text-base hover:text-amber-500 transition"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function openTab(evt, tabName) {
            document.querySelectorAll(".tab-content").forEach(t => t.classList.remove("active"));
            document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));
            document.getElementById(tabName).classList.add("active");
            if(evt) evt.currentTarget.classList.add("active");
        }
        function showGaleri() { 
            openTab({currentTarget: document.getElementById('tab-galeri')}, 'galeri-kegiatan'); 
            document.getElementById('katalog-section').scrollIntoView({behavior:'smooth'}); 
        }
    </script>
</body>
</html>
