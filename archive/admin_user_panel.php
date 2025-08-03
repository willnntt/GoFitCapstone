<?php
    session_start();

    include 'conn.php';

    // // Check if the user is logged in and is an admin
    // if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    //     header('Location: /login.php');
    //     exit();
    // }

    
?>