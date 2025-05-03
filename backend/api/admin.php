<?php

require_once '../db/connection.php'; // database connection file
session_start();
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../../frontend/login.html");
    exit();
}
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// Sanitize and retrieve input
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    // Redirect with error
    header("Location: ../../frontend/login.html");
    exit();
}

// Prepare and execute query securely
$stmt = $conn->prepare("
  SELECT u.id, u.email, u.password 
  FROM admin a
  INNER JOIN users u ON a.user_id = u.id
  WHERE u.email = ?
");

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        // Valid login
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../../frontend/admin_dashboard.html");
        exit();
    } else {
        // Invalid password
        header("Location: ../../frontend/login.html");
        exit();
    }
} else {
    // Email not found
    header("Location: ../../frontend/login.html");
    exit();
}
