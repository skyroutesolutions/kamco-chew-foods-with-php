<?php
require 'db_connection.php';

try {
    // Fetch blogs and their images
    $stmt =$pdo->query("
        SELECT b.*, GROUP_CONCAT(bi.image_path) AS images 
        FROM blogs b 
        LEFT JOIN blog_image bi ON b.id = bi.blog_id 
        GROUP BY b.id 
        ORDER BY b.published_date DESC
    ");
    
    $blogs = $stmt->fetchAll();

    foreach ($blogs as $blog) {
        // Convert multiple images to an array
        $images = explode(',', $blog['images']);
        $imageHtml = "";
        
        // Show only 2 images, with a "View More" button if there are more
        foreach ($images as $index => $img) {
            if ($index < 2) { 
                $imageHtml .= "<img src='backend/uploads/blogs/$img' width='60' style='margin:5px; border-radius:5px;'>";
            }
        }
        
        if (count($images) > 2) {
            $imageHtml .= "<br><button class='btn btn-sm btn-info view-images-btn' data-images='{$blog['images']}'>View More</button>";
        }

        // Short description
        $shortDesc = strlen($blog['description']) > 50 ? substr($blog['description'], 0, 50) . '...' : $blog['description'];
        $sno = 1;
        echo "<tr>
            <td>{$sno}</td>
            <td>{$blog['name']}</td>
            <td>{$imageHtml}</td>
           
            <td class='description-short'>{$shortDesc}</td>
            <td>
                <button class='btn btn-warning edit-btn' data-id='{$blog['id']}'>Edit</button>
                <button class='btn btn-danger delete-btn' data-id='{$blog['id']}'>Delete</button>
                <button class='btn btn-info view-btn' data-id='{$blog['id']}'>Show</button>
            </td>
        </tr>";
    }
} catch (PDOException $e) {
    echo "Error fetching blogs: " . $e->getMessage();
}
?>
