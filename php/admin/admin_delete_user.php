<?php
    header('Content-Type: application/json');
    include '../conn.php';

    if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid user ID'
        ]);
        exit;
    }

    $user_id = (int) $_GET['user_id'];


    $query = mysqli_query($conn, "DELETE FROM user_data WHERE user_id = $user_id");

    if ($query) {
        echo json_encode([
            'success' => true
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete user: ' . mysqli_error($conn)
        ]);
    }
?>