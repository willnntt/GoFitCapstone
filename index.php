<?php
    session_start();

    // Clear any existing session
    session_unset();
    session_destroy();

    // Folder path
    $folder = "assets/slideshow/";

    // Get all image files from the folder
    $images = glob($folder . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

    // Ensure array is not empty
    if (!$images) {
        $images = [];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoFit | Home</title>
    <link rel="stylesheet" href="css/user.css">
</head>
<body>
    <div class="header">
        <h1>GoFit Web App</h1>
    </div>

    <div class="hero-section" id="hero"></div>

    <div class="footer-section">
        <button class="login-btn" onclick="window.location.href='php/login.php'">Login</button>
        <p class="signup-text">
            Don't have an account? 
            <a href="php/signup.php">Sign Up</a>
        </p>
    </div>

    <!-- IMAGE SLIDESHOW -->
    <script>
        // Get images from PHP
        const images = <?php echo json_encode($images); ?>;
        let currentIndex = 0;
        const hero = document.getElementById("hero");

        function changeImage() {
            if (images.length > 0) {
                hero.style.backgroundImage = `url('${images[currentIndex]}')`;
                currentIndex = (currentIndex + 1) % images.length;
            }
        }

        // Start slideshow
        changeImage();
        setInterval(changeImage, 5000); // change every 5 seconds
    </script>
</body>
</html>