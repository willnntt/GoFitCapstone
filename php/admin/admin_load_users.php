<?php
    include '../conn.php';

    header('Content-Type: application/json');

    $query = "SELECT * FROM user_data WHERE user_id > 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to fetch users: ' . mysqli_error($conn)
        ]);
        exit;
    }

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => $users
    ]);
?>
