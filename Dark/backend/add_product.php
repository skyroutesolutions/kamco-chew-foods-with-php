<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $image_path = '';

    // Check if an image file is uploaded
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['product_image']['tmp_name'];
        $file_name = basename($_FILES['product_image']['name']);
        $upload_dir = 'uploads/products/'; // Ensure this directory exists and is writable
        $image_path = $upload_dir . $file_name;

        // Validate the image file type (optional)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['product_image']['type'], $allowed_types)) {
            echo "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
            exit;
        }

        // Move the uploaded file
        if (!move_uploaded_file($file_tmp, $image_path)) {
            echo "Failed to move uploaded file.";
            exit;
        }
    }


    $description = $_POST['product_description'];

    // Debugging: Log incoming data
    error_log("Product Name: $product_name");
    
    if (!empty($product_name)  && !empty($description)) {


        $stmt =$pdo->prepare("INSERT INTO products (name,  description, category_id,  image) VALUES (:name,  :description, :category_id,  :image_path)");



        $stmt->execute(['name' => $product_name,  'description' => $_POST['product_description'], 'category_id' => $category_id,  'image_path' => $image_path]);




        echo "Product added successfully!";
    } else {
        echo "Please fill in all fields.";
    }
} else {
    echo "Invalid request method.";
}
?>
