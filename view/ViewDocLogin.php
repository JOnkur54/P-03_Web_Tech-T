<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'doctor') {
    header("Location: ViewDocDashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-header">
            <div class="logo">Medi<span>Book</span></div>
            <h1>Welcome Back, Doctor</h1>
            <p>Please enter your details to access your account.</p>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="../controllers/ContDocLogin.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="doctor@medibook.com" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="form-actions">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
        </form>
        
        <div class="login-footer">
            <p>Don't have an account? Contact the administrator.</p>
        </div>
    </div>
</body>
</html>
