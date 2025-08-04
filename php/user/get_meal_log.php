<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    include '../conn.php';

    $user_id = $_SESSION['user_id'] ?? 1;
    $date = date('Y-m-d');

    $sql = "
        SELECT 
            m.log_id AS id, 
            m.meal_type, 
            f.name, 
            f.brand,
            f.portion_unit, 
            f.calories, 
            f.carbs, 
            f.protein, 
            f.fats, 
            m.amount
        FROM meal_log m
        JOIN foods f ON m.food_id = f.food_id
        WHERE m.user_id = $user_id AND m.date = '$date'
        ORDER BY m.meal_type, m.log_id DESC
    ";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        exit;
    }

    $meals = [
        'breakfast' => [],
        'lunch' => [],
        'dinner' => [],
        'snacks' => []
    ];

    while ($row = mysqli_fetch_assoc($result)) {
        $meal = strtolower($row['meal_type']);
        if (!isset($meals[$meal])) {
            $meals[$meal] = [];
        }

        $meals[$meal][] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'brand' => $row['brand'],
            'amount' => (float)$row['amount'],
            'unit' => $row['portion_unit'],
            'calories' => (float)$row['calories'],
            'carbs' => (float)$row['carbs'],
            'protein' => (float)$row['protein'],
            'fats' => (float)$row['fats']
        ];
    }

    echo json_encode(['success' => true, 'data' => $meals]);
    mysqli_close($conn);
?>
