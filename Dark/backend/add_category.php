<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];


    if (!empty($category_name) ) {
        $stmt =$pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute(['name' => $category_name]);

        echo "Category added successfully!";
    } else {
        echo "Please fill in all fields.";
    }
} else {
    echo "Invalid request method.";
}
?>
