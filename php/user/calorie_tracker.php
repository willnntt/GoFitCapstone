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
  <link rel="stylesheet" href="../../css/calorietracker.css" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

</head>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="menu-toggle">
        <img src="../../assets/icons/hamburger.png" alt="Menu" class="hamburger-icon">
      </div>

      <ul>
        <li class="active">
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
        <li>
          <a href="diet_database.php">
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
        <h2 style="margin: 0;">Calorie Tracker</h2>
        <button class="add-btn">＋</button>
      </div>

      <div class="donut-chart" id="donut">
        <span id="remaining">Remaining<br>2000 kcal</span>
      </div>

      <h2>Calories Consumed</h2>

      <div class="meal">
        <div class="meal-header">
          <span class="meal-title">Breakfast ⌄</span>
          <div class="meal-summary" id="breakfast-summary"></div>
        </div>
        <div class="food-list" id="breakfast-list">
          <ul></ul>
        </div>
      </div>

      <div class="meal">
        <div class="meal-header">
          <span class="meal-title">Lunch ⌄</span>
          <div class="meal-summary" id="lunch-summary"></div>
        </div>
        <div class="food-list" id="lunch-list">
          <ul></ul>
        </div>
      </div>

      <div class="meal">
        <div class="meal-header">
          <span class="meal-title">Dinner ⌄</span>
          <div class="meal-summary" id="dinner-summary"></div>
        </div>
        <div class="food-list" id="dinner-list">
          <ul></ul>
        </div>
      </div>

      <div class="meal">
        <div class="meal-header">
          <span class="meal-title">Snacks ⌄</span>
          <div class="meal-summary" id="snacks-summary"></div>
        </div>
        <div class="food-list" id="snacks-list">
          <ul></ul>
        </div>
      </div>

      <div class="overlay"></div>

      <div class="food-menu" id="foodMenu">
        <h3>Select Food</h3>

        <div class="search-bar">
          <input type="text" id="searchInput" placeholder="Hinted search text">
          <button>🔍</button>
        </div>

        <div class="food-scroll-list" id="foodScrollList">
        </div>

        <br />
        <label>Choose Meal:</label>
        <select id="mealSelect">
          <option value="breakfast">Breakfast</option>
          <option value="lunch">Lunch</option>
          <option value="dinner">Dinner</option>
          <option value="snack">Snacks</option>
        </select>
      </div>

      <button class="clear-btn" href="delete_meal_log.php?all=true" onclick="return confirm('Delete all logs for today?')">Clear All</button>
    </div>
  </div>
  <script src="../../javascript/calorie_tracker.js"></script>
  <script src="../../javascript/sidebar.js"></script>
</body>

</html>