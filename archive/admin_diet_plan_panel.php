<?php
    include 'conn.php';

    // Get all available diet plans
    $planQuery = "SELECT plan_id, name FROM diet_plans ORDER BY plan_id ASC";
    $planResult = mysqli_query($conn, $planQuery);
    $plans = mysqli_fetch_all($planResult, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Diet Plan</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <a href="admin_diet_plans.php" class="back-button">← Back to Diet Plans</a>

    <h1>Select a Diet Plan</h1>
    <div id="diet-buttons">
        <?php foreach ($plans as $plan): ?>
            <button class="diet-button" data-id="<?= $plan['plan_id'] ?>">
                <?= htmlspecialchars($plan['name']) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <hr>

    <!-- This is where meals/days will be loaded -->
    <div id="diet-plan-container"></div>

    <script>
        const buttons = document.querySelectorAll('.diet-button');
        const container = document.getElementById('diet-plan-container');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                fetch('admin_load_diet_plan.php?plan_id=' + id)
                    .then(response => response.text())
                    .then(html => {
                        container.innerHTML = html;
                        window.scrollTo({ top: container.offsetTop, behavior: 'smooth' });
                    });
            });
        });
    </script>
</body>
</html>
