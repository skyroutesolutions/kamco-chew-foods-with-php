<?php
header('Content-Type: application/json');
require '../../dark/backend/db_connection.php';

try {
    // Fetch all blogs
    $query =$pdo->prepare("SELECT * FROM blogs");
    $query->execute();
    $blogs = $query->fetchAll(PDO::FETCH_ASSOC);

    // Fetch images for each blog
    foreach ($blogs as &$blog) {
        $imgQuery =$pdo->prepare("SELECT image FROM blogs WHERE id = :id");
        $imgQuery->bindParam(':id', $blog['id'], PDO::PARAM_INT);
        $imgQuery->execute();
        $images = $imgQuery->fetchAll(PDO::FETCH_COLUMN);
        $blog['images'] = $images; // Add images to each blog
    }

    echo json_encode($blogs);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
