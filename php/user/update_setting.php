<?php
    include '../conn.php';

    $user_id = 1; // Example user
    $field = $_POST['field'] ?? '';
    $value = $_POST['value'] ?? '';

    $allowed = ['username','email','password','age','gender','height','weight','calorie_goal'];
    if (!in_array($field, $allowed)) {
        echo "Invalid field";
        exit();
    }

    $sql = "UPDATE user_data SET $field = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "SQL Error: " . $conn->error;
        exit();
    }

    $stmt->bind_param("si", $value, $user_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Update Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
?>