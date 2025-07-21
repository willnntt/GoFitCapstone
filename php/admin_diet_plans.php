    <?php
    include 'conn.php';

    // Fetch food list from foods table
    $foodsQuery = "SELECT food_id, name, brand FROM foods";
    $foodsResult = mysqli_query($conn, $foodsQuery);
    $foods = mysqli_fetch_all($foodsResult, MYSQLI_ASSOC);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);

        // Insert diet plan
        $insertPlanQuery = "INSERT INTO diet_plans (name, description) VALUES ('$name', '$description')";
        mysqli_query($conn, $insertPlanQuery);
        $planId = mysqli_insert_id($conn);

        // Insert meals for fixed 7-day plan
        for ($day = 1; $day <= 7; $day++) {
            foreach (['breakfast', 'lunch', 'dinner', 'snack'] as $meal) {
                $mealKey = "day{$day}_{$meal}";
                if (!empty($_POST[$mealKey])) {
                    $foodId = intval($_POST[$mealKey]);
                    $insertMeal = "INSERT INTO diet_plan_meals (plan_id, day_number, meal_type, food_id)
                                VALUES ($planId, $day, '$meal', $foodId)";
                    mysqli_query($conn, $insertMeal);
                }
            }
        }

        header("Location: admin_diet_plans.php");
        exit;
    }

    // Fetch all diet plans
    $query = "SELECT * FROM diet_plans";
    $result = mysqli_query($conn, $query);
    $dietPlans = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Diet Plans</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container { width: 90%; margin: auto; padding: 20px; }
        form { margin-bottom: 30px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], textarea {
            width: 100%; padding: 8px; margin-top: 5px;
        }
        button { margin-top: 10px; padding: 10px 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .back-button { display: inline-block; margin-bottom: 15px; text-decoration: none; }
        .meal-day { margin-top: 20px; padding: 10px; background: #f9f9f9; border: 1px solid #ddd; }
    </style>
</head>
<body>
<div class="container">
    <a href="admin_dashboard.php" class="back-button">← Back to Dashboard</a>
    <h1>All Diet Plans</h1>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($dietPlans as $plan): ?>
            <tr>
                <td><?= $plan['plan_id'] ?></td>
                <td><?= htmlspecialchars($plan['name']) ?></td>
                <td><?= htmlspecialchars($plan['description']) ?></td>
                <td>
                    <a href="admin_view_diet_plan.php?id=<?= $plan['plan_id'] ?>">View</a> |
                    <a href="edit_diet_plan.php?id=<?= $plan['plan_id'] ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    
    <h2>Create New Diet Plan</h2>
    <form method="POST">
        <label for="name">Plan Name</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" rows="3" required></textarea>

        <h3>Meal Schedule (7 Days)</h3>
        <?php for ($day = 1; $day <= 7; $day++): ?>
            <div class="meal-day">
                <h4>Day <?= $day ?></h4>
                <?php foreach (['breakfast', 'lunch', 'dinner', 'snack'] as $meal): ?>
                    <label><?= ucfirst($meal) ?></label>
                    <select name="day<?= $day ?>_<?= $meal ?>" required>
                        <option value="">-- Select Food --</option>
                        <?php foreach ($foods as $food): ?>
                            <option value="<?= $food['food_id'] ?>">
                                <?= htmlspecialchars($food['name']) ?> (<?= htmlspecialchars($food['brand']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endforeach; ?>
            </div>
        <?php endfor; ?>

        <button type="submit">Create Plan</button>
    </form>
</div>
</body>
</html>
