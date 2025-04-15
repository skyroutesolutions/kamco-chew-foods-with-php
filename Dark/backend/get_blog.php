<?php
header('Content-Type: application/json');
require 'db_connection.php'; // Ensure database connection

$response = ["error" => "Unknown error occurred"];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    try {
        // Fetch blog details
        $query =$pdo->prepare("SELECT * FROM blogs WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $blog = $query->fetch(PDO::FETCH_ASSOC);

        if ($blog) {
        // Add image to the blog response
        $blog['image'] = $blog['image'];
            $response = $blog;
        } else {
            $response = ["error" => "Blog not found"];
        }
    } catch (PDOException $e) {
        $response = ["error" => "Database error: " . $e->getMessage()];
    }
} else {
    $response = ["error" => "Invalid blog ID"];
}

echo json_encode($response);
?>
