<?php
    include '../conn.php';

    $user_id = $_GET['user_id'];
    mysqli_query($conn, "DELETE FROM user_data WHERE user_id = '$user_id'");
    header("Location: admin_users.php");

    mysqli_close($conn);
?>