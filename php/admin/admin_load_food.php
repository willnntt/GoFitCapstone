<?php
    include '../conn.php';

    header('Content-Type: application/json');

    $query = "SELECT * FROM foods";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to fetch foods.'
        ]);
        exit;
    }

    $foods = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $foods[] = [
            'food_id' => (int)$row['food_id'],
            'name' => $row['name'],
            'brand' => $row['brand'],
            'calories' => (float)$row['calories'],
            'portion_unit' => $row['portion_unit'],
            'carbs' => (float)$row['carbs'],
            'protein' => (float)$row['protein'],
            'fats' => (float)$row['fats']
        ];
    }

    echo json_encode([
        'success' => true,
        'foods' => $foods
    ]);

    mysqli_close($conn);
?>