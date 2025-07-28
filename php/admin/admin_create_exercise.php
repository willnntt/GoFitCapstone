<?php
    include '../conn.php';

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $query = "INSERT INTO exercises (name, difficulty, description, category)
            VALUES ('$name', '$difficulty', '$description', '$category')";

    if (mysqli_query($conn, $query)) {
        header("Location: admin_exercises.php?add=success");
    } else {
        echo "Insert failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>