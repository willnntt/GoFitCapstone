<?php
    include '../conn.php';

    $planId = intval($_GET['plan_id'] ?? 0);
    if ($planId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid plan ID']);
        exit;
    }

    // Get Day 1 ID
    $daySql = "SELECT day_id FROM diet_plan_days WHERE plan_id = $planId AND day_number = 1 LIMIT 1";
    $dayResult = mysqli_query($conn, $daySql);

    if (!$dayResult) {
        echo json_encode(['success' => false, 'message' => 'Error querying day: ' . mysqli_error($conn)]);
        exit;
    }

    $dayRow = mysqli_fetch_assoc($dayResult);

    // Fallback if Day 1 does not exist, create days 1 to 7
    if (!$dayRow) {
        $values = [];
        for ($i = 1; $i <= 7; $i++) {
            $values[] = "($planId, $i)";
        }
        $insertSql = "INSERT INTO diet_plan_days (plan_id, day_number) VALUES " . implode(',', $values);

        if (!mysqli_query($conn, $insertSql)) {
            echo json_encode(['success' => false, 'message' => 'Failed to insert 7 days: ' . mysqli_error($conn)]);
            exit;
        }

        // Try fetching again after insert
        $dayResult = mysqli_query($conn, $daySql);
        if (!$dayResult || !($dayRow = mysqli_fetch_assoc($dayResult))) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch Day 1 after creation.']);
            exit;
        }
    }

    $dayId = $dayRow['day_id'];

    // Fetch meals for Day 1
    $mealSql = "SELECT * FROM diet_plan_meals WHERE day_id = $dayId ORDER BY meal_type";
    $mealResult = mysqli_query($conn, $mealSql);

    $meals = [];

    while ($row = mysqli_fetch_assoc($mealResult)) {
        $type = strtolower($row['meal_type']); // breakfast, lunch, etc.
        if (!isset($meals[$type])) $meals[$type] = [];

        $meals[$type][] = [
            'food' => $row['food_name'],
            'portion' => $row['portion_size'],
            'serving' => $row['serving_size'],
            'calories' => $row['calories'],
            'carbs' => $row['carbs'],
            'protein' => $row['protein'],
            'fats' => $row['fats']
        ];
    }

    echo json_encode(['success' => true, 'data' => $meals]);
    mysqli_close($conn);
?>