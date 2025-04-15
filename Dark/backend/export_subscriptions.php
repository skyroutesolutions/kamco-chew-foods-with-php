<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.html");
    exit;
}

require_once 'db_connection.php';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="subscriptions_'.date('Y-m-d').'.xls"');

$query = "SELECT * FROM subscriptions ORDER BY created_date DESC";
$result = mysqli_query($conn, $query);

echo "ID\tEmail\tCreated Date\n";

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['id']."\t";
    echo $row['email']."\t";
    echo $row['created_date']."\n";
}
?>
