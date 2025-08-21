<?php
  include '../conn.php';

  session_start();

  if (!isset($_SESSION['user_id'])) {
      header('Location: ../../index.php');
      exit;
  }


  $exercise = [
      'exercise_id' => '',
      'name' => '',
      'category' => '',
      'difficulty' => '',
      'description' => '',
  ];

  if (isset($_GET['exercise_id'])) {
      $exercise_id = intval($_GET['exercise_id']);
      if ($exercise_id > 0) {
          $stmt = $conn->prepare("SELECT * FROM exercises WHERE exercise_id = ?");
          $stmt->bind_param("i", $exercise_id);
          $stmt->execute();
          $result = $stmt->get_result();
          if ($result && $result->num_rows === 1) {
              $exercise = $result->fetch_assoc();
          }
          $stmt->close();
      }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gofit | Exercise Database</title>
  <link rel="stylesheet" href="../../css/sidebar.css" />
  <link rel="stylesheet" href="../../css/admin_database.css" />
</head>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="menu-toggle">
        <img src="../../assets/icons/hamburger.png" alt="Menu" class="hamburger-icon">
      </div>

      <div class="sidebar-toggle-arrow" style="display: none;">
        <img src="../../assets/icons/arrow-right.png" alt="Show Sidebar" />
      </div>

      <ul>
        <li>
          <a href="user_database.php">
            <div class="nav-item">
              <img src="../../assets/icons/user.png" alt="User Icon" class="icon">
              <span>User Database</span>
            </div>
          </a>
        </li>
        <li class="active">
          <a href="exercise_database.php">
            <div class="nav-item">
              <img src="../../assets/icons/exercise.png" alt="Exercise Icon" class="icon">
              <span>Exercise Database</span>
            </div>
          </a>
        </li>
        <li>
          <a href="diet_database.php">
            <div class="nav-item">
              <img src="../../assets/icons/diet.png" alt="Diet Icon" class="icon">
              <span>Diet Plan Database</span>
            </div>
          </a>
        </li>
        <li>
          <a href="food_database.php">
            <div class="nav-item">
              <img src="../../assets/icons/food.png" alt="Food Icon" class="icon">
              <span>Foods Database</span>
            </div>
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="header">
        <h1>GoFit Database Management</h1>
      </div>

      <div class="sub-header">
        <h2>Exercise Record</h2>
        
        <a href="exercise_database.php">
          <button class="back-btn">Back</button>
        </a>
      </div>

      <div class="form-container">
        <div class="form-box">
          <form action="admin_save_exercise.php" method="POST" id="exerciseForm">
            <input type="hidden" name="exercise_id" id="exercise_id" value="<?php echo htmlspecialchars($exercise['exercise_id'] ?? ''); ?>" />

            <div class="form-group">
              <label for="name">Exercise Name:</label>
              <input type="text" id="name" name="name" placeholder="Dumbbell Benchpress" required
                    value="<?php echo htmlspecialchars($exercise['name'] ?? ''); ?>" />
            </div>

            <div class="form-group">
              <label for="category">Category:</label>
              <select id="category" name="category" required>
                <option value="" disabled <?php echo empty($exercise['category']) ? 'selected' : ''; ?>>Select Category</option>
                <option value="Strength" <?php echo (isset($exercise['category']) && $exercise['category'] === 'Strength') ? 'selected' : ''; ?>>Strength</option>
                <option value="Aerobic" <?php echo (isset($exercise['category']) && $exercise['category'] === 'Aerobic') ? 'selected' : ''; ?>>Aerobic</option>
              </select>
            </div>

            <div class="form-group">
              <label for="difficulty">Difficulty:</label>
              <select id="difficulty" name="difficulty" required>
                <option value="" disabled <?php echo empty($exercise['difficulty']) ? 'selected' : ''; ?>>Select difficulty</option>
                <option value="Beginner" <?php echo (isset($exercise['difficulty']) && $exercise['difficulty'] === 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
                <option value="Intermediate" <?php echo (isset($exercise['difficulty']) && $exercise['difficulty'] === 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                <option value="Advanced" <?php echo (isset($exercise['difficulty']) && $exercise['difficulty'] === 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
              </select>
            </div>

            <div class="form-buttons">
              <input type="submit" value="Submit" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="../../javascript/sidebar.js"></script>
</body>

</html>