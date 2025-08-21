<?php
    session_start();
    include '../conn.php';

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit;
    }

    // Fetch exercises for dropdown
    $exercises = [];
    $result = $conn->query("SELECT * FROM exercises");
    if ($result) {
        $exercises = $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch user's exercise history
    $exercise_logs = [];
    $user_id = $_SESSION['user_id'];
    $result = $conn->query("
        SELECT el.*, e.name AS exercise_name 
        FROM exercise_log el
        JOIN exercises e ON el.exercise_id = e.exercise_id
        WHERE el.user_id = $user_id
        ORDER BY el.date DESC
    ");
    if ($result) {
        $exercise_logs = $result->fetch_all(MYSQLI_ASSOC);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoFit | Exercise Log</title>

    <link rel="stylesheet" href="../../css/exercise.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
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
            <li>
                <a href="calorie_tracker.php">
                    <div class="nav-item">
                        <img src="../../assets/icons/food.png" alt="Food Icon" class="icon">
                        <span>Calorie Tracker</span>
                    </div>
                </a>
            </li>
            <li class="active">
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
            <h1>Exercise Log</h1>
        </div>

        <div class="wrapper">
            <!-- Exercise Log Form -->
            <div class="exercise-card">
                <h2 class="website-title">Today's Workout</h2>

                <div class="day-header">
                    <button class="arrow-btn"><i class="fas fa-chevron-left"></i></button>
                    <h2>
                        <?= date('F j') ?>
                    </h2>
                    <button class="arrow-btn"><i class="fas fa-chevron-right"></i></button>
                </div>

                <div class="exercise-display">
                    <div class="empty-state">
                        No exercises added yet
                        <div class="exercise-picture">
                            <img src="" alt="Exercise illustration">
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <button id="startSessionBtn" class="interact-btn start-session">Start Session</button>
                <button id="addExerciseBtn" class="interact-btn add-exercise">Add Exercise</button>

                <div id="timerDisplay" style="display: none;">
                    <div class="timer-display">
                        <span id="timer">00:00:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exercise Popup -->
    <div id="popup">
        <h3>Add Exercise</h3>
        <div id="exerciseList" class="exercise-list"></div>
    </div>
    <div id="overlay"></div>

    <!-- JavaScript Files -->
    <script src="../../javascript/sidebar.js"></script>
    <script src="../../javascript/exercise.js"></script>
</body>

</html>