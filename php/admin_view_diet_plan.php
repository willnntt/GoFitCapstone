<?php
    include 'conn.php';

    $planId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Get plan details
    $planQuery = "SELECT * FROM diet_plans WHERE id = $planId";
    $planResult = mysqli_query($conn, $planQuery);
    $plan = mysqli_fetch_assoc($planResult);

    // Get plan days
    $daysQuery = "SELECT * FROM diet_plan_days WHERE diet_plan_id = $planId ORDER BY day_number ASC";
    $daysResult = mysqli_query($conn, $daysQuery);
    $days = mysqli_fetch_all($daysResult, MYSQLI_ASSOC);

    // Load meals for each day
    $mealsByDay = [];
    foreach ($days as $day) {
        $dayId = $day['id'];
        $mealsQuery = "SELECT m.*, f.name AS food_name, f.calories 
                    FROM diet_plan_meals m
                    JOIN foods f ON m.food_id = f.id
                    WHERE m.day_id = $dayId";
        $mealsResult = mysqli_query($conn, $mealsQuery);
        $mealsByDay[$dayId] = mysqli_fetch_all($mealsResult, MYSQLI_ASSOC);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Diet Plan</title>
    <link rel="stylesheet" href="styles.css">
</head>
    <body>
        <a href="admin_diet_plans.php" class="back-button">← Back to Diet Plans</a>

        <h1><?= htmlspecialchars($plan['title']) ?> (<?= $plan['duration_days'] ?> Days)</h1>
        <p><?= htmlspecialchars($plan['description']) ?></p>

        <?php foreach ($days as $day): ?>
            <h2>Day <?= $day['day_number'] ?></h2>
            <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Meal Type</th>
                    <th>Food</th>
                    <th>Calories</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mealsByDay[$day['id']] as $meal): ?>
                    <tr>
                        <td><?= htmlspecialchars($meal['meal_type']) ?></td>
                        <td><?= htmlspecialchars($meal['food_name']) ?></td>
                        <td><?= $meal['calories'] ?> kcal</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        <?php endforeach; ?>
    </body>
</html>