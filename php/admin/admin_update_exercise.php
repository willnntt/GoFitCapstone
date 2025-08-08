<?php
    include '../conn.php';

    $exercise_id = intval($_POST['exercise_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $query = "UPDATE exercises SET
        name = '$name',
        difficulty = '$difficulty',
        description = '$description',
        category = '$category'
        WHERE exercise_id = $exercise_id";

    if (mysqli_query($conn, $query)) {
        header("Location: exercise_database.php?update=success");
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>