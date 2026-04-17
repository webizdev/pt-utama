<?php
header("Content-type: text/xml");
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Home Page
echo '<url>';
echo '<loc>' . $baseUrl . '/</loc>';
echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
echo '<changefreq>weekly</changefreq>';
echo '<priority>1.0</priority>';
echo '</url>';

echo '</urlset>';
?>
