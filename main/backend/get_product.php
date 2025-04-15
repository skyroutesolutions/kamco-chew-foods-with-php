<?php
require '../../dark/backend/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $product_id = $_GET['id'];

    if (!empty($product_id)) {
        $stmt =$pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $product_id]);  // Forgot to execute the statement!
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Remove HTML tags from description
            $clean_description = strip_tags($product['description']);

            // Replace original description with cleaned version
            $product['description'] = $clean_description;

            // Prepend 'backend/' to image URL if image is not empty
            if (!empty($product['image'])) {
                $product['image'] = '../../dark/backend/' . $product['image'];
            }

            echo json_encode($product);
        } else {
            echo json_encode(['error' => 'Product not found.']);
        }
    } else {
        echo json_encode(['error' => 'Invalid product ID.']);
    }
} else {
    echo "Invalid request method.";
}


