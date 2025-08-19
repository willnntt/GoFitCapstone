<?php
include '../conn.php';
header('Content-Type: application/json');
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

try {
    $result = $conn->query("SELECT * FROM exercises");
    $exercises = [];
    
    while ($row = $result->fetch_assoc()) {
        $exercises[] = $row;
    }
    
    echo json_encode($exercises);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>