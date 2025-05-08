<?php
session_start();
// Prevent back button access after logout
// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Pragma: no-cache");


require_once '../db/connection.php'; 
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: ../../frontend/login.html");
    exit();
}
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../../frontend/quiz_dashboard.php");
        exit();
    } else {
        header("Location: ../../frontend/login.html?password=f");
        exit();
       
    }
} else {
    header("Location: ../../frontend/login.html?email=f");
    exit();

}
?>