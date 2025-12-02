<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Final Project - Student Directory</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="styles/main.css">
</head>
<body>

  <h1>Student Directory Final Project</h1>

  <!-- Replace the name below with your full name -->
  <p><strong>Author:</strong> Dylan Webb</p>

  <!-- Date paragraph (dynamic) -->
  <p><strong>Date:</strong> <?php echo date('F j, Y'); ?></p>

  <form method="POST" action="search.php" autocomplete="off" novalidate>
    <label for="last_name">Search by last name (start of last name):</label>
    <input type="text" id="last_name" name="last_name" placeholder="e.g., Smith" required>
    <button type="submit">Search</button>
  </form>

  <script src="scripts/main.js"></script>
</body>
</html>
