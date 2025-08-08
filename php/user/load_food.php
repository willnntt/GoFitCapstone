<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    include '../conn.php';

    $query = "SELECT * FROM foods";
    $result = mysqli_query($conn, $query);

    $foods = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $foods[] = [
            "food_id" => $row['food_id'],
            "name" => $row['name'],
            "brand" => $row['brand'],
            "calories" => (int)$row['calories'],
            "portion_unit" => $row['portion_unit'],
            "carbs" => (int)$row['carbs'],
            "protein" => (int)$row['protein'],
            "fats" => (int)$row['fats']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($foods);
?>
