<?php
header('Content-Type: application/json');
require '../../dark/backend/db_connection.php';

try {
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

    if ($category_id > 0) {
        // Fetch products for specific category
        $stmt =$pdo->prepare("SELECT * FROM products WHERE category_id = :category_id AND status = 'active'");
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    } else {
        // Fetch all active products if category_id is not specified
        $stmt =$pdo->prepare("SELECT * FROM products WHERE status = 'active'");
    }
    
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
