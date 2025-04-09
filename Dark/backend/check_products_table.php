<?php
include 'db_connection.php';

try {
    $stmt =$pdo->query("DESCRIBE products");
    $columns = $stmt->fetchAll();

    header('Content-Type: application/json');
    echo json_encode($columns);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
