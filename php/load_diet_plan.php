<?php
    include 'conn.php';

    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    $query = "SELECT * FROM diet_plans";
    $result = mysqli_query($conn, $query);

    $diet_plans = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $diet_plans[] = $row;
    }

    echo json_encode($diet_plans);

    // Close the database connection
    mysqli_close($conn);
?>  