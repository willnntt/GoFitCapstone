<?php
    include '../conn.php';
    session_start();

    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $log_id = $_POST['log_id'] ?? null;
        $field = $_POST['field'] ?? null;
        $value = $_POST['value'] ?? null;
        $user_id = $_SESSION['user_id'];

        $allowed_fields = ['reps', 'weight', 'sets', 'distance', 'duration'];
        if (!in_array($field, $allowed_fields)) {
            echo json_encode(['success' => false, 'message' => 'Invalid field']);
            exit;
        }

        if ($field === 'duration') {
            if (!preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $value)) {
                echo json_encode(['success' => false, 'message' => 'Invalid duration format']);
                exit;
            }
        } elseif (!is_numeric($value)) {
            echo json_encode(['success' => false, 'message' => 'Invalid value']);
            exit;
        }

        try {
            $stmt = $conn->prepare("UPDATE exercise_log SET $field = ? WHERE log_id = ? AND user_id = ?");
            
            if ($field === 'duration') {
                $stmt->bind_param("sii", $value, $log_id, $user_id);
            } else {
                $stmt->bind_param("dii", $value, $log_id, $user_id);
            }

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => $stmt->error]);
            }
        } catch (Exception $e) {
            error_log("Update error: " . $e->getMessage()); // logs instead of echoing HTML
            echo json_encode(['success' => false, 'message' => 'Server error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}