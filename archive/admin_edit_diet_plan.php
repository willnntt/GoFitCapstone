<?php
    include 'conn.php';
    $plan_id = $_GET['id'];

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $duration = $_POST['duration'];

        $update = "UPDATE diet_plans SET name='$name', description='$description', duration=$duration WHERE id=$plan_id";
        mysqli_query($conn, $update);
        header("Location: admin_view_diet_plan.php?id=$plan_id");
        exit;
    }

    $plan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM diet_plans WHERE id = $plan_id"));
?>

<!DOCTYPE html>
<html>
<head><title>Edit Diet Plan</title></head>
<body>
    <h2>Edit Diet Plan</h2>
    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($plan['name']) ?>" required><br>

        <label>Description:</label><br>
        <textarea name="description" required><?= htmlspecialchars($plan['description']) ?></textarea><br>

        <label>Duration:</label><br>
        <input type="number" name="duration" value="<?= $plan['duration'] ?>" required><br><br>

        <button type="submit">Save Changes</button>
    </form>

    <br><a href="admin_view_diet_plan.php?id=<?= $plan_id ?>">Cancel</a>
</body>
</html>