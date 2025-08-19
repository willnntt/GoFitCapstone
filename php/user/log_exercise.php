<?php
include '../conn.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $exercise_id = $_POST['exercise_id'];
    $date = $_POST['date'];
    $sets = $_POST['sets'] ?? 1;
    $reps = $_POST['reps'] ?? 0;
    $weight = $_POST['weight'] ?? 0;
    $distance = 0;
    $duration = '00:00:00';

    try {
        $stmt = $conn->prepare("
            INSERT INTO exercise_log 
            (user_id, exercise_id, date, sets, reps, weight, distance, duration) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iisiiids", 
            $user_id, 
            $exercise_id, 
            $date, 
            $sets, 
            $reps, 
            $weight, 
            $distance, 
            $duration
        );
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'log_id' => $stmt->insert_id]);
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