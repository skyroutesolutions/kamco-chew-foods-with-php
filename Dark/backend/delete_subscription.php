<?php
require 'db_connection.php';

if (!empty($_POST['id'])) {
    $id = (int)$_POST['id'];

    try {
        $stmt =$pdo->prepare("DELETE FROM subscribers WHERE id = ?");
        $stmt->execute([$id]);
        echo "Subscription deleted successfully!";
    } catch (PDOException $e) {
        echo "Failed to delete subscription.";
    }
} else {
    echo "Invalid request.";
}
