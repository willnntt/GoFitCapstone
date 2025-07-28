<?php
    include '../conn.php';

    $user_id = intval($_POST['user_id']);

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $weight = floatval($_POST['weight']);
    $height = floatval($_POST['height']);
    $goal_weight = floatval($_POST['goal_weight']);
    $calorie_goal = intval($_POST['calorie_goal']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Calculate BMI with KG and CM
    $bmi = 0;
    if ($height > 0) {
        $bmi = $weight / pow(($height / 100), 2);
    }

    $query = "UPDATE user_data SET
        username = '$username',
        email = '$email',
        gender = '$gender',
        weight = '$weight',
        height = '$height',
        bmi = '$bmi',
        goal_weight = '$goal_weight',
        calorie_goal = '$calorie_goal',
        role = '$role'
        WHERE user_id = $user_id";

    if (mysqli_query($conn, $query)) {
        header("Location: admin_users.php?update=success");
        exit;
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>
