<?php
include('db_connection.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $enquiry = $_POST['enquiry'];
    $country = $_POST['country'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    try {
        // Update the contact with all fields
        $stmt =$pdo->prepare("UPDATE contacts 
                                SET name = :name, email = :email, enquiry = :enquiry, country = :country, subject = :subject, message = :message 
                                WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':email' => $email,
            ':enquiry' => $enquiry,
            ':country' => $country,
            ':subject' => $subject,
            ':message' => $message
        ]);

        echo "Contact updated successfully";
    } catch (PDOException $e) {
        echo "Error updating contact: " . $e->getMessage();
    }
}
?>
