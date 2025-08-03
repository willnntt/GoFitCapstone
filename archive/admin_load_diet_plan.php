<?php
    include 'conn.php';

    $planId = isset($_GET['plan_id']) ? (int)$_GET['plan_id'] : 0;

    if ($planId <= 0) {
        echo "<p>Invalid Diet Plan ID.</p>";
        exit;
    }

    // Get plan details
    $planQuery = $conn->prepare("SELECT * FROM diet_plans WHERE plan_id = ?");
    $planQuery->bind_param("i", $planId);
    $planQuery->execute();
    $planResult = $planQuery->get_result();
    $plan = $planResult->fetch_assoc();

    if (!$plan) {
        echo "<p>Diet Plan not found.</p>";
        exit;
    }

    // Get plan days
    $daysQuery = $conn->prepare("SELECT * FROM diet_plan_days WHERE plan_id = ? ORDER BY day_number ASC");
    $daysQuery->bind_param("i", $planId);
    $daysQuery->execute();
    $daysResult = $daysQuery->get_result();
    $days = $daysResult->fetch_all(MYSQLI_ASSOC);

    // Load meals for each day
    $mealsByDay = [];
    foreach ($days as $day) {
        $dayId = $day['day_id'];
        $mealsQuery = $conn->prepare("
            SELECT m.*, f.name AS food_name, f.calories 
            FROM diet_plan_meals m
            JOIN foods f ON m.food_id = f.id
            WHERE m.day_id = ?
        ");
        $mealsQuery->bind_param("i", $dayId);
        $mealsQuery->execute();
        $mealsResult = $mealsQuery->get_result();
        $mealsByDay[$dayId] = $mealsResult->fetch_all(MYSQLI_ASSOC);
    }
?>

<h2><?= htmlspecialchars($plan['name']) ?> (7 Days)</h2>
<p><?= htmlspecialchars($plan['description']) ?></p>

<?php foreach ($days as $day): ?>
    <h3>Day <?= $day['day_number'] ?> - <?= htmlspecialchars($day['day_name'] ?? '') ?></h3>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Meal Type</th>
                <th>Food</th>
                <th>Calories</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($mealsByDay[$day['day_id']])): ?>
            <?php foreach ($mealsByDay[$day['day_id']] as $meal): ?>
                <tr>
                    <td><?= htmlspecialchars($meal['meal_type']) ?></td>
                    <td><?= htmlspecialchars($meal['food_name']) ?></td>
                    <td><?= (int)$meal['calories'] ?> kcal</td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">No meals found for this day.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <br>
<?php endforeach; ?>
