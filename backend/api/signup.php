<?php
session_start();
include('../db/connection.php'); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    
    if ($password !== $confirm_password) {
        header('location: ../../frontend/signup.html?confirmPassword=f');
        exit;
    }

    $check_query = "SELECT id FROM users WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header('location: ../../frontend/signup.html?email=registered');
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
