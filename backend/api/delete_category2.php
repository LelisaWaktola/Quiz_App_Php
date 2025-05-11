<?php
// delete_category.php
require '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $categoryName = strtolower(trim($_POST['category_name']));

    $stmt = $conn->prepare("DELETE FROM categories WHERE LOWER(name) = ?");
    $stmt->bind_param("s", $categoryName);

    if ($stmt->execute()) {
        header("Location: ../../frontend/admin_dashboard.html?success=deleted");
        exit();
    } else {
        echo "Error deleting category: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
