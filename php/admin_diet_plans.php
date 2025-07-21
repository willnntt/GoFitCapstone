<?php
    include 'conn.php';

    // Handle form submission to create new plan
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['description'], $_POST['duration_days'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $duration = intval($_POST['duration_days']);

        $insertQuery = "INSERT INTO diet_plans (name, description, duration_days)
                        VALUES ('$name', '$description', $duration)";
        mysqli_query($conn, $insertQuery);
        header("Location: admin_diet_plans.php"); // Refresh page to update list
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
        input[type="text"], textarea, input[type="number"] {
        width: 100%; padding: 8px; margin-top: 5px;
        }
        button { margin-top: 10px; padding: 10px 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .back-button { display: inline-block; margin-bottom: 15px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_dashboard.php" class="back-button">← Back to Dashboard</a>
        <h1>Create New Diet Plan</h1>
        <form method="POST" action="">
        <label for="name">Plan Name</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" rows="3" required></textarea>

        <label for="duration_days">Duration (Days)</label>
        <input type="number" name="duration_days" id="duration_days" min="1" required>

        <button type="submit">Create Plan</button>
        </form>

        <h2>All Diet Plans</h2>
        <table>
        <thead>
            <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Duration (Days)</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dietPlans as $plan): ?>
            <tr>
                <td><?= $plan['id'] ?></td>
                <td><?= htmlspecialchars($plan['name']) ?></td>
                <td><?= htmlspecialchars($plan['description']) ?></td>
                <td><?= $plan['duration_days'] ?></td>
                <td>
                <a href="admin_view_diet_plan.php?id=<?= $plan['id'] ?>">View</a> |
                <a href="edit_diet_plan.php?id=<?= $plan['id'] ?>">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</body>
</html>