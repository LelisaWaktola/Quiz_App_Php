<?php
session_start();
include('../db/connection.php'); // Make sure this path is correct!

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs safely
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "❌ Passwords do not match!";
        exit;
    }

    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "❌ Email is already registered!";
        exit;
    }
    $password=password_hash($password,PASSWORD_DEFAULT);
    // Insert user into database
    $insert_query = "INSERT INTO users (name, email,password) VALUES ( '$username','$email','$password')";
    if (mysqli_query($conn, $insert_query)) {
        // Registration successful
        $_SESSION['user_id'] = mysqli_insert_id($conn); // Save new user id
        $_SESSION['username'] = $username;

        // Redirect to homepage or login
        header('Location: ../../frontend/login.html');
        exit;
    } else {
        echo "❌ Registration failed. Try again.";
    }
} else {
    echo "Invalid Request.";
}
?>
