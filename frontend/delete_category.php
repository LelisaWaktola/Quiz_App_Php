<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Category</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
      padding: 2rem;
    }

    .container {
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #1d3557;
    }

    select, button {
      width: 100%;
      padding: 0.75rem;
      margin: 1rem 0;
      font-size: 1rem;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button {
      background-color: #e63946;
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #d62828;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Delete Category</h2>
    <form action="../backend/api/delete_category2.php" method="post">
      <label for="category">Select Category:</label>
      <select name="category_name" id="category" required>
        <!-- Category options will be populated via PHP -->
        <?php
        require '../backend/db/connection.php';
        $res = $conn->query("SELECT name FROM categories");
        while ($row = $res->fetch_assoc()) {
          echo "<option value=\"" . htmlspecialchars($row['name']) . "\">" . htmlspecialchars($row['name']) . "</option>";
        }
        ?>
      </select>
      <button type="submit" onclick="return confirm('Are you sure you want to delete this category?');">Delete Category</button>
    </form>
  </div>
</body>
</html>
