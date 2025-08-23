<?php
  include '../conn.php';

  session_start();

  if (!isset($_SESSION['user_id'])) {
      header('Location: ../../index.php');
      exit;
  }

  // Get user ID from query
  $user_id = $_GET['user_id'] ?? 0;
  $user = null;
  $activity_level = '';
  $weight_change = '';

  if ($user_id) {
      $stmt = mysqli_prepare($conn, "SELECT * FROM user_data WHERE user_id = ?");
      mysqli_stmt_bind_param($stmt, "i", $user_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $user = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);

      if ($user) {
          $activity_level = $user['activity_level'] ?? '';
          $weight_change = $user['weight_change'] ?? '';
      }
  }
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gofit | Admin Database</title>
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
        <img src="../../assets/icons/arrow-right.png" alt="Show Sidebar" class="icon">
      </div>

      <ul>
        <li class="active">
          <a href="user_database.php">
            <div class="nav-item">
              <img src="../../assets/icons/user.png" alt="User Icon" class="icon">
              <span>User Database</span>
            </div>
          </a>
        </li>
        <li>
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
        <h2>Edit User Record</h2>

        <a href="user_database.php">
          <button class="back-btn">Back</button>
        </a>
      </div>

      <div class="form-container">
        <div class="form-box">
          <form id="userForm" method="POST" action="admin_update_user.php">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id'] ?? ''); ?>">

            <div class="form-group">
              <label for="username">Username:</label>
              <input type="text" name="username" id="username"
                value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
              <label for="dob">Date of Birth:</label>
              <input type="date" name="dob" id="dob"
                value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                required>
            </div>

            <div class="form-group">
              <label for="gender">Gender:</label>
              <select name="gender" id="gender" required>
                <option value="Male" <?php echo (isset($user['gender']) && $user['gender']=='Male' ) ? 'selected' : '' ;
                  ?>>Male</option>
                <option value="Female" <?php echo (isset($user['gender']) && $user['gender']=='Female' ) ? 'selected'
                  : '' ; ?>>Female</option>
              </select>
            </div>

            <div class="form-group">
              <label for="height">Height (cm):</label>
              <input type="number" step="0.1" name="height" id="height"
                value="<?php echo htmlspecialchars($user['height'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
              <label for="weight">Weight (kg):</label>
              <input type="number" step="0.1" name="weight" id="weight"
                value="<?php echo htmlspecialchars($user['weight'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
              <label for="goal_weight">Goal Weight (kg):</label>
              <input type="number" step="0.1" name="goal_weight" id="goal_weight"
                value="<?php echo htmlspecialchars($user['goal_weight'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
              <label for="activity_level">Activity Level:</label>
              <select name="activity_level" id="activity_level" required>
                  <option value="Sedentary" <?php if($activity_level == 'Sedentary') echo 'selected'; ?>>Sedentary</option>
                  <option value="Lightly active" <?php if($activity_level == 'Lightly active') echo 'selected'; ?>>Lightly active</option>
                  <option value="Moderately active" <?php if($activity_level == 'Moderately active') echo 'selected'; ?>>Moderately active</option>
                  <option value="Very active" <?php if($activity_level == 'Very active') echo 'selected'; ?>>Very active</option>
                  <option value="Extra active" <?php if($activity_level == 'Extra active') echo 'selected'; ?>>Extra active</option>
              </select>
            </div>

            <div class="form-group">
              <label for="weight_change">Weight Change per Week (kg):</label>
              <select name="weight_change" id="weight_change" required>
                  <option value="0.25" <?php if($weight_change == '0.25') echo 'selected'; ?>>0.25 kg</option>
                  <option value="0.5" <?php if($weight_change == '0.5') echo 'selected'; ?>>0.5 kg</option>
                  <option value="0.75" <?php if($weight_change == '0.75') echo 'selected'; ?>>0.75 kg</option>
              </select>
            </div>

            <div class="form-group">
              <label for="bmi">BMI:</label>
              <input type="text" id="bmi" value="<?php echo htmlspecialchars($user['bmi'] ?? ''); ?>" readonly>
            </div>

            <div class="form-group">
              <label for="calorie_goal">Calorie Goal (kcal):</label>
              <input type="text" id="calorie_goal" value="<?php echo htmlspecialchars($user['calorie_goal'] ?? ''); ?>"
                readonly>
            </div>

            <div class="form-buttons">
              <input type="submit" value="Update">
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="../../javascript/sidebar.js"></script>
    <script src="../../javascript/admin_user_records.js"></script>
</body>

</html>