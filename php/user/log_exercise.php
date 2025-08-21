<?php
    include '../conn.php';
    header('Content-Type: application/json');
    session_start();

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user_id = $_SESSION['user_id'];
        $exercise_id = $_POST['exercise_id'];
        $date = $_POST['date'];
        $sets = $_POST['sets'] ?? 1;
        $reps = $_POST['reps'] ?? 0;
        $weight = $_POST['weight'] ?? 0;
        $distance = $_POST['distance'] ?? 0;
        $duration = $_POST['duration'] ?? '00:00:00';

        // Get exercise category to determine if it's aerobic
        $category_stmt = $conn->prepare("SELECT category FROM exercises WHERE exercise_id = ?");
        $category_stmt->bind_param("i", $exercise_id);
        $category_stmt->execute();
        $category_result = $category_stmt->get_result();
        $exercise_data = $category_result->fetch_assoc();
        $category = $exercise_data['category'] ?? '';
        
        try {
            // For aerobic exercises, use distance and duration instead of sets/reps/weight
            if (strtolower($category) === 'aerobic') {
                $stmt = $conn->prepare("
                    INSERT INTO exercise_log 
                    (user_id, exercise_id, date, sets, reps, weight, distance, duration) 
                    VALUES (?, ?, ?, 1, 0, 0, ?, ?)
                ");
                $stmt->bind_param(
                    "iisds", 
                    $user_id, 
                    $exercise_id, 
                    $date, 
                    $distance, 
                    $duration
                );
            } else {
                // For strength exercises, use sets/reps/weight
                $stmt = $conn->prepare("
                    INSERT INTO exercise_log 
                    (user_id, exercise_id, date, sets, reps, weight, distance, duration) 
                    VALUES (?, ?, ?, ?, ?, ?, 0, '00:00:00')
                ");
                $stmt->bind_param(
                    "iisiii", 
                    $user_id, 
                    $exercise_id, 
                    $date, 
                    $sets, 
                    $reps, 
                    $weight
                );
            }

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