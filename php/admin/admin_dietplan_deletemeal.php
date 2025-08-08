<?php
    include '../conn.php';
    header('Content-Type: application/json');

    if (isset($_POST['meal_id'])) {
        $mealId = intval($_POST['meal_id']);

        $query = "DELETE FROM diet_plan_meals WHERE meal_id = $mealId";
        if (mysqli_query($conn, $query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Invalid request']);
?>