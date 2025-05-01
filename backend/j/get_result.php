<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['quiz_result'])) {
    $result = $_SESSION['quiz_result'];
    echo json_encode([
        'success' => true,
        'score' => $result['score'],
        'total' => $result['total'],
        'quiz_id' => $result['quiz_id'],
        'time' => $result['time']
    ]);
} else {
    echo json_encode(['success' => false]);
}
