<?php
    include '../conn.php';

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);

    // Insert into diet_plans
    $query = "INSERT INTO diet_plans (name, description, image) 
              VALUES ('$name', '$description', '$image')";

    if (mysqli_query($conn, $query)) {
        $planId = mysqli_insert_id($conn);

        // Insert days 1 to 7
        $values = [];
        for ($i = 1; $i <= 7; $i++) {
            $values[] = "($planId, $i)";
        }
        $insertDaysQuery = "INSERT INTO diet_plan_days (plan_id, day_number) VALUES " . implode(',', $values);

        if (mysqli_query($conn, $insertDaysQuery)) {
            header("Location: admin_diet_plans.php?add=success");
        } else {
            echo "Diet plan created, but failed to insert days: " . mysqli_error($conn);
        }
    } else {
        echo "Insert failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>