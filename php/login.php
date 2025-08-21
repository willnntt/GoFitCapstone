<?php
    session_start();
    include 'conn.php';

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email && $password) {
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT user_id, username, password FROM user_data WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($$stmt->num_rows == 1) {
                $stmt->bind_result($user_id, $db_username, $db_password, $role);
                $stmt->fetch();
                if (password_verify($password, $db_password)) {       
                // Regenerate session ID for security
                    session_regenerate_id(true); 
                    $_SESSION['user'] = $db_username;
                    $_SESSION['userid'] = $user_id;

                    if ($user['user_id'] == 1) { // Admin user
                        header('Location: admin/user_database.php');
                        exit;
                    } else {
                        header('Location: user/dashboard.php');
                        exit;
                    }
                } else {
                    $error = 'Invalid password';
                }
            } else {
                $error = 'User not found';
            }

            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            $error = 'Please fill in all fields';
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoFit | Login</title>
    <link rel="stylesheet" href="../css/user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1>GoFit Web App</h1>
    </div>

    <div class="wrapper">
        <div class="card">
            <h2 class="title">Login to your account</h2>
            <p class="subtitle">Enter your username, email, and password to log in</p>

            <?php if ($error): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            
            <form method="post" class="form">
                <input type="email" name="email" placeholder="email@domain.com" class="input" required>
                
                <div class="input-container">
                    <input type="password" id="password" class="input" name="password" placeholder="Password" required>
                    <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
                </div>

                <button type="submit" class="btn-primary">Login</button>
            </form>

            <div class="divider">or</div>

            <button class="btn-secondary" onclick="window.location.href='signup.php'">Sign Up</button>
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
