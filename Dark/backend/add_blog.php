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

        // Handle single image upload
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/blogs/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $imageName = $_FILES['image']['name'];
            $imageTmpName = $_FILES['image']['tmp_name'];
            $uniqueImageName = time() . "_" . basename($imageName);
            $targetPath = $targetDir . $uniqueImageName;

            if (move_uploaded_file($imageTmpName, $targetPath)) {
                $stmt = $pdo->prepare("UPDATE blogs SET image = ? WHERE id = ?");
                $stmt->execute([$uniqueImageName, $blog_id]);
            }
        }

        echo "Blog and images uploaded successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
