<?php
    include '../conn.php';

    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../index.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM user_data WHERE user_id = $user_id";
    $result = $conn->query($sql);

    $user = $result->fetch_assoc();

    $activityLabels = [
        'sedentary' => 'Sedentary (little or no exercise)',
        'light' => 'Lightly active (1-3 days/week)',
        'moderate' => 'Moderately active (3-5 days/week)',
        'active' => 'Active (6-7 days/week)',
        'very_active' => 'Very active (twice per day)'
    ];

    $activityDisplay = isset($activityLabels[$user['activity_level']]) 
        ? $activityLabels[$user['activity_level']] 
        : 'Not specified';

    $weightChangeLabels = [
        '0.25'=> '0.25 kg/week',
        '0.5'=> '0.5 kg/week',
        '0.75'=> '0.75 kg/week',
        '1'=> '1 kg/week'
    ];

    $weightChangeDisplay = isset($weightChangeLabels[$user['weight_change']]) 
        ? $weightChangeLabels[$user['weight_change']] 
        : 'Not specified';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link rel="stylesheet" href="../../css/setting.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
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
                    <a href="user_dashboard.php">
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
                <li>
                    <a href="diet_plan.php">
                        <div class="nav-item">
                            <img src="../../assets/icons/diet.png" alt="Diet Icon" class="icon">
                            <span>Diet Plan</span>
                        </div>
                    </a>
                </li>
                <li class="active">
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
                <h1>Settings</h1>
            </div>

            <div class="sub-header">
                <a href="../../index.php">
                    <button class="back-btn">Log out</button>
                </a>    
            </div>

            <div class="settings-card">
                <h3>Account Info</h3>
                <p>Username: <?php echo $user['username']; ?> 
                    <button type="button" class="edit-button" data-field="username" data-old="<?php echo $user['username']; ?>">Change</button>
                </p>
                <p>Email Address: <?php echo $user['email']; ?> 
                    <button type="button" class="edit-button" data-field="email" data-old="<?php echo $user['email']; ?>">Change</button>
                </p>

                <h3>Login Method</h3>
                <p>Password: <?php echo str_repeat('*', 8); ?> 
                    <button type="button" class="edit-button" data-field="password">Change</button>
                </p>

                <h3>Personal</h3>
                <p>Date of Birth: <?php echo $user['dob']; ?>
                    <button type="button" class="edit-button" data-field="dob" data-old="<?php echo $user['dob']; ?>">Change</button>
                </p>
                <p>Biological Gender: <?php echo $user['gender']; ?>
                    <button type="button" class="edit-button" data-field="gender" data-old="<?php echo $user['gender']; ?>">Change</button>
                </p>
                <p>Height: <?php echo $user['height']; ?> cm 
                    <button type="button" class="edit-button" data-field="height" data-old="<?php echo $user['height']; ?>">Change</button>
                </p>
                <p>Weight: <?php echo $user['weight']; ?> kg
                    <button type="button" class="edit-button" data-field="weight" data-old="<?php echo $user['weight']; ?>">Change</button>
                </p>
                <p>Weight Change per Week: <?php echo htmlspecialchars($weightChangeDisplay); ?>
                    <button type="button" class="edit-button" data-field="weight_change" data-old="<?php echo $user['weight_change']; ?>">Change</button>
                </p>
                <p>Activity Level: <?php echo htmlspecialchars($activityDisplay); ?>
                    <button type="button" class="edit-button" data-field="activity_level" data-old="<?php echo $user['activity_level']; ?>">Change</button>
                </p>
            </div>
        </div>
    </div>

    <div id="changeModal" class="modal">
        <div class="modal-content">
            <h3>Changing Info</h3>
            <p id="oldInfo" class="old-info"></p>

            <!-- Default text input -->
            <div class="input-wrapper" id="inputWrapper">
                <input type="text" id="newInfo" placeholder="Input New Info">
            </div>

            <div class="modal-buttons">
                <button id="saveBtn" class="save-btn">Save</button>
                <button id="cancelBtn" class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <script src="../../javascript/sidebar.js"></script>
    <script src="../../javascript/setting.js"></script>
</body>
</html>
