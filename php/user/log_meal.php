<?php
    session_start();
    include_once '../../conn.php';
    header('Content-Type: application/json');

    // For testing fallback
    $user_id = $_SESSION['user_id'] ?? 1;

    // Parse JSON from request
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (!$data || !isset($data['food_id'], $data['amount'], $data['meal_type'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    $food_id = (int)$data['food_id'];
    $amount = (int)$data['amount'];
    $meal_type = mysqli_real_escape_string($conn, $data['meal_type']);
    $date = date('Y-m-d');

    // Fetch food data from DB
    $food_query = "SELECT calories, carbs, protein, fats FROM foods WHERE food_id = $food_id LIMIT 1";
    $result = mysqli_query($conn, $food_query);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo json_encode(['success' => false, 'message' => 'Food not found']);
        exit;
    }

    $food = mysqli_fetch_assoc($result);

    // Calculate total nutrition
    $total_calories = $food['calories'] * $amount;
    $total_carbs    = $food['carbs'] * $amount;
    $total_protein  = $food['protein'] * $amount;
    $total_fats     = $food['fats'] * $amount;

    // Insert into meal_log
    $insert = "INSERT INTO meal_log 
        (user_id, food_id, amount, meal_type, date, calories, carbs, protein, fats)
        VALUES 
        ($user_id, $food_id, $amount, '$meal_type', '$date', 
        $total_calories, $total_carbs, $total_protein, $total_fats)";

    if (mysqli_query($conn, $insert)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }

    mysqli_close($conn);
?>
