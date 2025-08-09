<?php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
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

</head>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="menu-toggle">
        <img src="../../assets/icons/hamburger.png" alt="Menu" class="hamburger-icon">
      </div>

      <ul>
        <li>
          <a href="user_database.php">
            <div class="nav-item">
              <img src="../../assets/icons/user.png" alt="User Icon" class="icon">
              <span>Dashboard</span>
            </div>
          </a>
        </li>
        <li>
          <a href="exercise_database.php">
            <div class="nav-item">
              <img src="../../assets/icons/exercise.png" alt="Exercise Icon" class="icon">
              <span>Exercise Log</span>
            </div>
          </a>
        </li>
        <li class="active">
          <a href="calorie_tracker.php">
            <div class="nav-item">
              <img src="../../assets/icons/diet.png" alt="Diet Icon" class="icon">
              <span>Calorie Tracker</span>
            </div>
          </a>
        </li>
        <li>
          <a href="food_database.php">
            <div class="nav-item">
              <img src="../../assets/icons/food.png" alt="Food Icon" class="icon">
              <span>Diet Plans</span>
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

      <div class="donut-chart" id="donut">
        <span id="remaining">Remaining<br>2000 kcal</span>
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

      <a class="clear-btn" href="delete_meal_log.php?all=true" onclick="return confirm('Delete all logs for today?')">Clear All</a>
      
    </div>
  </div>
  <script src="../../javascript/calorie_tracker.js"></script>
  <script src="../../javascript/sidebar.js"></script>
</body>

</html>