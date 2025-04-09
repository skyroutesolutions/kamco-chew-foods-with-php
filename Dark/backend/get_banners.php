<?php
require_once 'db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

try {
    $bannerId = $_GET['id'] ?? null;
    if (!$bannerId) {
        echo json_encode(['error' => 'Banner ID is required']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM banners WHERE id = ?");
    $stmt->execute([$bannerId]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$banner) {
        echo json_encode(['error' => 'Banner not found']);
        exit;
    }

    // Decode images JSON and prepare full URLs
    $banner['images'] = json_decode($banner['images'], true);
    if (is_array($banner['images'])) {
        $banner['images'] = array_map(function($img) {
            return 'backend/uploads/banners/' . $img;
        }, $banner['images']);
    }

    echo json_encode($banner);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
