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
    <title>Sign Up | GoFit</title>

    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <div class="signup-box">
        <div class="signup-text">
            Welcome to GoFit
            Create an account 
            <br>
            <br>
            <div class="signup-email">
            Enter your email to sign up 
            <br>
            <input type="text" id="text"  placeholder="Name@gmail.com">
            </div>
            <br>
            <div class="signup-password">
            Enter your Password 
            <br>
            <input type="text" id="text"  placeholder="Password">
            </div>
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