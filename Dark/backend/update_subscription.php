<?php
require 'db_connection.php';

if (!empty($_POST['id']) && !empty($_POST['email'])) {
    $id = (int)$_POST['id'];
    $email = trim($_POST['email']);

    try {
        $stmt =$pdo->prepare("UPDATE subscribers SET email = ? WHERE id = ?");
        $stmt->execute([$email, $id]);
        echo "Subscription updated successfully!";
    } catch (PDOException $e) {
        echo "Failed to update subscription.";
    }
} else {
    echo "Please provide a valid ID and email.";
}
