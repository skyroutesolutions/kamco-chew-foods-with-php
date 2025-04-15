<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['blog_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    
    try {
        // Check if a new image is uploaded
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image'];
            $imageName = time() . "_" . basename($image['name']);
            $targetPath = "uploads/blogs/" . $imageName;

            if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                // Get old image
                $stmt =$pdo->prepare("SELECT image FROM blogs WHERE id = ?");
                $stmt->execute([$id]);
                $oldImage = $stmt->fetchColumn();

                // Delete old image
                if ($oldImage && file_exists("uploads/blogs/" . $oldImage)) {
                    unlink("uploads/blogs/" . $oldImage);
                }

                // Update with new image
                $stmt =$pdo->prepare("UPDATE blogs SET name = ?, image = ?, description = ? WHERE id = ?");
                $stmt->execute([$name, $imageName, $description, $id]);
            } else {
                echo "Error uploading image.";
                exit;
            }
        } else {
            // Update without changing the image
            $stmt =$pdo->prepare("UPDATE blogs SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $description, $id]);
        }

        echo "Blog updated successfully!";
    } catch (PDOException $e) {
        echo "Error updating blog: " . $e->getMessage();
    }
}
?>
