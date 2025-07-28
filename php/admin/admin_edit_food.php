<?php
    include '../conn.php';
    $food_id = intval($_GET['food_id']);

    $result = mysqli_query($conn, "SELECT * FROM foods WHERE food_id = $food_id");
    if (!$result || mysqli_num_rows($result) == 0) {
        echo "Food not found.";
        exit;
    }

    $food = mysqli_fetch_assoc($result);
?>

<!-- <h2>Edit Food</h2>
<form method="POST" action="update_food.php">
    <input type="hidden" name="food_id" value="<?= $food['food_id'] ?>">

    <label>Food Name:</label><br>
    <input type="text" name="name" value="<?= $food['name'] ?>" required><br><br>

    <label>Brand:</label><br>
    <input type="text" name="brand" value="<?= $food['brand'] ?>"><br><br>

    <label>Calories (kcal):</label><br>
    <input type="number" name="calories" value="<?= $food['calories'] ?>" required><br><br>

    <label>Carbs (g):</label><br>
    <input type="number" step="0.1" name="carbs" value="<?= $food['carbs'] ?>" required><br><br>

    <label>Protein (g):</label><br>
    <input type="number" step="0.1" name="protein" value="<?= $food['protein'] ?>" required><br><br>

    <label>Fats (g):</label><br>
    <input type="number" step="0.1" name="fats" value="<?= $food['fats'] ?>" required><br><br>

    <button type="submit">Update Food</button>
</form> -->