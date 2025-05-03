<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../../frontend/login.html");
    exit();
}
// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Optional: prevent caching of the page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

header("Location: ../../frontend/login.html");
exit();
?>





