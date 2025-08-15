<?php
    include '../conn.php';

    // Sanitize and assign inputs
    $food_id = isset($_POST['food_id']) ? intval($_POST['food_id']) : 0;
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $calories = intval($_POST['calories']);
    $portion_unit = isset($_POST['portion_unit']) ? mysqli_real_escape_string($conn, $_POST['portion_unit']) : '';
    $carbs = floatval($_POST['carbs']);
    $protein = floatval($_POST['protein']);
    $fats = floatval($_POST['fats']);

    // Check if this is an update or create
    if ($food_id > 0) {
        // UPDATE
        $query = "UPDATE foods SET
            name = '$name',
            brand = '$brand',
            calories = $calories,
            carbs = $carbs,
            protein = $protein,
            fats = $fats
            WHERE food_id = $food_id";

        $redirect = "food_database.php?update=success";
    } else {
        // CREATE
        $query = "INSERT INTO foods (name, brand, calories, portion_unit, carbs, protein, fats)
                VALUES ('$name', '$brand', $calories, '$portion_unit', $carbs, $protein, $fats)";

        $redirect = "food_database.php?add=success";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: $redirect");
        exit();
    } else {
        echo "Query failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>