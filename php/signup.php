<?php
session_start();
include 'conn.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if ($email && $password && $confirmPassword) {
        if ($password !== $confirmPassword) {
            $error = 'Passwords do not match';
        } else {
            // Check if email exists
            $stmt = $conn->prepare("SELECT user_id FROM user_data WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = 'Email is already registered';
            } else {
                $_SESSION['signup_email'] = $_POST['email'];
                $_SESSION['signup_password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                header('Location: user_info.php');
            }
            $stmt->close();
        }
    } else {
        $error = 'Please fill in all fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoFit | Sign Up</title>
    <link rel="stylesheet" href="../css/user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1>GoFit Web App</h1>
    </div>

    <div class="wrapper">
        <div class="card">
            <h2 class="title">Create a new GoFit account</h2>
            <p class="subtitle">Enter your email and password to create an account.</p>

            <?php if ($error): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form method="post" class="form">
                <input type="email" name="email" placeholder="email@domain.com" class="input" required>

                <div class="input-container">
                    <input type="password" id="password" class="input" name="password" placeholder="Password" required>
                    <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
                </div>

                <div class="input-container">
                    <input type="password" id="confirm_password" class="input" name="confirm_password" placeholder="Confirm Password" required>
                    <i class="fa-solid fa-eye toggle-password" data-target="confirm_password"></i>
                </div>

                <button type="submit" class="btn-primary">Signup</button>
            </form>

            <div class="divider">or</div>

            <button class="btn-secondary" onclick="window.location.href='login.php'">Login</button>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', () => {
                const input = document.getElementById(icon.getAttribute('data-target'));
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = "password";
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    </script>
</body>
</html>
