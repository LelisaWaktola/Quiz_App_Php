<?php
// Start session and check if admin is logged in (optional but recommended)
// session_start();
// if (!isset($_SESSION['is_admin'])) {
//     header("Location: ../../frontend/admin_login.html");
//     exit();
// }

require '../db/connection.php'; // Your DB connection file

// Fetch quiz history from database
$sql = "SELECT users.name, quizzes.title AS quiz_title, results.score, results.taken_at
        FROM results
        JOIN users ON results.user_id = users.id
        JOIN quizzes ON results.quiz_id = quizzes.id
        ORDER BY results.taken_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View User History</title>
  <link rel="stylesheet" href="styles.css"> <!-- Your CSS file -->
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background-color: #f8f8f8;
    }

    h2 {
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }

    th {
      background-color: #007BFF;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .back {
      display: block;
      margin-top: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
  <h2>User Quiz History</h2>
  <table>
    <thead>
      <tr>
        <th>User</th>
        <th>Quiz</th>
        <th>Score</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['quiz_title']); ?></td>
            <td><?php echo htmlspecialchars($row['score']); ?></td>
            <td><?php echo htmlspecialchars($row['taken_at']); ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="4">No quiz history found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  <a href="../../frontend/admin_dashboard.html" class="back">← Back to Dashboard</a>
</body>
</html>
