<?php
session_start();
include('db_connection.php'); // Include the database connection file

// Log file for login errors
$log_file = __DIR__ . "/logs/login_errors.log";

// Ensure the logs directory exists
if (!file_exists(__DIR__ . "/logs")) {
      mkdir(__DIR__ . "/logs", 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = trim($_POST['email']);
      $password = trim($_POST['password']);

      if (empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
            exit;
      }

      try {
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                  $_SESSION['admin_id'] = $user['id'];
                  $_SESSION['admin_email'] = $user['email'];
                  echo json_encode(['status' => 'success', 'message' => 'Login successful.']);
            } else {
                  echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
            }
      } catch (PDOException $e) {
            // Log any errors
            file_put_contents($log_file, "[" . date("Y-m-d H:i:s") . "] Login Error: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode(['status' => 'error', 'message' => 'Server error. Please try again later.']);
      }
} else {
      echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
