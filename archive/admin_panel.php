<?php
    // Start the session
    session_start();

    include 'conn.php';

    // // Check if the user is logged in and is an admin
    // if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    //     header('Location: /login.php');
    //     exit();
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GoFit Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="container">
        <h1>GoFit Admin Panel</h1>

        <div class="card user-card">
            <h2><i class="fas fa-users-cog"></i> Manage User Accounts</h2>
            <div class="buttons">
                <button onclick="window.location.href='admin_view_user.php'"><i class="fas fa-list"></i> View</button>
                <!-- <button onclick="window.location.href='admin_edit_user.php'"><i class="fas fa-edit"></i> Edit</button>
                <button class="danger" onclick="window.location.href='admin_delete_user.php'"><i class="fas fa-user-slash"></i> Deactivate</button> -->
            </div>
        </div>

        <div class="card diet-card">
            <h2><i class="fas fa-utensils"></i> Create Diet Plans</h2>
            <div class="buttons">
                <button onclick="window.location.href='admin_diet_plan_panel.php'"><i class="fas fa-list"></i> View</button>
                <!-- <button onclick="window.location.href='admin_create_diet_plan.php'"><i class="fas fa-plus"></i> Create</button> -->
            </div>
        </div>

        <div class="card food-card">
            <h2><i class="fas fa-apple-alt"></i> Create Food Record</h2>
            <div class="buttons">
                <button onclick="window.location.href='admin_view_food.php'"><i class="fas fa-list"></i> View</button>
                <!-- <button onclick="window.location.href='admin_create_food.php'"><i class="fas fa-plus"></i> Add</button> -->
            </div>
        </div>

        <div class="card exercise-card">
            <h2><i class="fas fa-dumbbell"></i> Create Exercise Record</h2>
            <div class="buttons">
                <button onclick="window.location.href='admin_view_exercise.php'"><i class="fas fa-list"></i> View</button>
                <!-- <button onclick="window.location.href='admin_add_exercise.php'"><i class="fas fa-plus"></i> Add</button> -->
            </div>
        </div>

    </div>

</body>
</html>
