<?php
require 'db_connection.php';  // Ensure this path is correct

header('Content-Type: text/html; charset=UTF-8');

// Fetch subscribers from the database
try {
    $stmt =$pdo->prepare("SELECT id, email, subscribed_at FROM subscribers ORDER BY subscribed_at DESC");
    $stmt->execute();
    $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
$sno=1;
    if ($subscribers) {
        foreach ($subscribers as $subscriber) {
            echo "<tr>";
            echo "<td>" .($sno++) . "</td>";
            echo "<td>" . htmlspecialchars($subscriber['email']) . "</td>";
            echo "<td>" . htmlspecialchars($subscriber['subscribed_at']) . "</td>";
            echo "<td>
                    <button class='btn btn-sm btn-primary edit-btn' data-id='" . htmlspecialchars($subscriber['id']) . "'>Edit</button>
                    <button class='btn btn-sm btn-danger delete-btn' data-id='" . htmlspecialchars($subscriber['id']) . "'>Delete</button>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4' class='text-center'>No subscribers found.</td></tr>";
    }
} catch (PDOException $e) {
    // Log the error for debugging
    file_put_contents(__DIR__ . "/logs/db_errors.log", "[" . date("Y-m-d H:i:s") . "] Fetch Error: " . $e->getMessage() . "\n", FILE_APPEND);
    echo "<tr><td colspan='4' class='text-center text-danger'>Error fetching subscribers.</td></tr>";
}
