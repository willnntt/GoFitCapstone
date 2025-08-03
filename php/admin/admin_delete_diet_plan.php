<?php
    include '../conn.php';

    $plan_id = $_GET['plan_id'];

    //Delete diet plan from main diet plans table
    mysqli_query($conn, "DELETE FROM diet_plans WHERE plan_id = '$plan_id'");

    //Delete associated diet plan items
    mysqli_query($conn, "DELETE FROM diet_plan_days WHERE plan_id = '$plan_id'");
    mysqli_query($conn, "DELETE FROM diet_plan_meals WHERE plan_id = '$plan_id'");

    header("Location: admin_diet_plans.php");

    mysqli_close($conn);
?>