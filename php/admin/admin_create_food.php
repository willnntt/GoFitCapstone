<?php
    include '../conn.php';

    // Sanitize and assign inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $calories = intval($_POST['calories']);
    $portion_unit = mysqli_real_escape_string($conn, $_POST['portion_unit']);
    $carbs = intval($_POST['carbs']);
    $protein = intval($_POST['protein']);
    $fats = intval($_POST['fats']);

    // Build query
    $query = "INSERT INTO foods (name, brand, calories, portion_unit, carbs, protein, fats)
            VALUES ('$name', '$brand', $calories, '$portion_unit', $carbs, $protein, $fats)";

    // Execute query
    if (mysqli_query($conn, $query)) {
        header("Location: food_database.php?add=success");
        exit();
    } else {
        echo "Insert failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>

