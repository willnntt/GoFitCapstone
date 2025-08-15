<?php
    session_start();
    include '../conn.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../index.php');
        exit;
    }  

    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];    

    // Fetch user data
    $query = "SELECT * FROM user_data WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $currentWeight = $user['weight'];
    $goalWeight = $user['goal_weight'];
    $bmi = $user['bmi'];
    $age = $user['age'];

    $bodyComposition = "";

    if ($age >= 18) {
        if ($bmi < 18.5) {
            $bodyComposition = "Underweight";
        } elseif ($bmi >= 18.5 && $bmi < 25) {
            $bodyComposition = "Healthy Weight";
        } elseif ($bmi >= 25 && $bmi < 30) {
            $bodyComposition = "Overweight";
        } else {
            $bodyComposition = "Obese";
        }
    }

    $exerciseQuery = "
        SELECT el.*, e.name 
        FROM exercise_log el
        JOIN exercises e ON el.exercise_id = e.exercise_id
        WHERE el.user_id = '$user_id'
        ORDER BY el.date DESC
        LIMIT 1
    ";
    $exerciseResult = mysqli_query($conn, $exerciseQuery);
    $exercise = mysqli_fetch_assoc($exerciseResult);

    $dietQuery = "SELECT * FROM user_diet_plans udp
                  JOIN diet_plans dp ON udp.plan_id = dp.plan_id
                  WHERE udp.user_id = '$user_id'
                  ORDER BY udp.start_date DESC LIMIT 1";
    $dietResult = mysqli_query($conn, $dietQuery);
    $diet = mysqli_fetch_assoc($dietResult);

    $sqlMax = "SELECT calorie_goal FROM user_data WHERE user_id = $user_id";
    $resultMax = mysqli_query($conn, $sqlMax);
    $maxCalories = 0;
    if ($row = mysqli_fetch_assoc($resultMax)) {
        $maxCalories = (int)$row['calorie_goal'];
    }

    // Calculate total calories eaten today
    $today = date('Y-m-d');
    $sqlCalories = "SELECT SUM(calories) AS total FROM meal_log 
                    WHERE user_id = $user_id 
                    AND DATE(date) = '$today'";
    $resultCalories = mysqli_query($conn, $sqlCalories);
    $totalCalories = 0;
    if ($row = mysqli_fetch_assoc($resultCalories)) {
        $totalCalories = (int)$row['total'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GoFit - Dashboard</title>
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="menu-toggle">
                <img src="../../assets/icons/hamburger.png" alt="Menu" class="hamburger-icon">
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

        <div class="main">
            <div class="header">
                <h1>GoFit WebApp</h1>
            </div>

            <!-- user -->
            <div class="user-info">
                <div class="user-text">
                    <div>Welcome, <strong><?php echo $username?>!</strong></div>
                </div>

                <a href="../../index.php">
                    <button class="back-btn">Log out</button>
                </a> 
            </div>

            <!-- progress -->
            <div class="progress-section">
                <div class="progress-header">
                    <h3>My Progress</h3>
                    
                    <hr>

                    <div class="progress-entry">
                        <div class="progress-item calorie-tracker">
                            <h4>Calorie Tracker</h4>
                            <div class="donut-chart" id="donut">
                                <span id="remaining">Remaining<br> 0 kcal </span>
                            </div>
                        </div>

                        <div class="progress-item latest-exercise">
                            <h4>Latest Exercise</h4>

                            <?php if ($exercise): ?>
                                <p><strong><?= htmlspecialchars($exercise['exercise_name']) ?></strong></p>
                                
                                <?php if ($exercise['type'] === 'Strength'): ?>
                                    <p>Sets: <?= intval($exercise['sets']) ?></p>
                                    <p>Reps: <?= intval($exercise['reps']) ?></p>
                                    <p>Weight: <?= htmlspecialchars($exercise['weight']) ?> kg</p>
                                <?php elseif ($exercise['type'] === 'Flexibility'): ?>
                                    <p>Sets: <?= intval($exercise['sets']) ?></p>
                                    <p>Reps: <?= intval($exercise['reps']) ?></p>
                                <?php elseif ($exercise['type'] === 'Aerobic'): ?>
                                    <p>Duration: <?= htmlspecialchars($exercise['duration']) ?> mins</p>
                                    <p>Distance: <?= htmlspecialchars($exercise['distance']) ?> km</p>
                                <?php endif; ?>
                                
                                <p>Date: <?= date('Y-m-d', strtotime($exercise['date'])) ?></p>
                            <?php else: ?>
                                <p>No exercises logged yet.</p>
                            <?php endif; ?>

                        </div>

                        <div class="progress-item current-diet">
                            <h4>Current Diet Plan</h4>

                            <?php if ($diet): ?>
                                <div class='diet-plan'>
                                        <img src='<?= '../../../' . htmlspecialchars($diet['image']) ?>' alt='Diet Plan Image' class='diet-img'>
                                        <span class='diet-name'><?= htmlspecialchars($diet['name']) ?></span>
                                    </div>
                            <?php else: ?>
                                    <p>No diet plan selected.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- health statistics -->
            <div class="health-section">
                <div class="health-header">
                    <div class="health-title">
                        <h3>My Health Statistics</h3>
                    </div>
                </div>

                <hr>

                <div class="health-content">
                    <ul class="health-stats">
                        <li>Current Weight: <?php echo $currentWeight; ?>kg</li>
                        <li>Goal Weight: <?php echo $goalWeight; ?>kg</li>
                        <li>Body Mass Index (BMI): <?php echo $bmi; ?></li>
                        <li>Body Composition: <?php echo $bodyComposition; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="../../javascript/sidebar.js"></script>
    <script src="../../javascript/dashboard.js"></script>
    <script>
        const totalCalories = <?= $totalCalories ?>; 
        const maxCalories = <?= $maxCalories ?>; 

        loadCalorieChart(totalCalories, maxCalories, 'donut', 'remaining');
    </script>
</body>
</html>
