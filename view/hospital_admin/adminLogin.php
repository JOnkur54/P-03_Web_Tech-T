<?php
session_start();

$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';

unset($_SESSION['errors']);
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/adminLogin.css">
</head>
<body>

<div class="login-wrapper">

    <div class="brand">
        <h1>Medi<span>Book</span></h1>
        <p>Hospital Appointment System</p>
    </div>

    <h2>Admin Login</h2>

    <p class="success-box">
        <?php echo $success ? htmlspecialchars($success) : ""; ?>
    </p>

    <?php if (!empty($errors)) { ?>
        <div class="error-box">
            <ul>
                <?php foreach ($errors as $error) { ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <form action="../../controllers/adminLoginController.php" method="POST" onsubmit="return validateLogin(this)" novalidate>

        <div class="field-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="admin@hospital.com">
            <span id="emailErr"></span>
        </div>

        <div class="field-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="••••••••">
            <span id="passwordErr"></span>
        </div>

        <input type="submit" value="Login">

    </form>

</div>

<script src="../js/adminLogin.js"></script>

</body>
</html>