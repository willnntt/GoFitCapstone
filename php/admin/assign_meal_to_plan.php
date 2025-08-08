<?php
    header('Content-Type: application/json');
    include '../conn.php';

    $data = json_decode(file_get_contents('php://input'), true);

    $plan_id = intval($data['plan_id']);
    $day_number = intval($data['day_number']);
    $meal_type = mysqli_real_escape_string($conn, $data['meal_type']);
    $food_id = intval($data['food_id']);
    $amount = intval($data['amount']);

    // Get the day_id for this plan/day
    $daySql = "SELECT day_id FROM diet_plan_days WHERE plan_id = $plan_id AND day_number = $day_number LIMIT 1";
    $dayResult = mysqli_query($conn, $daySql);

    if ($dayResult && mysqli_num_rows($dayResult) > 0) {
        $dayRow = mysqli_fetch_assoc($dayResult);
        $day_id = intval($dayRow['day_id']);

        $insertSql = "INSERT INTO diet_plan_meals (day_id, meal_type, food_id, amount)
                    VALUES ($day_id, '$meal_type', $food_id, $amount)";

        if (mysqli_query($conn, $insertSql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Day not found for this plan']);
    }

    mysqli_close($conn);
?>