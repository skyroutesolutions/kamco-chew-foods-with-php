<?php

$host = 'localhost';
$dbname = 'srs_backend_kamco'; // Full DB name from cPanel
$username = 'root';    // Full DB user from cPanel
$password = '';        // Your MySQL password

try {
      // Connect to the database
      $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
      echo json_encode([
            'success' => false,
            'message' => 'Database connection/setup failed: ' . $e->getMessage()
      ]);
      exit;
}
?>
