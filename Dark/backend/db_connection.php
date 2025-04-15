<?php

$host = 'localhost';
$dbname = 'pmzc27wuvsg8_kamco_db'; // Full DB name from cPanel
$username = 'pmzc27wuvsg8_kam';    // Full DB user from cPanel
$password = 'eOy4#Rh=)G=U';        // Your MySQL password

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
