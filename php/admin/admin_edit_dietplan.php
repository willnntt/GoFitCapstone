<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include '../conn.php';

    $plan_id = intval($_POST['plan_id'] ?? 0);
    if ($plan_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid plan ID']);
        exit;
    }

    $updates = [];
    $response = ['success' => false];

    if (!empty($_FILES['diet_image']['name'])) {
        $targetDir = "../../assets/images/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['diet_image']['name']);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
            exit;
        }

        if (move_uploaded_file($_FILES['diet_image']['tmp_name'], $targetFile)) {
            $relativePath = "/Webapp/assets/images/" . $fileName;
            $updates[] = "image = '" . mysqli_real_escape_string($conn, $relativePath) . "'";
            $response['new_image_url'] = $relativePath;
        } else {
            echo json_encode(['success' => false, 'message' => 'Image upload failed']);
            exit;
        }
    }


    if (!empty($_POST['name'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $updates[] = "name = '$name'";
    }

    if (!empty($_POST['description'])) {
        $desc = mysqli_real_escape_string($conn, $_POST['description']);
        $updates[] = "description = '$desc'";
    }


    if (!empty($updates)) {
        $query = "UPDATE diet_plans SET " . implode(", ", $updates) . " WHERE plan_id = $plan_id";
        if (mysqli_query($conn, $query)) {
            $response['success'] = true;
            $response['message'] = 'Diet plan updated successfully';
        } else {
            $response['message'] = 'Database update failed: ' . mysqli_error($conn);
        }
    } else {
        $response['message'] = 'No update data provided';
    }

    header("Location: dietinfo_database.php?plan_id=$plan_id");
    mysqli_close($conn);
?>