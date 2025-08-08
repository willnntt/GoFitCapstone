<?php
    include '../conn.php';

    $query = "SELECT * FROM exercises";
    $result = mysqli_query($conn, $query);

    $exercises = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $exercises[] = $row;
        }
        echo json_encode([
            'success' => true,
            'data' => $exercises
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to fetch exercises.'
        ]);
    }

    mysqli_close($conn);
?>