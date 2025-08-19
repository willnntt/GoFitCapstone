<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

// Handle exercise logging
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $exercise_id = $_POST['exercise_id'];
    $sets = $_POST['sets'];
    $reps = $_POST['reps'];
    $weight = $_POST['weight'];
    $date = date('Y-m-d');
    
    $stmt = $conn->prepare("INSERT INTO exercise_log (user_id, exercise_id, date, sets, reps, weight) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisiii", $_SESSION['user_id'], $exercise_id, $date, $sets, $reps, $weight);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Exercise logged successfully!";
    } else {
        $error = "Error logging exercise: " . $conn->error;
    }
}

// Fetch available exercises
$exercises = [];
$result = $conn->query("SELECT * FROM exercises");
if ($result) {
    $exercises = $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch exercise history
$history = [];
$result = $conn->query("
    SELECT el.*, e.name as exercise_name 
    FROM exercise_log el
    JOIN exercises e ON el.exercise_id = e.exercise_id
    WHERE el.user_id = {$_SESSION['user_id']}
    ORDER BY el.date DESC
    LIMIT 5
");
if ($result) {
    $history = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Log | GoFit</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/exercise.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
    /* Additional custom styles */
    .empty-state {
        text-align: center;
        padding: 40px 0;
        color: #888;
    }
    
    .exercise-picture {
        width: 200px;
        height: 200px;
        margin: 20px auto;
        background-color: #f5f5f5;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .exercise-picture img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    </style>
</head>
<body>
    <!-- Your HTML content here -->
    
    <!-- JavaScript Files -->
    <script src="../javascript/sidebar.js"></script>
    <script src="../javascript/exercise.js"></script>
    
    <script>
    // Additional inline JavaScript if needed
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Exercise page loaded");
        
        // Initialize any page-specific JS here
        const dateInput = document.querySelector('input[type="date"]');
        if (dateInput) {
            dateInput.valueAsDate = new Date();
        }
    });
    </script>
</body>
</html>