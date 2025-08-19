<?php
require_once __DIR__ . '/../conn.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $log_id = $_GET['log_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM exercise_log WHERE log_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $log_id, $user_id);
        
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