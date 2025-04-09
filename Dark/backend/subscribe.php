<?php
include 'db_connection.php'; // Ensure database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    try {
        // Insert email into the subscribers table
        $stmt =$pdo->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->execute([$email]);

        echo "Thank you for subscribing!";
    } catch (PDOException $e) {
        // Log the error
        file_put_contents(__DIR__ . "/logs/db_errors.log", "[" . date("Y-m-d H:i:s") . "] Subscription Error: " . $e->getMessage() . "\n", FILE_APPEND);
        echo "Error: " . $e->getMessage();
    }
}

?>
