<?php
    include '../conn.php';

    $user_id = 1;

    $sql = "SELECT username, email, password, age, gender, height, weight, calorie_goal 
            FROM user_data WHERE user_id = $user_id";
    $result = $conn->query($sql);

    $user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link rel="stylesheet" href="../../css/setting.css">
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
                            <span>Setting</span>
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

            <div class="settings-card">
                <h3>Account Info</h3>
                <p>Display Name: <?php echo $user['username']; ?> 
                    <button type="button" class="edit-button" data-field="username" data-old="<?php echo $user['username']; ?>">Change</button>
                </p>
                <p>Email Address: <?php echo $user['email']; ?> 
                    <button type="button" class="edit-button" data-field="email" data-old="<?php echo $user['email']; ?>">Change</button>
                </p>

                <h3>Login Method</h3>
                <p>Password: <?php echo $user['password']; ?> 
                    <button type="button" class="edit-button" data-field="password" data-old="<?php echo $user['password']; ?>">Change</button>
                </p>

                <h3>Personal</h3>
                <p>Date of Birth: <?php echo $user['age']; ?>
                    <button type="button" class="edit-button" data-field="age" data-old="<?php echo $user['age']; ?>">Change</button>
                </p>
                <p>Gender: <?php echo $user['gender']; ?>
                    <button type="button" class="edit-button" data-field="gender" data-old="<?php echo $user['gender']; ?>">Change</button>
                </p>
                <p>Height: <?php echo $user['height']; ?> CM 
                    <button type="button" class="edit-button" data-field="height" data-old="<?php echo $user['height']; ?>">Change</button>
                </p>
                <p>Weight: <?php echo $user['weight']; ?> KG 
                    <button type="button" class="edit-button" data-field="weight" data-old="<?php echo $user['weight']; ?>">Change</button>
                </p>
                <p>Calories per Day: <?php echo $user['calorie_goal']; ?>
                    <button type="button" class="edit-button" data-field="calorie_goal" data-old="<?php echo $user['calorie_goal']; ?>">Change</button>
                </p>
            </div>
        </div>
    </div>

    <div id="changeModal" class="modal">
        <div class="modal-content">
            <h3>Changing Info</h3>
            <p id="oldInfo" class="old-info"></p>

            <!-- Default text input -->
            <div id="inputWrapper">
                <input type="text" id="newInfo" placeholder="Input New Info">
            </div>

            <div class="modal-buttons">
                <button id="saveBtn" class="save-btn">Save</button>
                <button class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <script src="../../javascript/sidebar.js"></script>
    <script src="../../javascript/setting.js"></script>
</body>
</html>
