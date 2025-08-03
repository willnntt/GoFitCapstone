<?php
    include '../conn.php';

    $user_id = intval($_GET['user_id']);
    $result = mysqli_query($conn, "SELECT * FROM user_data WHERE user_id = $user_id");

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "User not found.";
        exit;
    }

    $user = mysqli_fetch_assoc($result);
?>

<!-- <h2>Edit User</h2>
<form method="POST" action="update_user.php">
    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

    Username: <input type="text" name="username" value="<?= $user['username'] ?>"><br>
    Email: <input type="email" name="email" value="<?= $user['email'] ?>"><br>
    Gender: 
    <select name="gender">
        <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
    </select><br>
    Weight (kg): <input type="number" step="0.1" name="weight" value="<?= $user['weight'] ?>"><br>
    Height (cm): <input type="number" step="0.1" name="height" value="<?= $user['height'] ?>"><br>
    Goal Weight (kg): <input type="number" step="0.1" name="goal_weight" value="<?= $user['goal_weight'] ?>"><br>
    Calorie Goal: <input type="number" name="calorie_goal" value="<?= $user['calorie_goal'] ?>"><br>
    Role:
    <select name="role">
        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
    </select><br>

    <button type="submit">Update User</button>
</form> -->

<!-- user forms to display html -->