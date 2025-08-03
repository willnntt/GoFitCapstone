<?php
    include '../conn.php';

    $food_id = intval($_POST['food_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $calories = intval($_POST['calories']);
    $carbs = floatval($_POST['carbs']);
    $protein = floatval($_POST['protein']);
    $fats = floatval($_POST['fats']);

    $query = "UPDATE foods SET
        name = '$name',
        brand = '$brand',
        calories = '$calories',
        carbs = '$carbs',
        protein = '$protein',
        fats = '$fats'
        WHERE food_id = $food_id";

    if (mysqli_query($conn, $query)) {
        header("Location: admin_foods.php?update=success");
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>