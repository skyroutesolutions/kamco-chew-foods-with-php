<?php
header('Content-Type: application/json');
require '../../dark/backend/db_connection.php';

try {
    // Fetch categories along with product count
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, COUNT(p.id) AS count
        FROM categories c
        LEFT JOIN products p ON p.category_id = c.id AND p.status = 'active'
        GROUP BY c.id, c.name
    ");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
