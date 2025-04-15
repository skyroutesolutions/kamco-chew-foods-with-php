<?php
header('Content-Type: application/json');
require '../../dark/backend/db_connection.php';

try {
    // Fetch latest 2 blogs
    $query =$pdo->prepare("SELECT id, name, description FROM blogs ORDER BY created_at DESC LIMIT 2");
    $query->execute();
    $blogs = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($blogs) {
        foreach ($blogs as &$blog) {
            // Fetch images for each blog
            $imgQuery =$pdo->prepare("SELECT image FROM blogs WHERE id = :id LIMIT 1");
            $imgQuery->bindParam(':id', $blog['id'], PDO::PARAM_INT);
            $imgQuery->execute();
            $image = $imgQuery->fetchColumn();

            // Add image path or default if not available
            $blog['image'] = $image ? $image : 'default.jpg';

            // Clean and shorten description
            $cleanDescription = strip_tags($blog['name']);
            $blog['name'] = mb_substr($cleanDescription, 0, 80) . '...';
        }

        echo json_encode($blogs);
    } else {
        echo json_encode([]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
