<?php
    include '../conn.php';

    // Validate POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {

        $user_id = (int) $_POST['user_id'];
        $username = trim($_POST['username']);
        $dob = $_POST['dob'];
        $email = trim($_POST['email']);
        $gender = $_POST['gender'];
        $height = (float) $_POST['height'];
        $weight = (float) $_POST['weight'];
        $goal_weight = (float) $_POST['goal_weight'];
        $activity_level = $_POST['activity_level'] ?? 'sedentary';
        $weight_change = (float) $_POST['weight_change'];

        // Calculate derived fields
        $age = date_diff(date_create($dob), date_create('today'))->y;

        // BMI = weight / (height in m)^2
        $bmi = ($height > 0) ? ($weight / pow($height / 100, 2)) : 0;

        // BMR (Mifflin-St Jeor)
        if (strtolower($gender) === 'male') {
            $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
        } else {
            $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
        }

        // TDEE multipliers
        $activity_multipliers = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9
        ];
        $multiplier = $activity_multipliers[strtolower($activity_level)] ?? 1.2;
        $tdee = $bmr * $multiplier;

        // Calorie goal (TDEE + weight change * 7700 / 7 days)
        $calorie_goal = $tdee + (($weight_change * 7700) / 7);

        // Update DB
        $stmt = $conn->prepare("
            UPDATE user_data 
            SET username = ?, dob = ?, email = ?, gender = ?, height = ?, weight = ?, 
                goal_weight = ?, activity_level = ?, weight_change = ?, age = ?, bmi = ?, 
                bmr = ?, tdee = ?, calorie_goal = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param(
            "sssssddsdiddddi",
            $username, $dob, $email, $gender, $height, $weight,
            $goal_weight, $activity_level, $weight_change, $age, $bmi,
            $bmr, $tdee, $calorie_goal, $user_id
        );

        if ($stmt->execute()) {
            header("Location: user_database.php?update=success");
            exit();
        } else {
            echo "Error updating user: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
?>