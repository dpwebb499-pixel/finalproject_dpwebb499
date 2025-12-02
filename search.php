<?php
// search.php

// Database connection settings
$host = 'localhost';
$user = 'root';
$pass = 'mysql'; // default as requested; change if you use a different password
$db   = 'student_directory';

// Connect using mysqli object
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_errno) {
    die('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . htmlspecialchars($mysqli->connect_error));
}

// Retrieve POST parameter (safe default)
$lName = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lName = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    // DEBUG
// echo "<p>Searching for: " . htmlspecialchars($lName) . "</p>";

}

// Prepare and call stored procedure securely
$results = [];

if ($lName !== '') {
    $query = $mysqli->prepare("CALL `search_students`(?)");
    $query->bind_param("s", $lName);
    
    if ($query->execute()) {
        
        $result = $query->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            $result->free();
        }

        // Clear additional result sets
        while ($mysqli->more_results() && $mysqli->next_result()) {
            $extra = $mysqli->use_result();
            if ($extra instanceof mysqli_result) {
                $extra->free();
            }
        }
    }

    $query->close();
}

// close connection
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Search Results - Student Directory</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="styles/main.css">
</head>
<body>

  <h1>Search Results</h1>
  <p><a href="index.php">&larr; Return to Home</a></p>

<?php if ($lName === ''): ?>
  <p>Please enter a last name to search. <a href="index.php">Go back</a>.</p>
<?php else: ?>

  <?php if (!empty($results)): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($results as $student): ?>
        <tr>
          <td><?php echo htmlspecialchars($student['id']); ?></td>
          <td><?php echo htmlspecialchars($student['first_name']); ?></td>
          <td><?php echo htmlspecialchars($student['last_name']); ?></td>
          <td><?php echo htmlspecialchars($student['email']); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p><strong>No students found.</strong></p>
  <?php endif; ?>

<?php endif; ?>

  <script src="scripts/main.js"></script>
</body>
</html>
