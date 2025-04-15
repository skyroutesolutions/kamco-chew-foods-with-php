<?php
require '../../dark/backend/db_connection.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT * FROM banners");
    $stmt->execute();
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $baseUrl = '../../dark/backend/uploads/banners/'; // Update to your actual server path

    // Process images to include full URLs
    foreach ($banners as &$banner) {
        if (isset($banner['images'])) {
            $images = json_decode($banner['images'], true);
            if (is_array($images)) {
                $banner['images'] = array_map(function($img) use ($baseUrl) {
                    return $baseUrl . $img;
                }, $images);
            }
        }
    }

    echo json_encode(['success' => true, 'banners' => $banners]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
