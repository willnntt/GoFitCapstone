<?php
  include '../conn.php';

  session_start();

  if (!isset($_SESSION['user_id'])) {
      header('Location: ../../index.php');
      exit;
  }

  $plan_id = isset($_GET['plan_id']) ? intval($_GET['plan_id']) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GoFit Diet Plan</title>
  <link rel="stylesheet" href="../../css/sidebar.css" />
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
        <li>
          <a href="exercise_database.php">
            <div class="nav-item">
              <img src="../../assets/icons/exercise.png" alt="Exercise Icon" class="icon">
              <span>Exercise Database</span>
            </div>
          </a>
        </li>
        <li class="active">
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
          <span class="plan-name-text">Diet Plan Name</span>
          <img src="../../assets/icons/pen.png" alt="Edit Icon" class="edit-icon" id="editBtn">
        </h2>
        <button class="back-btn" onclick="window.location.href='diet_database.php'">Back</button>
      </div>

      <div class="picture-box">
          <img src="" class="diet-image" id="dietImagePreview" alt="Diet Plan Image">
      </div>

      <div class="description-box">
        <p class="description-text">
          This is a sample description of the diet plan. It provides an overview of the diet, its benefits, and what
          users can expect.
        </p>
      </div>

      <div class="day-selector">
        <i class="fa-solid fa-chevron-left" id="prevDay"></i>
        <span>Day 1</span>
        <i class="fa-solid fa-chevron-right" id="nextDay"></i>
      </div>

      <div class="meal-table" id="breakfast">
        <div class="meal-header">
          <div class="meal-title">
            <span>Breakfast</span>
            <i class="fa-solid fa-plus openFoodMenu" data-meal="breakfast"></i>
          </div>
          <i id="toggle-icon-breakfast" class="fa-solid fa-chevron-up"></i>
        </div>
        <table id="food-table-breakfast">
          <thead>
            <tr>
              <th>Food</th>
              <th>Brand</th>
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
            <i class="fa-solid fa-plus openFoodMenu" data-meal="lunch"></i>
          </div>
          <i id="toggle-icon-lunch" class="fa-solid fa-chevron-up"></i>
        </div>
        <table id="food-table-lunch">
          <thead>
            <tr>
              <th>Food</th>
              <th>Brand</th>
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
            <i class="fa-solid fa-plus openFoodMenu" data-meal="dinner"></i>
          </div>
          <i id="toggle-icon-dinner" class="fa-solid fa-chevron-up"></i>
        </div>
        <table id="food-table-dinner">
          <thead>
            <tr>
              <th>Food</th>
              <th>Brand</th>
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

      <div class="meal-table" id="snack">
        <div class="meal-header">
          <div class="meal-title">
            <span>Snack</span>
            <i class="fa-solid fa-plus openFoodMenu" data-meal="snack"></i>
          </div>
          <i id="toggle-icon-snack" class="fa-solid fa-chevron-up"></i>
        </div>
        <table id="food-table-snack">
          <thead>
            <tr>
              <th>Food</th>
              <th>Brand</th>
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

      <div class="overlay"></div>

      <!-- Food Selection Menu -->
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

      <!-- Edit Diet Plan Form -->
      <div class="food-menu" id="editPlanMenu">
        <h3>Edit Diet Plan</h3>

        <div class="form-container">
          <div class="form-box">
            <form action="admin_edit_dietplan.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="plan_id" />

              <div class="form-group">
                <label for="name">Diet Plan Name:</label>
                <input type="text" id="name" name="name" required />
              </div>

              <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" placeholder="Enter full description..." required></textarea>
              </div>

              <div class="form-group">
                <label>Diet Plan Image:</label>
                <div class="image-preview" id="dietImagePreview" style="border:1px solid #ccc; padding:10px; max-width: 400px;">
                  <img src="" alt="Diet Plan Image" id="dietImagePreviewImg" style="max-width:100%; display:none; border-radius: 6px;">
                </div>
                <input type="file" id="dietImageInput" name="diet_image" accept="image/*" style="display:none;">
                <button type="button" id="uploadImageBtn" style="margin-top: 8px;">Choose Image</button>
              </div>

              <div class="form-buttons" style="margin-top: 15px;">
                <input type="reset" value="Reset" />
                <input type="submit" value="Submit" />
              </div>
            </form>
          </div>
        </div>
      </div>

      <button class="delete-button" data-plan-id="<?= $plan_id ?>" id="deleteBtn">
        <i class="fa-solid fa-trash"></i> Delete Diet Plan
      </button>

    </div>
  </div>

  <script src="../../javascript/sidebar.js"></script>
  <script src="../../javascript/admin_dietplan_info.js"></script>
</body>

</html>