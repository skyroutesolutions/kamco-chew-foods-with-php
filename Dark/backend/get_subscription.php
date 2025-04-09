<?php
require 'db_connection.php';

header('Content-Type: application/json');

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        $stmt =$pdo->prepare("SELECT id, email FROM subscribers WHERE id = ?");
        $stmt->execute([$id]);
        $subscriber = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($subscriber) {
            echo json_encode($subscriber);
        } else {
            echo json_encode(['error' => 'Subscriber not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
