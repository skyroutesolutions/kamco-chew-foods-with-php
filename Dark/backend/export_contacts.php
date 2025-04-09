<?php
require_once 'db_connection.php';

// Set headers to download file as Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=contact_list.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Enquiry</th>
        <th>Country</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Created Date</th>
      </tr>";

try {
    $stmt = $pdo->query("SELECT * FROM contacts");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['enquiry']) . "</td>
                <td>" . htmlspecialchars($row['country']) . "</td>
                <td>" . htmlspecialchars($row['subject']) . "</td>
                <td>" . htmlspecialchars($row['message']) . "</td>
                <td>" . $row['created_at'] . "</td>
              </tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='8'>Error: " . $e->getMessage() . "</td></tr>";
}

echo "</table>";
?>
