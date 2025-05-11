<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../frontend/login.html");
    exit();
}

require '../db/connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Check if the category already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM categories WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Category already exists
        header("Location: ../../frontend/admin_dashboard.html?category=duplicate");
        exit();
    }

    // Insert the new category if it doesn't exist
    $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);
    
    if ($stmt->execute()) {
        header("Location: ../../frontend/admin_dashboard.html?category=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

