<?php
    include '../conn.php';
    session_start();

    $action = $_POST['action'] ?? '';
    $user_id = $_SESSION['user_id'];
    $plan_id = intval($_POST['plan_id'] ?? 0);
    $start_date = $_POST['start_date'] ?? '';

    $response = ['success' => false];

    if ($action === 'accept') {
        $stmt = $conn->prepare("INSERT INTO user_diet_plans (user_id, plan_id, start_date) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $plan_id, $start_date);
        if ($stmt->execute()) {
            $response['success'] = true;
        }
    } elseif ($action === 'cancel') {
        $stmt = $conn->prepare("DELETE FROM user_diet_plans WHERE user_id = ? AND plan_id = ?");
        $stmt->bind_param("ii", $user_id, $plan_id);
        if ($stmt->execute()) {
            $response['success'] = true;
        }
    }

    echo json_encode($response);
?>