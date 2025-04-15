<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB Connection
require '../../dark/backend/db_connection.php';

try {
      $stmt = $pdo->prepare("SELECT * FROM categories ");
      $stmt->execute();
      $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
      echo json_encode($categories);
} catch (PDOException $e) {
      echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
