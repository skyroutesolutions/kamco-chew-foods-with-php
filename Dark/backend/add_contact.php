<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../../dark/backend/db_connection.php';

// Set content type to JSON
header('Content-Type: application/json');

// Safely retrieve POST data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$enquiry = isset($_POST['enquiry']) ? trim($_POST['enquiry']) : '';
$country = isset($_POST['country']) ? trim($_POST['country']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Check if all required fields are filled
if (empty($name) || empty($email) || empty($enquiry) || empty($country) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

try {
    // Prepare and execute the SQL statement
    $stmt =$pdo->prepare("INSERT INTO contacts (name, email, enquiry, country, subject, message) 
                            VALUES (:name, :email, :enquiry, :country, :subject, :message)");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':enquiry' => $enquiry,
        ':country' => $country,
        ':subject' => $subject,
        ':message' => $message
    ]);

    // Clear output buffer to ensure valid JSON response
    if (ob_get_length()) ob_clean();

    echo json_encode(['success' => true, 'message' => 'Contact added successfully.']);
} catch (PDOException $e) {
    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => false, 'message' => 'Error adding contact: ' . $e->getMessage()]);
}
?>
