<?php
require 'db_connection.php';

if (!empty($_POST['email'])) {
    $email = trim($_POST['email']);

    try {
        $stmt =$pdo->prepare("INSERT INTO subscribers (email, subscribed_at) VALUES (?, NOW())");
        $stmt->execute([$email]);
        echo "Subscription added successfully!";
    } catch (PDOException $e) {
        echo "Failed to add subscription.";
    }
} else {
    echo "Please provide a valid email.";
}
