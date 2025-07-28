<?php
    include '../conn.php';

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $calories = intval($_POST['calories']);
    $carbs = floatval($_POST['carbs']);
    $protein = floatval($_POST['protein']);
    $fats = floatval($_POST['fats']);

    $query = "INSERT INTO foods (name, brand, calories, carbs, protein, fats)
            VALUES ('$name', '$brand', '$calories', '$carbs', '$protein', '$fats')";

    if (mysqli_query($conn, $query)) {
        header("Location: admin_foods.php?add=success");
    } else {
        echo "Insert failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>