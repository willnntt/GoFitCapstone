<?php
    include '../conn.php';

    $query = "SELECT * FROM exercises";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<tr><td colspan='6'>Failed to fetch exercises.</td></tr>";
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['exercise_id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['category']}</td>";
        echo "<td>{$row['difficulty']}</td>";
        echo "<td>{$row['description']}</td>";
        echo "<td>
            <a href='edit_exercise.php?id={$row['exercise_id']}'>Edit</a> |
            <a href='delete_exercise.php?id={$row['exercise_id']}' onclick='return confirm(\"Delete this exercise?\")'>Delete</a>
        </td>";
        echo "</tr>";
    }
?>