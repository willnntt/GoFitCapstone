<?php
  include '../conn.php';

  session_start();

  if (!isset($_SESSION['user_id'])) {
      header('Location: ../../index.php');
      exit;
  }  

  $food = [
      'food_id' => '',
      'name' => '',
      'brand' => '',
      'calories' => '',
      'portion_unit' => '',
      'carbs' => '',
      'protein' => '',
      'fats' => ''
  ];

  if (isset($_GET['food_id'])) {
      $food_id = intval($_GET['food_id']);
      if ($food_id > 0) {
          $stmt = $conn->prepare("SELECT * FROM foods WHERE food_id = ?");
          $stmt->bind_param("i", $food_id);
          $stmt->execute();
          $result = $stmt->get_result();
          if ($result && $result->num_rows === 1) {
              $food = $result->fetch_assoc();
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
  <title>Diet Tracker</title>
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
        <h2>Create New Exercise Record</h2>

        <a href="food_database.php">
          <button class="back-btn">Back</button>
        </a>
      </div>

      <div class="form-container">
        <div class="form-box">
          <form action="admin_save_food.php" method="POST" id="foodForm">
            <input type="hidden" name="food_id" id="food_id" value="<?php echo htmlspecialchars($food['food_id']); ?>" />

            <div class="form-group">
              <label for="name">Food Name:</label>
              <input type="text" id="name" name="name" placeholder="Full Cream Milk" required
                    value="<?php echo htmlspecialchars($food['name']); ?>" />
            </div>

            <div class="form-group">
              <label for="brand">Brand Name:</label>
              <input type="text" id="brand" name="brand" placeholder="Marigold HL" required
                    value="<?php echo htmlspecialchars($food['brand']); ?>" />
            </div>

            <div class="form-group">
              <label for="calories">Calories (kcal):</label>
              <input type="number" id="calories" name="calories" placeholder="800" required
                    value="<?php echo htmlspecialchars($food['calories']); ?>" />
            </div>

            <div class="form-group">
              <label for="portion_unit">Portion Unit:</label>
              <select id="portion_unit" name="portion_unit" required>
                <option value="" disabled <?php echo empty($food['portion_unit']) ? 'selected' : ''; ?>>Select Portion Unit</option>
                <?php
                $units = ['100g', '100ml', '1 piece', '1 slice', '1 tablespoon', '1 teaspoon', '1 serving', '1 cup'];
                foreach ($units as $unit) {
                    $selected = ($food['portion_unit'] === $unit) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($unit) . "\" $selected>$unit</option>";
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label for="carbs">Carbs (g):</label>
              <input type="number" step="0.01" id="carbs" name="carbs" placeholder="0" required
                    value="<?php echo htmlspecialchars($food['carbs']); ?>" />
            </div>

            <div class="form-group">
              <label for="protein">Protein (g):</label>
              <input type="number" step="0.01" id="protein" name="protein" placeholder="40" required
                    value="<?php echo htmlspecialchars($food['protein']); ?>" />
            </div>

            <div class="form-group">
              <label for="fats">Fats (g):</label>
              <input type="number" step="0.01" id="fats" name="fats" placeholder="25" required
                    value="<?php echo htmlspecialchars($food['fats']); ?>" />
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