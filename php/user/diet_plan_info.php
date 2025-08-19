<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php"); 
        exit();
    }   
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Plan</title>
    <link rel="stylesheet" href="../../css/dietplan.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="wrapper">
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
                <li>
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
                <li class="active">
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

        <div class="main-content">
            <div class="header">
                <h1>GoFit WebApp</h1>
            </div>

            <div class="sub-header">
                <h2 class="plan-name">
                <span class="plan-name-text">Diet Plan Name</span>
                </h2>
                <button class="back-btn" onclick="window.location.href='diet_plan.php'">Back</button>
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

            <div class="day-footer">
                <button class="plan-btn accept-btn">Accept Plan</button>
            </div>
        </div>
    </div>
    <script src="../../javascript/sidebar.js"></script>
    <script src="../../javascript/user_dietplan_info.js"></script>
</body>

</html>