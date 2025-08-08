<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Database</title>
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/admin_database.css">
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
                <h2>Exercise Database</h2>
            </div>

            <div class="table-container">
                <table id="exerciseTable">
                    <thead>
                        <tr>
                            <th>Exercise ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Difficulty</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>

            <div class="add-button-container">
                <button class="add-btn">Add</button>
            </div>
        </div>
    </div>
    <script src="../../javascript/sidebar.js"></script>
    <script src="../../javascript/admin_exercise_records.js"></script>
</body>
</html>