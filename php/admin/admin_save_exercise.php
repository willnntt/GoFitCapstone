<?php
    include '../conn.php';

    // Ensure POST variables exist before using them
    $exercise_id = isset($_POST['exercise_id']) ? intval($_POST['exercise_id']) : 0;
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if ($exercise_id > 0) {
        // UPDATE existing exercise
        $sql = "UPDATE exercises 
                SET name = '$name', 
                    category = '$category', 
                    difficulty = '$difficulty', 
                    description = '$description'
                WHERE exercise_id = $exercise_id";
    } else {
        // INSERT new exercise
        $sql = "INSERT INTO exercises (name, category, difficulty, description) 
                VALUES ('$name', '$category', '$difficulty', '$description')";
    }

    if (mysqli_query($conn, $sql)) {
        if ($exercise_id > 0) {
            header("Location: exercise_database.php?update=success");
        } else {
            header("Location: exercise_database.php?add=success");
        }
        exit();
    } else {
        echo "Query failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>