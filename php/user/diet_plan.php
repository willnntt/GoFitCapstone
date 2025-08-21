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
    <title>GoFit | Diet Plan</title>
    <link rel="stylesheet" href="../../css/dietplan.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
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
                <h1>Diet Plans</h1>
            </div>

            <div class="sub-header">
                <h1>Select a Diet Plan</h1>
                <a href="../../index.php">
                    <button class="back-btn">Log out</button>
                </a>    
            </div>
        
            <section class="top-section">
                <div class="scroll-container">
                    <!-- <div class="plan-card">
                        <img src="" alt="Plan 1">
                    </div> -->
                </div>

            </section>

            <section class="bottom-section">
                <h2>Selected Plan:</h2>
                    <div class="focused-card">
                        <h3>Pick a Diet Plan to View</h3>
                        <p>What the plan is about and the benefits</p>
                        <a href="#" class="view-plan">View Plan</a>
                    </div>
            </section>
        </div>
    </div>

    <script src="../../javascript/sidebar.js"></script>
    <script src="../../javascript/user_diet_plan.js"></script>
</body>
</html>
