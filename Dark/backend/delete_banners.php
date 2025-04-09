<?php
require_once 'db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

try {
    $bannerId = $_POST['id'] ?? null;
    if (!$bannerId) {
        echo json_encode(['error' => 'Banner ID is required']);
        exit;
    }

    // Get images to delete from filesystem
    $stmt = $pdo->prepare("SELECT images FROM banners WHERE id = ?");
    $stmt->execute([$bannerId]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($banner) {
        $images = json_decode($banner['images'], true);
        $uploadDir = __DIR__ . '/uploads/banners/';
        
        // Delete associated image files
        if (is_array($images)) {
            foreach ($images as $image) {
                $filePath = $uploadDir . $image;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
        $stmt->execute([$bannerId]);
    }

    echo json_encode(['success' => 'Banner deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
