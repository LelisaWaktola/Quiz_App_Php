<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quiz Dashboard</title>
  <link rel="stylesheet" href="css/quiz_dashboard.css" />
</head>
<body>
  <?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
    require_once '../backend/db/connection.php';
    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);
  ?>

  <div class="container">
   
    <div class="upper">
      <h2 class="hero-heading">Explore Quiz Categories</h2>
      <a class="logout-btn" href="../backend/api/logout.php">Logout</a>
    </div>

    <div class="grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php  $count=0; ?>
        <div class="card">
          <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5><!-- for security ex . <script>-->
          <p id="<?php echo $row['id'] ?>"><?php echo htmlspecialchars($row['description']); ?></p>
          <a href="../backend/api/quiz.php?subject=<?php echo urlencode(strtolower($row['name'])); ?>&id=<?php echo $row['id']; ?>" class="btn">Start Quiz</a>
          </div>
      <?php endwhile; ?>
    </div>
  </div>
  <script src="js/quiz_dashboard.js"></script>
</body>
</html>
