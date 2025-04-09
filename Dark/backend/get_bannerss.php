<?php
require_once 'db_connection.php';
header('Content-Type: text/html');

try {
    $stmt = $pdo->query("SELECT * FROM banners ORDER BY id DESC");
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
$sno=1;
    foreach ($banners as $banner) {
        $images = json_decode($banner['images'], true);
        $firstImage = !empty($images) ? $images[0] : 'no-image.jpg';
        
        echo "<tr>
            <td>{$sno}</td>
            <td>
                <img src='backend/uploads/banners/{$firstImage}' width='100' class='img-thumbnail'>
            </td>
            <td>
            
                <button class='btn btn-primary btn-sm edit-btn' data-id='{$banner['id']}'>
                    <i class='fas fa-edit'></i>
                </button>
                <button class='btn btn-danger btn-sm delete-btn' data-id='{$banner['id']}'>
                    <i class='fas fa-trash'></i>
                </button>
            </td>
        </tr>";
            $sno++;
    }
} catch (Exception $e) {
    echo "<tr><td colspan='3' class='text-danger'>Error loading banners: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>
