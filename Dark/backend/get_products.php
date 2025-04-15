<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt =$pdo->query("SELECT p.id, p.name, p.description, p.image, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id");

$sno=1;
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        echo "<tr>
                <td>{$sno}</td>
                <td>{$product['name']}</td>
                <td>{$product['description']}</td>
                <td>{$product['category_name']}</td>

                <td><img src='backend/{$product['image']}' alt='Product Image' style='width: 50px; height: auto;'></td>
                <td>
                    <button class='edit-btn' style='color:black' data-id='{$product['id']}'>Edit</button>
                    <button class='delete-btn' style='color:black' data-id='{$product['id']}'>Delete</button>
                </td>
                $sno++;
              </tr>";
    }
} else {
    echo "Invalid request method.";
}
?>
