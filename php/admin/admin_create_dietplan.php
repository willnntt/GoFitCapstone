<?php
    include '../conn.php';

    // Escape basic text inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle image upload
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../../assets/images/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = "/Webapp/assets/images/" . $fileName;
            }
        }
    }

    // Insert into diet_plans
    $query = "INSERT INTO diet_plans (name, description, image) 
            VALUES ('$name', '$description', " . ($imagePath ? "'$imagePath'" : "NULL") . ")";

    if (mysqli_query($conn, $query)) {
        $planId = mysqli_insert_id($conn);

        // Insert days 1 to 7
        $values = [];
        for ($i = 1; $i <= 7; $i++) {
            $values[] = "($planId, $i)";
        }
        $insertDaysQuery = "INSERT INTO diet_plan_days (plan_id, day_number) VALUES " . implode(',', $values);

        if (mysqli_query($conn, $insertDaysQuery)) {
            header("Location: dietinfo_database.php?plan_id=$planId&created=1");
            exit;
        } else {
            echo "Diet plan created, but failed to insert days: " . mysqli_error($conn);
        }
    } else {
        echo "Insert failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>