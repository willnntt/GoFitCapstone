<?php
    include 'php/conn.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: php/login.php");
    } else {
        header("Location: php/user/user_dashboard.php");
    }

    mysqli_close($conn);
?>