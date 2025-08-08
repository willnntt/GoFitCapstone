<?php
    header('Content-Type: application/json');
    include '../conn.php';

    $exercise_id = intval($_GET['exercise_id'] ?? 0);

    if ($exercise_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid exercise ID']);
        exit;
    }

    // Run your delete query (add error checking)
    if (mysqli_query($conn, "DELETE FROM exercises WHERE exercise_id = $exercise_id")) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }

    mysqli_close($conn);
    exit; 
?>