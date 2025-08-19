<?php
    include '../conn.php';

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $planId = intval($_POST['plan_id'] ?? 0); // if 0 → create, else update

    // Handle image upload
    $imagePath = null;
    if ($planId > 0) {
        $result = mysqli_query($conn, "SELECT image FROM diet_plans WHERE plan_id = $planId");
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $imagePath = $row['image']; // fallback to old
        }
    }

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
                // Delete old image if exists
                if (!empty($imagePath)) {
                    $oldFile = $_SERVER['DOCUMENT_ROOT'] . "/webapp/assets/images/" . basename($imagePath);
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                // Save only the filename into DB
                $imagePath = $fileName;
            }
        }
    }

    if ($planId > 0) {
        $query = "UPDATE diet_plans 
                SET name = '$name', description = '$description'";

        if ($imagePath) { // only update image if a new one is uploaded
            $query .= ", image = '$imagePath'";
        }

        $query .= " WHERE plan_id = $planId";

        if (mysqli_query($conn, $query)) {
            header("Location: dietinfo_database.php?plan_id=$planId&updated=1");
            exit;
        } else {
            echo "Update failed: " . mysqli_error($conn);
        }
    }
    
    else {
        $query = "INSERT INTO diet_plans (name, description, image) 
                VALUES ('$name', '$description', " . ($imagePath ? "'$imagePath'" : "NULL") . ")";

        if (mysqli_query($conn, $query)) {
            $planId = mysqli_insert_id($conn);

            // Insert default days 1–7
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
    }

    mysqli_close($conn);
?>