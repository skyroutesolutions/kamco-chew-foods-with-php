<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB Connection
require '../../dark/backend/db_connection.php';

// SQL Query
$sql = "SELECT * FROM categories WHERE status = 'active'";
$result = $pdo->query($sql);

// Output only <li> elements
if ($result->rowCount() > 0) {
      echo '<li><a href="main/shop.html">All Products</a></li>';
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $category = strtolower($row['name']);
            echo '<li><a href="main/shop.html?category=' . urlencode($category) . '">' . htmlspecialchars($row['name']) . '</a></li>';
      }
} else {
      echo '<li>No categories found.</li>';
}
