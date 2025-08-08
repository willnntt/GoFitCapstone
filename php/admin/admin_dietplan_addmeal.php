<?php
    include '../conn.php';

    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);

    $planId = $data['plan_id'];
    $foodId = $data['food_id'];
    $mealType = $data['meal_type'];
    $amount = $data['amount'];
    $dayNumber = $data['day_number'];

    // Step 1: Get day_id based on plan_id and day_number
    $daySql = "SELECT day_id FROM diet_plan_days WHERE plan_id = ? AND day_number = ?";
    $dayStmt = $conn->prepare($daySql);
    $dayStmt->bind_param("ii", $planId, $dayNumber);
    $dayStmt->execute();
    $dayResult = $dayStmt->get_result();

    if ($dayResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Day not found for this plan']);
        exit;
    }

    $dayRow = $dayResult->fetch_assoc();
    $dayId = $dayRow['day_id'];

    // Step 2: Insert into diet_plan_meals
    $insertSql = "INSERT INTO diet_plan_meals (plan_id, day_id, meal_type, food_id, amount)
                VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("iisii", $planId, $dayId, $mealType, $foodId, $amount);

    if ($insertStmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $insertStmt->error]);
    }
?>