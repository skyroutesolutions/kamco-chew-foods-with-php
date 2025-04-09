<?php
header('Content-Type: application/json');
require '../../dark/backend/db_connection.php';

try {
      $stmt = $pdo->prepare("SELECT * FROM categories WHERE status = 'active'");
      $stmt->execute();
      $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
      echo json_encode($categories);
} catch (PDOException $e) {
      echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
