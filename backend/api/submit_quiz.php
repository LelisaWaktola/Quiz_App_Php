<?php
session_start();
require '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get logged-in user ID from session
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in.");
    }
    $userId = $_SESSION['user_id'];

    $userAnswers = $_POST['answers'] ?? [];
    $score = 0;
    $total = count($userAnswers);
    $quizId = null;

    foreach ($userAnswers as $questionId => $selectedOption) {
        // First fetch quiz ID if not already set
        if (!$quizId) {
            $quizStmt = $conn->prepare("
                SELECT quiz_id FROM questions WHERE id = ?
            ");
            $quizStmt->bind_param("i", $questionId);
            $quizStmt->execute();
            $quizRes = $quizStmt->get_result();
            if ($quizRow = $quizRes->fetch_assoc()) {
                $quizId = $quizRow['quiz_id'];
            }
        }

        // Check if the answer is correct
        $stmt = $conn->prepare("SELECT is_correct FROM options WHERE question_id = ? AND option_text = ?");
        $stmt->bind_param("is", $questionId, $selectedOption);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            if ($row['is_correct']) {
                $score++;
            }
        }
    }

    // Save result to session
    $_SESSION['quiz_result'] = [
        'score' => $score,
        'total' => $total,
        'quiz_id' => $quizId,
        'time' => date('Y-m-d H:i:s')
    ];

    // Save result to the database
    if ($quizId) {
        $insertStmt = $conn->prepare("
            INSERT INTO results (user_id, quiz_id, score) VALUES (?, ?, ?)
        ");
        $insertStmt->bind_param("iii", $userId, $quizId, $score);
        $insertStmt->execute();
    }

    // Redirect to result page
    header("Location: ../../frontend/result.html");
    exit();
} else {
    echo "Invalid request method.";
}

