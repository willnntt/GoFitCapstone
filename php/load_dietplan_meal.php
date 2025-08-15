<?php
    header('Content-Type: application/json');
    error_reporting(0);

    include 'conn.php';

    $plan_id = intval($_GET['plan_id']);
    $day_number = isset($_GET['day_number']) ? intval($_GET['day_number']) : 1;

    // Get name + description from diet_plans
    $descSql = "SELECT name, description, image FROM diet_plans WHERE plan_id = $plan_id";
    $descResult = mysqli_query($conn, $descSql);

    $planName = "";
    $description = "";
    $image = "";

    if ($descResult && mysqli_num_rows($descResult) > 0) {
        $row = mysqli_fetch_assoc($descResult);
        $planName = $row['name'];
        $description = $row['description'];

        // Clean image path
        $imagePath = $row['image'];
        $imagePath = preg_replace('#^(\.\./)+#', '/', $imagePath); // remove ../../ and replace with /

        $image = $imagePath;
    }

    // Get meals for the correct day
    $sql = "SELECT 
                m.meal_id,
                f.name AS food_name,
                f.brand AS brand,
                f.portion_unit,
                f.calories,
                f.carbs,
                f.protein,
                f.fats,
                m.meal_type,
                m.amount
            FROM diet_plan_days d
            JOIN diet_plan_meals m ON d.day_id = m.day_id
            JOIN foods f ON m.food_id = f.food_id
            WHERE d.plan_id = $plan_id AND d.day_number = $day_number";

    $result = mysqli_query($conn, $sql);

    $meals = [
        'breakfast' => [],
        'lunch' => [],
        'dinner' => [],
        'snacks' => []
    ];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $mealType = strtolower($row['meal_type']);
            $portionUnit = $row['portion_unit'] ?: '1 Serving';
            $amount = intval($row['amount']);
            $meals[$mealType][] = [
                'id' => $row['meal_id'],
                'food' => $row['food_name'],
                'brand' => $row['brand'] ?? 'Unknown',
                'portion' => $amount,
                'serving' => $portionUnit,
                'calories' => $row['calories'] * $amount,
                'carbs' => $row['carbs'] * $amount,
                'protein' => $row['protein'] * $amount,
                'fats' => $row['fats'] * $amount
            ];
        }

        echo json_encode([
            'success' => true,
            'name' => $planName,
            'description' => $description,
            'image' => $image,
            'data' => $meals
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => mysqli_error($conn)
        ]);
    }

    mysqli_close($conn);
?>