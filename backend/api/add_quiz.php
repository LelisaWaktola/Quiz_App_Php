<?php
require_once '../db/connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../../frontend/login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

$categoryName = $_POST['category'] ?? '';
$categoryDesc = $_POST['description'] ?? '';
$questions = $_POST['questions'] ?? [];

if (empty($categoryName) || empty($categoryDesc) || empty($questions)) {
    die("All fields are required.");
}

// 1. Insert or get category ID
$checkCategory = $conn->prepare("SELECT id FROM categories WHERE name = ?");
$checkCategory->bind_param("s", $categoryName);
$checkCategory->execute();
$checkCategory->store_result();

if ($checkCategory->num_rows > 0) {
    $checkCategory->bind_result($categoryId);
    $checkCategory->fetch();
    $checkCategory->close();
} else {
    $checkCategory->close();
    $insertCategory = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $insertCategory->bind_param("ss", $categoryName, $categoryDesc);
    $insertCategory->execute();
    $categoryId = $insertCategory->insert_id;
    $insertCategory->close();
}

// 2. Insert quiz
$createdBy = $_SESSION['user_id'] ?? 1; // fallback if session isn't set
$quizTitle = $categoryName . " Quiz";

$insertQuiz = $conn->prepare("INSERT INTO quizzes (title, category_id) VALUES (?, ?)");
$insertQuiz->bind_param("si", $quizTitle, $categoryId);
$insertQuiz->execute();
$quizId = $insertQuiz->insert_id;
$insertQuiz->close();

// 3. Insert questions and options
foreach ($questions as $index => $q) {
    $questionText = $q['text'] ?? '';
    $options = $q['options'] ?? [];
    $correctIndex = $q['correct'] ?? -1;

    if (empty($questionText) || count($options) !== 4 || $correctIndex < 0 || $correctIndex > 3) {
        continue; // skip invalid
    }

    // Insert question
    $insertQuestion = $conn->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
    $insertQuestion->bind_param("is", $quizId, $questionText);
    $insertQuestion->execute();
    $questionId = $insertQuestion->insert_id;
    $insertQuestion->close();

    // Insert options
    foreach ($options as $optIndex => $optionText) {
        $isCorrect = ($optIndex == $correctIndex) ? 1 : 0;

        $insertOption = $conn->prepare("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
        $insertOption->bind_param("isi", $questionId, $optionText, $isCorrect);
        $insertOption->execute();
        $insertOption->close();
    }
}

$conn->close();
header("Location: ../../frontend/add_quiz.html?addition=success");
exit();
?>
