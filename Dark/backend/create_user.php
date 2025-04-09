<?php
include 'db_config.php';

$email = 'admin@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);

try {
    $stmt =$pdo->prepare("INSERT INTO admin_users (email, password) VALUES (?, ?)");
    $stmt->execute([$email, $password]);
    echo "Admin user created successfully!";
} catch (PDOException $e) {
    echo "Failed to create admin user: " . $e->getMessage();
}
