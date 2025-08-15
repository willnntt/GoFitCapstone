<?php
    include '../conn.php';

    session_start();

    $user_id = $_SESSION['user_id'];
    $field = $_POST['field'] ?? '';
    $value = $_POST['value'] ?? '';

    $allowed = ['username','email','password','age','gender','height','weight','calorie_goal','activity_level','weight_change'];

    if (!in_array($field, $allowed)) {
        echo "Invalid field";
        exit();
    }

    // If updating password, hash it
    if ($field === 'password') {
        $value = password_hash($value, PASSWORD_DEFAULT);
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