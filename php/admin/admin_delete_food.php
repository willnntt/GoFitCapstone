<?php
    include '../conn.php';

    $food_id = intval($_GET['food_id'] ?? 0);

    if ($food_id > 0) {
        $ok1 = mysqli_query($conn, "DELETE FROM foods WHERE food_id = $food_id");
        $ok2 = mysqli_query($conn, "DELETE FROM diet_plan_meals WHERE food_id = $food_id");

        if ($ok1) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid food ID']);
    }

    mysqli_close($conn);
?>