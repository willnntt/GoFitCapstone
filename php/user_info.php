<?php
    session_start();
    include 'conn.php';

    // Ensure signup data is available
    if (!isset($_SESSION['signup_email'], $_SESSION['signup_password'])) {
        die("Signup information missing. Please restart registration.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email          = $_SESSION['signup_email'];
        $hashedPassword = $_SESSION['signup_password'];

        $username       = trim($_POST['username']);
        $dob       = $_POST['dob'];
        $gender         = $_POST['gender'];
        $weight = floatval($_POST['weight']);
        $height         = floatval($_POST['height']);
        $goal_weight  = floatval($_POST['goal_weight']);
        $activity_level = $_POST['activity_level'];
        $weight_change  = floatval($_POST['weight_change']);

        // Calculate age
        $birthDate = new DateTime($dob);
        $today     = new DateTime();
        $register_date = $today->format('Y-m-d');
        $age = $today->diff($birthDate)->y;

        // Calculate BMI
        $bmi = $weight / pow($height / 100, 2);

        // Calculate BMR
        if ($gender === 'male') {
            $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
        } else {
            $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
        }

        $activityMultipliers = [
            'sedentary'   => 1.2,
            'light'       => 1.375,
            'moderate'    => 1.55,
            'active'      => 1.725,
            'very_active' => 1.9
        ];

        $tdee = $bmr * ($activityMultipliers[$activity_level] ?? 1.2);

        // Calorie adjustment for weight change (7700 kcal ≈ 1 kg)
        $calorieChange = ($weight_change * 7700) / 30; // daily change
        if ($goal_weight > $weight) {
            $calorie_goal = $tdee + $calorieChange;
        } else {
            $calorie_goal = $tdee - $calorieChange;
        }

        // Insert all data into user_data table
        $stmt = $conn->prepare("
            INSERT INTO user_data 
            (email, password, username, dob, gender, weight, height, goal_weight, activity_level, weight_change, age, bmi, bmr, tdee, calorie_goal, register_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssdddsdidddis", 
            $email, 
            $hashedPassword, 
            $username, 
            $dob, 
            $gender, 
            $weight, 
            $height, 
            $goal_weight, 
            $activity_level, 
            $weight_change, 
            $age, 
            $bmi, 
            $bmr, 
            $tdee, 
            $calorie_goal, 
            $register_date
        );

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['email']   = $email;

            // Clear signup temp data
            unset($_SESSION['signup_email'], $_SESSION['signup_password']);

            header('Location: login.php');
            exit;
        } else {
            echo "Error saving data: " . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>GoFit | User Info</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../css/user.css">
</head>
    <body>
        <div class="header">
            <h1>GoFit Web App</h1>
        </div>

        <div class="wrapper">
            <div class="card">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <h2 style="flex: 1; text-align: center; margin: 0;">Complete Your Profile</h2>
                    <div style="width: 60px;"></div> <!-- Spacer to balance layout -->
                    <a href="signup.php" style="text-decoration: none; position: flex-end;">
                        <button class="btn-primary">Back</button>
                    </a>
                </div>
                <p class="subtitle">Enter your details to personalize your plan.</p>

                <form method="POST" class="form">
                    <input type="text" name="username" class="input" placeholder="Username" required>

                    <div class="input-container">
                        <input type="date" name="dob" class="input" required>
                    </div>

                    <div class="input-container">
                        <select name="gender" class="input" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="input-container">
                        <input type="number" name="weight" step="0.1" class="input" placeholder="Current Weight (kg)" required>
                    </div>

                    <div class="input-container">
                        <input type="number" name="height" step="0.1" class="input" placeholder="Height (cm)" required>
                    </div>

                    <div class="input-container">
                        <input type="number" name="goal_weight" step="0.1" class="input" placeholder="Target Weight (kg)" required>
                    </div>

                    <div class="input-container">
                        <select name="activity_level" class="input" required>
                            <option value="">Activity Level</option>
                            <option value="sedentary">Sedentary (little or no exercise)</option>
                            <option value="light">Lightly active (1-3 days/week)</option>
                            <option value="moderate">Moderately active (3-5 days/week)</option>
                            <option value="active">Active (6-7 days/week)</option>
                            <option value="very_active">Very active (twice per day)</option>
                        </select>
                    </div>

                    <div class="input-container">
                        <select name="weight_change" class="input" required>
                            <option value="">Weight Change per Month</option>
                            <option value="0.25">0.25 kg</option>
                            <option value="0.5">0.5 kg</option>
                            <option value="0.75">0.75 kg</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary">Save Info</button>
                </form>
            </div>
        </div>
    </body>
</html>