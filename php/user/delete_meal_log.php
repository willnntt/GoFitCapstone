<?php
    include '../conn.php';
    session_start();

    $user_id = $_SESSION['user_id'] ?? 1;

    // Delete a specific log
    if (isset($_GET['log_id'])) {
        $logId = intval($_GET['log_id']);

        $query = "DELETE FROM meal_log WHERE log_id = $logId";
        if (mysqli_query($conn, $query)) {
            header("Location: calorie_tracker.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting log: " . mysqli_error($conn);
        }

    // Delete all logs for today
    } elseif (isset($_GET['all']) && $_GET['all'] === 'true') {
        $today = date('Y-m-d');
        $query = "DELETE FROM meal_log WHERE user_id = $user_id AND date = '$today'";
        
        if (mysqli_query($conn, $query)) {
            header("Location: calorie_tracker.php?msg=all_deleted");
            exit();
        } else {
            echo "Error deleting all logs: " . mysqli_error($conn);
        }

    } else {
        echo "Invalid request.";
    }
?>
