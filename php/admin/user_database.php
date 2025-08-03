<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Database</title>
    <link rel="stylesheet" href="../../css/admin_database.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="menu-toggle" onclick="toggleSidebar()">
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

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>GoFit Database Management</h1>
            </div>

            <div class="sub-header">
                <h2>User Database</h2>
                <button class="back-btn">Back</button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Height (cm)</th>
                            <th>Weight (kg)</th>
                            <th>BMI</th>
                            <th>Role</th>
                            <th>Register Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'admin_load_users.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="../../javascript/navigation_bar.js"></script>
</body>
</html>