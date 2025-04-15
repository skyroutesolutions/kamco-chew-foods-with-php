<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt =$pdo->query("SELECT id, name FROM categories");

    $categories = $stmt->fetchAll();
$sno = 1;
    foreach ($categories as $category) {
        echo "<tr>
                <td>$sno</td>
                <td>{$category['name']}</td>
                <td>
                    <button class='edit-btn' style='color:black' data-id='{$category['id']}'>Edit</button>
                    <button class='delete-btn' style='color:black' data-id='{$category['id']}'>Delete</button>
                </td>
              </tr>";
              $sno++;
    }
} else {
    echo "Invalid request method.";
}
