
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../../frontend/login.html");
    exit();
}
require '../db/connection.php'; // adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name) || empty($description)) {
        die("Category name and description are required.");
    }

    // Insert into categories
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
