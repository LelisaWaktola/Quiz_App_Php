<?php

require_once '../db/connection.php'; 
session_start();

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: ../../frontend/admin_login.html");
    exit();
}
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
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../../frontend/admin_dashboard.html");
        exit();
    } else {
        header("Location: ../../frontend/admin_login.html?password=f");
        exit();
    }
} else {
    header("Location: ../../frontend/admin_login.html?email=f");
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../frontend/admin_login.html");
    exit();
}

?>
