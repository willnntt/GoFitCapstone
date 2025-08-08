<?php
    include '../conn.php';

    $user_id = intval($_POST['user_id']);

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $weight = floatval($_POST['weight']);
    $height = floatval($_POST['height']);
    $goal_weight = floatval($_POST['goal_weight']);
    $birthday_raw = $_POST['birthday']; // expecting format YYYY-MM-DD
    $activity_level = isset($_POST['activity_level']) ? $_POST['activity_level'] : 'light';

    // Calculate Age from Birthday
    $birthday = new DateTime($birthday_raw);
    $today = new DateTime();
    $age = $today->diff($birthday)->y;

    // Calculate BMI
    $bmi = 0;
    if ($height > 0) {
        $bmi = $weight / pow(($height / 100), 2);
    }

    function calculate_calorie_goal($weight, $goal_weight, $height, $age, $gender, $activity_level = 'light', $duration_days = 60) {
        if ($gender === 'Male') {
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
        } else {
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
        }

        $activity_factors = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9
        ];

        $multiplier = $activity_factors[$activity_level] ?? 1.375;
        $tdee = $bmr * $multiplier;

        $weight_diff = $goal_weight - $weight;
        $total_calorie_change = $weight_diff * 7700;
        $daily_calorie_offset = $total_calorie_change / $duration_days;

        $calorie_goal = round($tdee + $daily_calorie_offset);
        return max($calorie_goal, 1200); // prevent too low calorie goals
    }

    $calorie_goal = calculate_calorie_goal($weight, $goal_weight, $height, $age, $gender, $activity_level);

    $query = "UPDATE user_data SET
        username = '$username',
        email = '$email',
        gender = '$gender',
        weight = '$weight',
        height = '$height',
        bmi = '$bmi',
        goal_weight = '$goal_weight',
        birthday = '$birthday_raw',
        calorie_goal = '$calorie_goal'
        WHERE user_id = $user_id";

    if (mysqli_query($conn, $query)) {
        header("Location: user_database.php?update=success");
        exit;
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>