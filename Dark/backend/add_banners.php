<?php
require_once 'db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

try {
    // Create uploads directory if it doesn't exist
    $uploadDir = __DIR__ . '/uploads/banners/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Process file uploads
    $uploadedFiles = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $filePath = $uploadDir . uniqid() . '_' . $fileName;
            
            // Validate file type and size
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($tmpName);
            $fileSize = $_FILES['images']['size'][$key];
            
            if (!in_array($fileType, $allowedTypes) || $fileSize > 2097152) { // 2MB max
                continue;
            }

            if (move_uploaded_file($tmpName, $filePath)) {
                $uploadedFiles[] = basename($filePath);
            }
        }
    }

    if (empty($uploadedFiles)) {
        echo json_encode(['error' => 'No valid images uploaded']);
        exit;
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO banners (images) VALUES (?)");
    $imagesJson = json_encode($uploadedFiles);
    $stmt->execute([$imagesJson]);

    echo json_encode(['success' => 'Banner added successfully']);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
