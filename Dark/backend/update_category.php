<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];


    if (!empty($category_id) && !empty($category_name) ) {
        $stmt =$pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
        $stmt->execute(['name' => $category_name, 'id' => $category_id]);

        echo "Category updated successfully!";
    } else {
        echo "Please fill in all fields.";
    }
} else {
    echo "Invalid request method.";
}
?>
