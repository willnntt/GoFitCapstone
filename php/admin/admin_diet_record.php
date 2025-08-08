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
        <h2>Create New Diet Plan</h2>

        <a href="diet_database.php">
          <button class="back-btn">Back</button>
        </a>
      </div>

      <div class="form-container">
        <div class="form-box">
          <form action="admin_create_dietplan.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                  <label for="name">Diet Plan Name:</label>
                  <input type="text" id="name" name="name" placeholder="Keto Diet" required />
              </div>

              <div class="form-group">
                  <label for="description">Description:</label>
                  <textarea id="description" name="description" rows="4" placeholder="Enter full description..." required></textarea>
              </div>

              <div class="form-group">
                  <label for="image">Diet Plan Image:</label>
                  <input type="file" id="image" name="image" accept="image/*" />
              </div>

              <div class="form-buttons">
                  <input type="reset" value="Reset" />
                  <input type="submit" value="Create Diet Plan" />
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="../../javascript/sidebar.js"></script>
</body>

</html>