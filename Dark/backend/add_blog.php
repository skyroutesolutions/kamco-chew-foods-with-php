<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    $name = $_POST['name'] ?? null;

    $description = $_POST['description'] ?? null;

    if (!$name || !$description) {

        echo "All fields are required.";
        exit;
    }

    try {
        // Insert into `blogs` table first
        $stmt =$pdo->prepare("INSERT INTO blogs (name, description) VALUES (?, ?)");

        $stmt->execute([$name,  $description]);

        // Get the last inserted blog ID
        $blog_id =$pdo->lastInsertId();

        // Handle multiple images
        if (!empty($_FILES['images']['name'][0])) {
            $targetDir = "uploads/blogs/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            foreach ($_FILES['images']['name'] as $key => $imageName) {
                $imageTmpName = $_FILES['images']['tmp_name'][$key];
                $uniqueImageName = time() . "_" . basename($imageName);
                $targetPath = $targetDir . $uniqueImageName;

                if (move_uploaded_file($imageTmpName, $targetPath)) {
                    $stmt =$pdo->prepare("INSERT INTO blog_image (blog_id, image_path) VALUES (?, ?)");
                    $stmt->execute([$blog_id, $uniqueImageName]);
                }
            }
        }

        echo "Blog and images uploaded successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
