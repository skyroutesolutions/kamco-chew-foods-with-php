<?php
include('db_connection.php'); // Include the database connection file

try {
    // Fetch all contacts
    $stmt = $pdo->query("SELECT * FROM contacts");
    $contacts = $stmt->fetchAll();
$sno = 1;
    if ($contacts) {
        foreach ($contacts as $contact) {
            echo "<tr>
                    <td>" . ($sno++) . "</td>
                    <td>" . htmlspecialchars($contact['name']) . "</td>
                    <td>" . htmlspecialchars($contact['email']) . "</td>
                    <td>" . htmlspecialchars($contact['enquiry']) . "</td>
                    <td>" . htmlspecialchars($contact['country']) . "</td>
                    <td>" . htmlspecialchars($contact['subject']) . "</td>
                    <td>" . nl2br(htmlspecialchars($contact['message'])) . "</td>
                    <td>" . htmlspecialchars($contact['created_at']) . "</td>
                    <td>
                        <button class='btn btn-warning edit-btn' data-id='" . $contact['id'] . "'>Edit</button>
                        <button class='btn btn-danger delete-btn' data-id='" . $contact['id'] . "'>Delete</button>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No contacts found</td></tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='9'>Error fetching contacts</td></tr>";
}
?>
