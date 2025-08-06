<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GoFit Diet Plan</title>
  <link rel="stylesheet" href="../../css/dietdatabase.css" />
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

    <div class="main-content">
      <div class="header">
        <h1>GoFit Database Management</h1>
      </div>

      <div class="sub-header">
        <h2 class="plan-name">
          Diet Plan Name
          <img src="../../assets/icons/pen.png" alt="Edit Icon" class="edit-icon">
        </h2>
        <button class="back-btn">Back</button>
      </div>

      <div class="picture-box">
        <img src=".." alt="Diet Plan Image" class="diet-image">
      </div>

      <div class="day-selector">
        <i class="fa-solid fa-chevron-left"></i>
        <span>Day 1</span>
        <i class="fa-solid fa-chevron-right"></i>
      </div>

      <div class="meal-table" id="breakfast">
        <div class="meal-header">
          <div class="meal-title">
            <span>Breakfast</span>
            <i class="fa-solid fa-plus"></i>
          </div>
          <i id="toggle-icon-breakfast" class="fa-solid fa-chevron-up"></i>
        </div>
        <table id="food-table-breakfast">
          <thead>
            <tr>
              <th>Food</th>
              <th>Portion Size</th>
              <th>Serving Size</th>
              <th>Calories (kcal)</th>
              <th>Carbs (g)</th>
              <th>Protein (g)</th>
              <th>Fats (g)</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>

            </tr>
          </tbody>
        </table>
      </div>

      <div class="meal-table" id="lunch">
        <div class="meal-header">
          <div class="meal-title">
            <span>Lunch</span>
            <i class="fa-solid fa-plus"></i>
          </div>
          <i id="toggle-icon-lunch" class="fa-solid fa-chevron-up"></i>
        </div>
        <table id="food-table-lunch">
          <thead>
            <tr>
              <th>Food</th>
              <th>Portion Size</th>
              <th>Serving Size</th>
              <th>Calories (kcal)</th>
              <th>Carbs (g)</th>
              <th>Protein (g)</th>
              <th>Fats (g)</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>

            </tr>
          </tbody>
        </table>
      </div>

      <div class="meal-table" id="dinner">
        <div class="meal-header">
          <div class="meal-title">
            <span>Dinner</span>
            <i class="fa-solid fa-plus"></i>
          </div>
          <i id="toggle-icon-dinner" class="fa-solid fa-chevron-up"></i>
        </div>
        <table id="food-table-dinner">
          <thead>
            <tr>
              <th>Food</th>
              <th>Portion Size</th>
              <th>Serving Size</th>
              <th>Calories (kcal)</th>
              <th>Carbs (g)</th>
              <th>Protein (g)</th>
              <th>Fats (g)</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>

            </tr>
          </tbody>
        </table>
      </div>

      <button class="delete-button">
        <i class="fa-solid fa-trash"></i> Delete Diet Plan
      </button>

    </div>

  </div>
  </div>

  <script src="../../javascript/sidebar.js"></script>
  <script src="../../javascript/admin_diet_plan.js"></script>
</body>

</html>