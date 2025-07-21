<?php
    include 'conn.php';
    
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }       

    // Fetch the username from the database
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT name FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $username = htmlspecialchars($row['username']);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | GoFit</title>

    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="Login">
        <div class="Login-text">
            Login 
            <br>
            <br>
            Enter your email
            <br>
            <br>
            <input type="text" id="text"  placeholder="Name@gmail.com">
            <br>
            <br>
            Enter your Password
            <br>
            <br>
            <input type="text" id="text"  placeholder="Password">
            <br>
            <br>
            <button>Continue</button>
            <br>
            or
            <br>
            <button>Login</button>
        </div>

    </div>
</body>
</html>