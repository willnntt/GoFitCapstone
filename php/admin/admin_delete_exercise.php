<?php
    include '../conn.php';

    $exercise_id = $_GET['exercise_id'];
    mysqli_query($conn, "DELETE FROM exercises WHERE exercise_id = '$exercise_id'");
    header("Location: admin_exercise.php");

    mysqli_close($conn);
?>