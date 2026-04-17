<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect data
    $newData = [
        "brand" => $_POST['brand'] ?? '',
        "heroTitle" => $_POST['heroTitle'] ?? '',
        "heroDesc" => $_POST['heroDesc'] ?? '',
        "heroImg" => $_POST['heroImg'] ?? '',
        "wa" => $_POST['wa'] ?? '',
        "iup" => $_POST['iup'] ?? '',
        "seoDesc" => $_POST['seoDesc'] ?? '',
        "seoKey" => $_POST['seoKey'] ?? '',
        "products" => [],
        "gallery" => $_POST['gallery'] ?? []
    ];

    // Handle products (mapping title and img)
    if (isset($_POST['products']) && is_array($_POST['products'])) {
        foreach ($_POST['products'] as $p) {
            if (!empty($p['title'])) {
                $newData['products'][] = [
                    "title" => $p['title'],
                    "img" => $p['img'] ?? ''
                ];
            }
        }
    }

    // Convert to JSON
    $jsonContent = json_encode($newData);

    try {
        $stmt = $pdo->prepare("INSERT INTO settings (config_key, config_value) 
                               VALUES ('web_data', ?) 
                               ON DUPLICATE KEY UPDATE config_value = ?");
        $stmt->execute([$jsonContent, $jsonContent]);
        
        // Success: Redirect back with success message (optional)
        header('Location: index.php?status=success');
        exit;
    } catch (Exception $e) {
        die("Gagal mengupdate database: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>
