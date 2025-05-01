<?php
require '../db/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $questions = $_POST['questions'] ?? [];

    // 1. Insert category
    $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $categoryName, $description);
    if (!$stmt->execute()) {
        echo "Error inserting category: " . $stmt->error;
        exit;
    }
    $categoryId = $stmt->insert_id;
    $stmt->close();

    // 2. If questions are submitted, insert quiz and process them
    if (!empty($questions)) {
        $quizTitle = $categoryName . " Quiz";
        $stmtQuiz = $conn->prepare("INSERT INTO quizzes (title, category_id) VALUES (?, ?)");
        $stmtQuiz->bind_param("si", $quizTitle, $categoryId);
        if (!$stmtQuiz->execute()) {
            echo "Error inserting quiz: " . $stmtQuiz->error;
            exit;
        }
        $quizId = $stmtQuiz->insert_id;
        $stmtQuiz->close();

        // Loop through questions
        foreach ($questions as $q) {
            $questionText = $q['text'];
            $stmtQ = $conn->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
            $stmtQ->bind_param("is", $quizId, $questionText);
            $stmtQ->execute();
            $questionId = $stmtQ->insert_id;
            $stmtQ->close();

            // Loop through options
            foreach ($q['options'] as $index => $optionText) {
                $isCorrect = ($index == $q['correct']) ? 1 : 0;
                $stmtO = $conn->prepare("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                $stmtO->bind_param("isi", $questionId, $optionText, $isCorrect);
                $stmtO->execute();
                $stmtO->close();
            }
        }

        echo "<h2>✅ Category, Quiz & Questions Added!</h2>";
    } else {
        echo "<h2>✅ Only Category Added (no questions submitted)</h2>";
    }

    echo "<a href='../../frontend/admin_dashboard.html'>⬅️ Back to Dashboard</a>";
} else {
    echo "❌ Invalid request method.";
}
?>
