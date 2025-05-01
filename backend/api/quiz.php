<?php
// quiz.php

require '../db/connection.php'; // adjust the path to your DB connection file

// Step 1: Get the subject from URL
$subject = isset($_GET['subject']) ? strtolower(trim($_GET['subject'])) : null;

if (!$subject) {
  die("No subject provided.");
}

// Step 2: Fetch the category ID
$stmt = $conn->prepare("SELECT id FROM categories WHERE LOWER(name) = ?");
$stmt->bind_param("s", $subject);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
  die("Invalid subject.");
}

$categoryId = $category['id'];

// Step 3: Fetch questions (you can change this to fetch quizzes if needed)
// Step 3: Fetch questions (corrected logic)
$qStmt = $conn->prepare("
  SELECT q.id, q.question_text
  FROM questions q
  INNER JOIN quizzes z ON q.quiz_id = z.id
  WHERE z.category_id = ?
");
$qStmt->bind_param("i", $categoryId);
$qStmt->execute();
$questions = $qStmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Quiz - <?php echo htmlspecialchars(ucfirst($subject)); ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2rem;
      background: #f1f8ff;
    }
    h2 {
      color: #1d3557;
    }
    .question {
      margin-bottom: 2rem;
      background: white;
      padding: 1rem;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .submit-btn {
  background-color: #1d3557;
  color: #fff;
  border: none;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
  margin-top: 1rem;
}

.submit-btn:hover {
  background-color: #457b9d;
  transform: translateY(-2px);
}

.submit-btn:active {
  background-color: #1d3557;
  transform: scale(0.98);
}

  </style>
</head>
<body>
  <h2><?php echo ucfirst($subject); ?> Quiz</h2>

  <?php if ($questions->num_rows > 0): ?>
    <form method="post" action="submit_quiz.php">
  <?php 
  $qIndex = 1; // Question counter
  while ($q = $questions->fetch_assoc()): 
    // Fetch answers for the question
    $aStmt = $conn->prepare("SELECT * FROM options WHERE question_id = ?");
    $aStmt->bind_param("i", $q['id']);
    $aStmt->execute();
    $answers = $aStmt->get_result();

    // Only show question if it has options
    if ($answers->num_rows > 0):
  ?>
    <div class="question">
      <p><strong><?php echo $qIndex++ . '. ' . htmlspecialchars($q['question_text']); ?></strong></p>

      <?php
      $optionLabels = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
      $oIndex = 0;
      while ($a = $answers->fetch_assoc()):
      ?>
        <label>
          <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="<?php echo htmlspecialchars($a['option_text']); ?>" required>
          <?php echo $optionLabels[$oIndex++] . '. ' . htmlspecialchars($a['option_text']); ?>
        </label><br>
      <?php endwhile; ?>
    </div>
  <?php 
    endif;
  endwhile; 
  ?>
  <button type="submit" class="submit-btn">📝 Submit Quiz</button>

</form>

  <?php else: ?>
    <p>No questions available for this subject.</p>
  <?php endif; ?>
</body>
</html>

