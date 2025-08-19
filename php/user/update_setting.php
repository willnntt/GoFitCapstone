<?php
    include '../conn.php';
    session_start();

    $user_id = $_SESSION['user_id'];
    $field = $_POST['field'] ?? '';
    $value = $_POST['value'] ?? '';

    $allowed = ['username','email','password','dob','gender','height','weight','goal_weight','calorie_goal','activity_level','weight_change'];

    if (!in_array($field, $allowed)) {
        echo "Invalid field";
        exit();
    }

    // If updating password, hash it
    if ($field === 'password') {
        $value = password_hash($value, PASSWORD_DEFAULT);
    }

    // First: update the requested field
    if ($field === 'dob') {
        // Update dob and age
        $sql = "UPDATE user_data 
                SET dob = ?, 
                    age = TIMESTAMPDIFF(YEAR, ?, CURDATE()) 
                WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $value, $value, $user_id);
    } else {
        // Normal field update
        $sql = "UPDATE user_data SET $field = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $value, $user_id);
    }

    if (!$stmt) {
        echo "SQL Error: " . $conn->error;
        exit();
    }

    if (!$stmt->execute()) {
        echo "Update Error: " . $stmt->error;
        exit();
    }
    $stmt->close();

    // Second: If the changed field affects BMI/TDEE/Calorie Goal, recalc
    $recalcFields = ['dob','height','weight','goal_weight','weight_change','activity_level'];

    if (in_array($field, $recalcFields)) {
        $sql = "SELECT dob, gender, height, weight, goal_weight, weight_change, activity_level 
                FROM user_data WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            $dob = $user['dob'];
            $age = (new DateTime())->diff(new DateTime($dob))->y;
            $gender = $user['gender'];
            $height = $user['height'];
            $weight = $user['weight'];
            $activity = $user['activity_level'];
            $weightChange = (float)$user['weight_change'];

            // BMI
            $bmi = ($height > 0) ? $weight / pow($height/100, 2) : 0;

            // BMR (Mifflin-St Jeor)
            if ($gender === 'male') {
                $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
            } else {
                $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
            }

            // Activity multiplier
            $multipliers = [
                'sedentary' => 1.2,
                'light' => 1.375,
                'moderate' => 1.55,
                'active' => 1.725,
                'very_active' => 1.9
            ];
            $activityFactor = $multipliers[$activity] ?? 1.2;

            $tdee = $bmr * $activityFactor;

            // Calorie goal = TDEE ± adjustment
            $calorie_goal = $tdee + ($weightChange * -7700 / 7); 
            // 7700 kcal ≈ 1kg fat; negative = deficit, positive = surplus

            $sql = "UPDATE user_data 
                    SET age = ?, bmi = ?, tdee = ?, calorie_goal = ? 
                    WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("idddi", $age, $bmi, $tdee, $calorie_goal, $user_id);

            if ($stmt->execute()) {
                echo "Success (with recalculation)";
            } else {
                echo "Recalc Update Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    $conn->close();
?>