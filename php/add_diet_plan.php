<?php
    include 'conn.php';

    session_start();

    // Check if the user is logged in and is an admin
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_role']) == 'admin') {
        header("Location: login.php");
        exit();
    }

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $diet_name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $duration_days = mysqli_real_escape_string($conn, $_POST['duration_days']);

        $query = "INSERT INTO diet_plans (name, description, duration_days) VALUES ('$diet_name', '$description', '$duration_days')";
        
        if (mysqli_query($conn, $query)) {
            echo "Diet plan added successfully.";
            header("Location: admin_view_diet_plan.php");
        } else {
            echo "Error: " . mysqli_error($conn);
            header("Location: admin_view_diet_plan.php?error=1");
        }
    }


    mysqli_close($conn);
?>