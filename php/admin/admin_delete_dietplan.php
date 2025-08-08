<?php
    include '../conn.php';

    $plan_id = intval($_GET['plan_id'] ?? 0);

    if ($plan_id > 0) {
        $res = mysqli_query($conn, "SELECT image FROM diet_plans WHERE plan_id = $plan_id LIMIT 1");
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $imagePath = $row['image'];

            // 2. Delete the image file if it exists
            if ($imagePath) {
                $serverPath = realpath(__DIR__ . "/.." . $imagePath);

                if ($serverPath && file_exists($serverPath)) {
                    unlink($serverPath);
                }
            }
        }

        // Delete meals linked to this plan via diet_plan_days
        mysqli_query(
            $conn,
            "DELETE FROM diet_plan_meals 
            WHERE day_id IN (
                SELECT day_id FROM diet_plan_days WHERE plan_id = $plan_id
            )"
        );

        // Delete days linked to this plan
        mysqli_query($conn, "DELETE FROM diet_plan_days WHERE plan_id = $plan_id");

        // Delete the main diet plan
        mysqli_query($conn, "DELETE FROM diet_plans WHERE plan_id = $plan_id");
    }

    header("Location: diet_database.php");
    mysqli_close($conn);
?>