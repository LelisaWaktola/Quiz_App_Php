<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quiz Dashboard</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0eafc, #cfdef3);
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
    }
    .hero-heading {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 2rem;
      text-align: center;
      color: #1d3557;
      text-shadow: 1px 1px 2px #a8dadc;
    }
    .grid {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      justify-content: center;
    }
    .card {
      background: linear-gradient(to bottom right, #ffffff, #f1f8ff);
      border-radius: 1rem;
      padding: 2rem 1.5rem;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      flex: 1 1 260px;
      max-width: 320px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .card::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle at center, rgba(255,255,255,0.15), transparent 70%);
  animation: rotate 6s linear infinite;
  pointer-events: none; /* ✅ This fixes the click issue */
}

    @keyframes rotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.2);
    }
    .card-title {
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
      color: #2a2a2a;
    }
    .card-text {
      font-size: 1rem;
      color: #555;
      margin-bottom: 1.25rem;
    }
    .btn {
      display: inline-block;
      padding: 0.6rem 1.2rem;
      background-color: #1d3557;
      color: white;
      text-decoration: none;
      border-radius: 0.5rem;
      font-weight: bold;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn:hover {
      background-color: #457b9d;
      transform: scale(1.05);
    }
    @media (max-width: 768px) {
      .grid {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>
  <?php
    require_once '../backend/db/connection.php';
    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);
  ?>
  <div class="container">
    <h2 class="hero-heading">Explore Quiz Categories</h2>
    <div class="grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
          <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
          <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
          <a href="../backend/api/quiz.php?subject=<?php echo urlencode(strtolower($row['name'])); ?>" class="btn">Start Quiz</a>
          </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>

