<?php
    include '../conn.php';
    $exercise_id = intval($_GET['exercise_id']);

    $result = mysqli_query($conn, "SELECT * FROM exercises WHERE exercise_id = $exercise_id");
    if (!$result || mysqli_num_rows($result) == 0) {
        echo "Exercise not found.";
        exit;
    }

    $exercise = mysqli_fetch_assoc($result);
?>

<!-- <h2>Edit Exercise</h2>
<form method="POST" action="update_exercise.php">
    <input type="hidden" name="exercise_id" value="<?= $exercise['exercise_id'] ?>">

    <label>Exercise Name:</label><br>
    <input type="text" name="name" value="<?= $exercise['name'] ?>" required><br><br>

    <label>Difficulty:</label><br>
    <select name="difficulty" required>
        <option value="Beginner" <?= $exercise['difficulty'] == 'Beginner' ? 'selected' : '' ?>>Beginner</option>
        <option value="Intermediate" <?= $exercise['difficulty'] == 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
        <option value="Advanced" <?= $exercise['difficulty'] == 'Advanced' ? 'selected' : '' ?>>Advanced</option>
    </select><br><br>

    <label>Description:</label><br>
    <textarea name="description" required><?= $exercise['description'] ?></textarea><br><br>

    <label>Category:</label><br>
    <select name="category" required>
        <option value="Cardio" <?= $exercise['category'] == 'Cardio' ? 'selected' : '' ?>>Cardio</option>
        <option value="Strength" <?= $exercise['category'] == 'Strength' ? 'selected' : '' ?>>Strength</option>
        <option value="Flexibility" <?= $exercise['category'] == 'Flexibility' ? 'selected' : '' ?>>Flexibility</option>
        <option value="Balance" <?= $exercise['category'] == 'Balance' ? 'selected' : '' ?>>Balance</option>
    </select><br><br>

    <button type="submit">Update Exercise</button>
</form> -->
