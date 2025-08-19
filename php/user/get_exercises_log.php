<?php
include '../conn.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$date = $_GET['date'] ?? date('Y-m-d');
$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        SELECT el.*, e.name AS exercise_name 
        FROM exercise_log el
        JOIN exercises e ON el.exercise_id = e.exercise_id
        WHERE el.user_id = ? AND el.date = ?
        ORDER BY el.log_id DESC
    ");
    $stmt->bind_param("is", $user_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
    
    echo json_encode($logs);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>