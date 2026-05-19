<?php
session_start();
$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Login</title>
    <link rel="stylesheet" href="../css/receptionist.css">
    <style>
    body { background-color: #0033a0; display:flex; align-items:center; justify-content:center; min-height:100vh; padding-top:0; }
    .login-wrapper { background:#fff; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.25); padding:40px 44px; width:400px; }
    .brand { text-align:center; margin-bottom:28px; }
    .brand h1 { font-size:26px; font-weight:700; color:#0033a0; }
    .brand h1 span { color:#4fc3f7; }
    .brand p { font-size:13px; color:#666; margin-top:4px; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="brand">
        <h1>Medi<span>Book</span></h1>
        <p>Hospital Appointment System</p>
    </div>
    <h2>Receptionist Login</h2>
    <p><?php echo $success ? htmlspecialchars($success) : ""; ?></p>
    <?php if (!empty($errors)) { ?>
        <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
    <?php } ?>
    <form action="../../controllers/receptionistLoginController.php" method="POST" novalidate>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="receptionist@hospital.com">
        <span id="emailErr"></span>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="••••••••">
        <span id="passwordErr"></span>
        <input type="submit" value="Login">
    </form>
</div>
<script src="../js/receptionistLogin.js"></script>
</body>
</html>