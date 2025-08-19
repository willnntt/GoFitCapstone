<?php
include '../conn.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $log_id = $_POST['log_id'];
    $field = $_POST['field'];
    $value = $_POST['value'];
    $user_id = $_SESSION['user_id'];

    // Validate field to prevent SQL injection
    $allowed_fields = ['reps', 'weight', 'sets'];
    if (!in_array($field, $allowed_fields)) {
        echo json_encode(['success' => false, 'message' => 'Invalid field']);
        exit;
    }

    // Validate value is numeric
    if (!is_numeric($value)) {
        echo json_encode(['success' => false, 'message' => 'Invalid value']);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE exercise_log SET $field = ? WHERE log_id = ? AND user_id = ?");
        $stmt->bind_param("dii", $value, $log_id, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>