<?php
    include '../conn.php';

    $food_id = $_GET['food_id'];

    //Delete food item from main foods table
    mysqli_query($conn, "DELETE FROM foods WHERE food_id = '$food_id'");
    
    //Delete associated food items from diet plans
    mysqli_query($conn, "DELETE FROM diet_plan_meals WHERE food_id = '$food_id'");
    
    header("Location: food_database.php");

    mysqli_close($conn);
?>