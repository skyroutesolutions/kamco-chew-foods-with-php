<?php
require 'db_connection.php';

try {
    // Fetch blogs with their single image
    $stmt =$pdo->query("
        SELECT * FROM blogs 
     
    ");
    
    $blogs = $stmt->fetchAll();
    $sno = 1;

    foreach ($blogs as $blog) {
        $imageHtml = "";
        if ($blog['image']) {
            $imageHtml = "<img src='backend/uploads/blogs/{$blog['image']}' width='60' style='margin:5px; border-radius:5px;'>";
        }

        // Short description
        $shortDesc = strlen($blog['description']) > 50 ? substr($blog['description'], 0, 50) . '...' : $blog['description'];
        $sno = 1;
        echo "<tr>
            <td>{$sno}</td>
            <td>{$blog['name']}</td>
            <td>{$imageHtml}</td>
           
            <td class='description-short'>                <button class='btn btn-info view-btn' data-id='{$blog['id']}'>Show</button>
</td>
            <td>
                <button class='btn btn-warning edit-btn' data-id='{$blog['id']}'>Edit</button>
                <button class='btn btn-danger delete-btn' data-id='{$blog['id']}'>Delete</button>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "Error fetching blogs: " . $e->getMessage();
}
?>
