<?php
    include '../conn.php';

    $query = "SELECT * FROM foods";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<tr><td colspan='7'>Failed to fetch foods.</td></tr>";
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['food_id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['brand']}</td>";
        echo "<td>{$row['calories']}</td>";
        echo "<td>{$row['carbs']}</td>";
        echo "<td>{$row['protein']}</td>";
        echo "<td>{$row['fats']}</td>";
        echo "<td>
            <a href='edit_food.php?id={$row['food_id']}'>Edit</a> |
            <a href='delete_food.php?id={$row['food_id']}' onclick='return confirm(\"Delete this food item?\")'>Delete</a>
        </td>";
        echo "</tr>";
    }
?>
