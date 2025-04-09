<?php
require '../../dark/backend/db_connection.php';

header('Content-Type: application/json');

try {
    $stmt =$pdo->prepare("SELECT * FROM contacts ORDER BY id DESC");
    $stmt->execute();
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($contacts);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch contacts: ' . $e->getMessage()]);
}
