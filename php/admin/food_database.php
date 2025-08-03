<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Database</title>
    <link rel="stylesheet" href="..//css/admin_database.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="menu-toggle" onclick="toggleSidebar()">
                <img src="../assets/hamburger.png" alt="Menu" class="hamburger-icon">
            </div>
            
            <ul>
                <li>
                    <a href="user_database.html">
                        <div class="nav-item">
                            <img src="../assets/user.png" alt="User Icon" class="icon">
                            <span>User Database</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="exercise_database.html">
                        <div class="nav-item">
                            <img src="../assets/exercise.png" alt="Exercise Icon" class="icon">
                            <span>Exercise Database</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="diet_database.html">
                        <div class="nav-item">
                            <img src="../assets/diet.png" alt="Diet Icon" class="icon">
                            <span>Diet Plan Database</span>
                        </div>
                    </a>
                </li>
                <li class="active">
                    <a href="food_database.html">
                        <div class="nav-item">
                            <img src="../assets/food.png" alt="Food Icon" class="icon">
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
                <h2>Food Database</h2>
                <button class="back-btn">Back</button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Food ID</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Calories (kcal)</th>
                            <th>Carbs (g)</th>
                            <th>Protein (g)</th>
                            <th>Fat (g)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'admin_load_food.php'; ?>
                    </tbody>
                </table>
            </div>

            <div class="add-button-container">
                <button class="add-btn">Add</button>
            </div>
        </div>
    </div>
    <script src="../javascript/navigation_bar.js"></script>
</body>
</html>