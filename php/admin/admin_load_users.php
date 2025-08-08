<?php
    include '../conn.php';

    $query = "SELECT * FROM user_data";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<tr><td colspan='12'>Failed to fetch data.</td></tr>";
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['user_id']}</td>";
        echo "<td>{$row['username']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['gender']}</td>";
        echo "<td>{$row['birthday']}</td>";
        echo "<td>{$row['weight']}</td>";
        echo "<td>{$row['height']}</td>";
        echo "<td>{$row['bmi']}</td>";
        echo "<td>{$row['goal_weight']}</td>";
        echo "<td>{$row['calorie_goal']}</td>";
        echo "<td>{$row['register_date']}</td>";
        echo "<td>
            <a href='admin_edit_user.php?id={$row['user_id']}'>Edit</a> |
            <a href='admin_delete_user.php?id={$row['user_id']}' onclick='return confirm(\"Delete this user?\")'>Delete</a>
        </td>";
        echo "</tr>";
    }
?>