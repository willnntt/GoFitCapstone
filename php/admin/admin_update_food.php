<?php
    include '../conn.php';

    $food_id = intval($_POST['food_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $calories = intval($_POST['calories']);
    $carbs = intval($_POST['carbs']);
    $protein = intval($_POST['protein']);
    $fats = intval($_POST['fats']);

    $query = "UPDATE foods SET
        name = '$name',
        brand = '$brand',
        calories = '$calories',
        carbs = '$carbs',
        protein = '$protein',
        fats = '$fats'
        WHERE food_id = $food_id";

    if (mysqli_query($conn, $query)) {
        header("Location: food_database.php?update=success");
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>