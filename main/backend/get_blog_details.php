<?php
header('Content-Type: application/json');

require '../../dark/backend/db_connection.php';

try {
    // Get blog name from URL
    $name = isset($_GET['Name']) ? urldecode($_GET['Name']) : '';

    if ($name) {
        // Fetch blog details by name
        $query =$pdo->prepare("SELECT * FROM blogs WHERE name = :name LIMIT 1");
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->execute();
        $blog = $query->fetch(PDO::FETCH_ASSOC);

        if ($blog) {
            // Clean and limit description
            $cleanDescription = strip_tags($blog['description']);  // Remove HTML tags
            $blog['description'] =  $cleanDescription ;// Limit to 200 chars with ellipsis

            // Fetch blog images
            $imgQuery =$pdo->prepare("SELECT image FROM blogs WHERE id = :id");
            $imgQuery->bindParam(':id', $blog['id'], PDO::PARAM_INT);
            $imgQuery->execute();
            $images = $imgQuery->fetchAll(PDO::FETCH_COLUMN);

            $blog['images'] = $images;  // Add images to blog details

            echo json_encode($blog);
        } else {
            echo json_encode(["error" => "Blog not found"]);
        }
    } else {
        echo json_encode(["error" => "Invalid blog name"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
