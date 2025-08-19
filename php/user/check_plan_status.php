<?php
    session_start();
    include '../conn.php';

    $user_id = $_SESSION['user_id'] ?? 0;
    $plan_id = intval($_GET['plan_id'] ?? 0);

    $response = ['accepted' => false];

    if ($user_id && $plan_id) {
        $stmt = $conn->prepare("SELECT * FROM user_diet_plans WHERE user_id = ? AND plan_id = ? LIMIT 1");
        $stmt->bind_param("ii", $user_id, $plan_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $response['accepted'] = true;
        }
    }

    echo json_encode($response);
?>