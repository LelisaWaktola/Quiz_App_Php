<?php
require '../db/connection.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../frontend/login.html");
    exit();
}

$subject = isset($_GET['subject']) ? strtolower(trim($_GET['subject'])) : null;
$id_id=isset($_GET['id']);

$stmt = $conn->prepare("SELECT id FROM categories WHERE LOWER(name) = ?");
$stmt->bind_param("s", $subject);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();
$categoryId = $category['id'];

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
  <meta charset="UTF-8">
  <title>Quiz - <?php echo htmlspecialchars(ucfirst($subject)); ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2rem;
      background: #f1f8ff;
    }

    h2 {
      color: #1d3557;
      text-align: center;
    }
    form {
  max-width: 700px;
  margin: 0 auto;
}

.question {
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
  padding: 1.5rem 2rem;
  margin-bottom: 2rem;
  transition: all 0.3s ease;
}

.question p {
  font-size: 1.2rem;
  color: #1d3557;
  font-weight: 600;
  margin-bottom: 1rem;
}

.question label {
  display: block;
  background: #f1f8ff;
  padding: 0.75rem 1rem;
  border-radius: 10px;
  margin-bottom: 0.75rem;
  cursor: pointer;
  font-size: 1rem;
  transition: all 0.2s ease;
  border: 2px solid transparent;
  color: #333;
}

.question label:hover {
  background: #dcefff;
  border-color: #457b9d;
}

.question input[type="radio"] {
  margin-right: 0.75rem;
  transform: scale(1.2);
  vertical-align: middle;
  accent-color: #457b9d;
}

@media (max-width: 768px) {
  .question {
    padding: 1rem;
  }

  .question p {
    font-size: 1rem;
  }

  .question label {
    font-size: 0.95rem;
    padding: 0.65rem 0.9rem;
  }
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
      display: inline-block;
      text-align: center;
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


    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h2><?php echo ucfirst($subject); ?> Quiz</h2>

  <?php if ($questions->num_rows > 0): ?>
    <form method="post" action="submit_quiz.php">
      <?php 
      $qIndex = 1;
      while ($q = $questions->fetch_assoc()):
          $aStmt = $conn->prepare("SELECT * FROM options WHERE question_id = ?");
          $aStmt->bind_param("i", $q['id']);
          $aStmt->execute();
          $answers = $aStmt->get_result();

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
            </label>
          <?php endwhile; ?>
        </div>
      <?php 
          endif;
      endwhile;
      ?>
      <!-- <button type="submit" class="submit-btn">Submit Quiz</button> -->
    </form>
  <?php else: ?>
    <?php header('Location: ../../frontend/quiz_dashboard.php?noQuestion=empty&id=' . $id_id)?>
  <?php endif; ?>
  <script>
  const questions = document.querySelectorAll(".question");
  const form = document.querySelector("form");
  let currentQuestionIndex = 0;
  let timer;

  function showQuestion(index) {
    questions.forEach((q, i) => {
      q.style.display = i === index ? "block" : "none";
    });

    document.getElementById("next-btn").style.display = index < questions.length - 1 ? "inline-block" : "none";
    document.getElementById("submit-btn").style.display = index === questions.length - 1 ? "inline-block" : "none";

    startTimer();
  }

  function nextQuestion() {
  const currentQuestion = questions[currentQuestionIndex];
  const selectedOption = currentQuestion.querySelector('input[type="radio"]:checked');

  if (!selectedOption) {
    alert("Please select an answer before moving to the next question.");
    return;
  }

  clearTimeout(timer);
  currentQuestionIndex++;
  if (currentQuestionIndex < questions.length) {
    showQuestion(currentQuestionIndex);
  }
}


  function startTimer() {
    timer = setTimeout(() => {
      if (currentQuestionIndex < questions.length - 1) {
        nextQuestion();
      }
    }, 0.5 * 60 * 1000); // 3 minutes
  }

  document.addEventListener("DOMContentLoaded", () => {
    const nextBtn = document.createElement("button");
    nextBtn.type = "button";
    nextBtn.id = "next-btn";
    nextBtn.className = "submit-btn";
    nextBtn.textContent = "Next";
    nextBtn.addEventListener("click", nextQuestion);
    form.appendChild(nextBtn);

    const submitBtn = document.createElement("button");
    submitBtn.type = "submit";
    submitBtn.id = "submit-btn";
    submitBtn.className = "submit-btn";
    submitBtn.textContent = "Submit Quiz";
    submitBtn.style.display = "none";
    form.appendChild(submitBtn);

    showQuestion(currentQuestionIndex);
  });
</script>

</body>
</html>
