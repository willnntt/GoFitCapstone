<?php
    header('Content-Type: application/json');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include '../conn.php';

    $sql = "SELECT * FROM diet_plans ORDER BY plan_id DESC";
    $result = mysqli_query($conn, $sql);

    $plans = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $plans[] = [
                'plan_id' => $row['plan_id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'image' => $row['image']
            ];
        }
        echo json_encode(['success' => true, 'data' => $plans]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }

    mysqli_close($conn);
?>
