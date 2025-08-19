<?php
  include '../conn.php';
  session_start();

  $user_id = $_SESSION['user_id'];

      if (!isset($_SESSION['user_id'])) {
        header('Location: ../../index.php');
        exit;
    }

  $query = "SELECT calorie_goal FROM user_data WHERE user_id = '$user_id'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $calorie_goal = (int)$row['calorie_goal'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Calorie Tracker</title>
  <link rel="stylesheet" href="../../css/sidebar.css" />
  <link rel="stylesheet" href="../../css/calorietracker.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script>
    window.calorie_goal = <?php echo $calorie_goal; ?>;
  </script>

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
          <a href="dashboard.php">
            <div class="nav-item">
              <img src="../../assets/icons/user.png" alt="User Icon" class="icon">
              <span>Dashboard</span>
            </div>
          </a>
        </li>
        <li class="active">
          <a href="calorie_tracker.php">
            <div class="nav-item">
              <img src="../../assets/icons/food.png" alt="Food Icon" class="icon">
              <span>Calorie Tracker</span>
            </div>
          </a>
        </li>
        <li>
          <a href="exercise_log.php">
            <div class="nav-item">
              <img src="../../assets/icons/exercise.png" alt="Exercise Icon" class="icon">
              <span>Exercise Log</span>
            </div>
          </a>
        </li>
        <li>
          <a href="diet_plan.php">
            <div class="nav-item">
              <img src="../../assets/icons/diet.png" alt="Diet Icon" class="icon">
              <span>Diet Plan</span>
            </div>
          </a>
        </li>
        <li>
          <a href="setting.php">
            <div class="nav-item">
              <img src="../../assets/icons/setting.png" alt="Setting Icon" class="icon">
              <span>Settings</span>
            </div>
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="header">
        <h2>Calorie Tracker</h2>
      </div>

      <div class="sub-header">
        <a href="../../index.php">
          <button class="back-btn">Log out</button>
        </a>    
      </div>

      <div class="donut-chart" id="donut">
        <span id="remaining">Remaining<br> <?= $calorie_goal ?> </span>
      </div>

      <h2>Calories Consumed</h2>

      <div class="meal-section" data-meal="breakfast">
        <div class="meal-header">
          <span class="meal-name">Breakfast</span>
          <div class="meal-actions">
            <i class="fa-solid fa-plus openFoodMenu" data-meal="breakfast"></i>
            <i id="toggle-icon-breakfast" class="fa-solid fa-chevron-up"></i>
          </div>
        </div>
        <ul class="meal-food-list">
          <!-- Food items will be inserted here -->
        </ul>
      </div>

      <div class="meal-section" data-meal="lunch">
        <div class="meal-header">
          <span class="meal-name">Lunch</span>
          <div class="meal-actions">
            <i class="fa-solid fa-plus openFoodMenu" data-meal="lunch"></i>
            <i id="toggle-icon-lunch" class="fa-solid fa-chevron-up"></i>
          </div>
        </div>
        <ul class="meal-food-list">
          <!-- Food items will be inserted here -->
        </ul>
      </div>

      <div class="meal-section" data-meal="dinner">
        <div class="meal-header">
          <span class="meal-name">Dinner</span>
          <div class="meal-actions">
            <i class="fa-solid fa-plus openFoodMenu" data-meal="dinner"></i>
            <i id="toggle-icon-dinner" class="fa-solid fa-chevron-up"></i>
          </div>
        </div>
        <ul class="meal-food-list">
          <!-- Food items will be inserted here -->
        </ul>
      </div>

      <div class="meal-section" data-meal="snack">
        <div class="meal-header">
          <span class="meal-name">Snack</span>
          <div class="meal-actions">
            <i class="fa-solid fa-plus openFoodMenu" data-meal="snack"></i>
            <i id="toggle-icon-snack" class="fa-solid fa-chevron-up"></i>
          </div>
        </div>
        <ul class="meal-food-list">
          <!-- Food items will be inserted here -->
        </ul>
      </div>

      <div class="overlay"></div>

      <div class="food-menu" id="foodMenu">
        <h3>Select Food</h3>

        <div class="search-bar">
          <input type="text" id="searchInput" placeholder="Search Food">
          <button>
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>

        <div class="food-scroll-list" id="foodScrollList">
        </div>
      </div>

      <a class="clear-btn" href="delete_meal_log.php?all=true"
        onclick="return confirm('Delete all logs for today?')">Clear All</a>

    </div>
  </div>
  <script src="../../javascript/calorie_tracker.js"></script>
  <script src="../../javascript/sidebar.js"></script>
</body>

</html>