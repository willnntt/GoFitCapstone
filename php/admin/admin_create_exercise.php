<?php
    include '../conn.php';

    $id = intval($_POST['exercise_id']) ?? 0;
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    if ($id > 0) {
    // Update existing exercise
    $sql = "UPDATE exercises SET name='$name', category='$category', difficulty='$difficulty', description='$description' WHERE id=$id";
    } else {
    // Insert new exercise
    $sql = "INSERT INTO exercises (name, category, difficulty, description) VALUES ('$name', '$category', '$difficulty', '$description')";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: exercise_database.php?add=success");
    } else {
        echo "Insert failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>