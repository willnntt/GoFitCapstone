<?php
    include '../conn.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $birthday = $_POST['birthday'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $height = (float)$_POST['height'];
        $weight = (float)$_POST['weight'];
        $goal_weight = (float)$_POST['goal_weight'];

        // Calculate BMI
        $bmi = ($height > 0) ? round($weight / (($height / 100) ** 2), 1) : 0;

        // Calculate Calorie Goal (simple formula)
        $bmr = 10 * $goal_weight + 6.25 * $height - 5 * 25; // age skipped
        $calorie_goal = round($bmr * 1.2);

        $stmt = mysqli_prepare($conn, "
            UPDATE user_data
            SET username=?, birthday=?, email=?, gender=?, height=?, weight=?, goal_weight=?, bmi=?, calorie_goal=?
            WHERE user_id=?
        ");
        mysqli_stmt_bind_param($stmt, "ssssdddddi",
            $username, $birthday, $email, $gender, $height, $weight, $goal_weight, $bmi, $calorie_goal, $user_id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: user_database.php?success=1");
            exit;
        } else {
            echo "Failed to update user.";
        }
    }
?>