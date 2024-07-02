<?php
session_start();
require 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'reset_password') {
        $username = $_POST['username'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("UPDATE teachers SET password = :password WHERE username = :username");
            if ($stmt->execute(['password' => $hashed_password, 'username' => $username])) {
                $message = "Password successfully updated. You can now login with your new password.";
            } else {
                $error = "Failed to update password. Please try again.";
            }
        } else {
            $error = "Passwords do not match. Please try again.";
        }
    } else {
        // Handle login
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $teacher = $stmt->fetch();

        if ($teacher && password_verify($password, $teacher['password'])) {
            $_SESSION['teacher_id'] = $teacher['id'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid credentials";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - tailwebs</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: center;
        }

        .login-title {
            color: #ff0000;
            margin-bottom: 30px;
            font-weight: 500;
            font-size: 24px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 10px 5px 10px 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .input-group i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .forgot-password {
            display: block;
            color: #2196F3;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color: #555;
        }

        .message, .error {
            color: #ff0000;
            margin-top: 15px;
            font-size: 14px;
        }

        #resetPasswordForm {
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">tailwebs.</h2>
        
        <form id="loginForm" method="post" action="">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <a href="#" class="forgot-password" id="forgotPasswordLink">Forgot Password?</a>
            <button type="submit" class="login-btn">Login</button>
        </form>

        <form id="resetPasswordForm" method="post" action="">
            <input type="hidden" name="action" value="reset_password">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-group">
                <input type="password" name="new_password" placeholder="New Password" required>
                <i class="fas fa-lock"></i>
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <i class="fas fa-lock"></i>
            </div>
            <button type="submit" class="login-btn">Reset Password</button>
            <a href="#" class="forgot-password" id="backToLoginLink">Back to Login</a>
        </form>

        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if (isset($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>

    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const loginForm = document.getElementById('loginForm');
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');
        const backToLoginLink = document.getElementById('backToLoginLink');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        forgotPasswordLink.addEventListener('click', function (e) {
            e.preventDefault();
            loginForm.style.display = 'none';
            resetPasswordForm.style.display = 'block';
        });

        backToLoginLink.addEventListener('click', function (e) {
            e.preventDefault();
            loginForm.style.display = 'block';
            resetPasswordForm.style.display = 'none';
        });
    </script>
</body>
</html>