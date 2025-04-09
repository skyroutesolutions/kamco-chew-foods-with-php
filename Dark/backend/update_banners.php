<?php
require_once 'db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

try {
    $bannerId = $_POST['banners_id'] ?? null;
    if (!$bannerId) {
        echo json_encode(['error' => 'Banner ID is required']);
        exit;
    }

    // Get existing images
    $stmt = $pdo->prepare("SELECT images FROM banners WHERE id = ?");
    $stmt->execute([$bannerId]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);
    $existingImages = $banner ? json_decode($banner['images'], true) : [];

    // Handle new image uploads
    $uploadDir = __DIR__ . '/uploads/banners/';
    $uploadedFiles = $_POST['existing_images'] ?? [];

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $filePath = $uploadDir . uniqid() . '_' . $fileName;
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($tmpName);
            $fileSize = $_FILES['images']['size'][$key];
            
            if (in_array($fileType, $allowedTypes) && $fileSize <= 2097152) {
                if (move_uploaded_file($tmpName, $filePath)) {
                    $uploadedFiles[] = basename($filePath);
                }
            }
        }
    }

    // Update database
    $stmt = $pdo->prepare("UPDATE banners SET images = ? WHERE id = ?");
    $imagesJson = json_encode($uploadedFiles);
    $stmt->execute([$imagesJson, $bannerId]);

    echo json_encode(['success' => 'Banner updated successfully']);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
