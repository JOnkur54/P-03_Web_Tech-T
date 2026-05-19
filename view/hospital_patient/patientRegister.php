<?php
session_start();
if (isset($_SESSION['patient_id'])) { header("Location: patientDashboard.php"); exit(); }
$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration</title>
    <link rel="stylesheet" href="../css/patient.css">
    <style>
    body { background-color:#0033a0; display:flex; align-items:center; justify-content:center; min-height:100vh; padding-top:0; }
    .register-wrapper { background:#fff; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.25); padding:36px 44px; width:520px; margin:30px auto; }
    .brand { text-align:center; margin-bottom:24px; }
    .brand h1 { font-size:26px; font-weight:700; color:#0033a0; }
    .brand h1 span { color:#4fc3f7; }
    </style>
</head>
<body>
<div class="register-wrapper">
    <div class="brand">
        <h1>Medi<span>Book</span></h1>
        <p>Hospital Appointment System</p>
    </div>
    <h2>Patient Registration</h2>
    <?php if ($success) { ?>
        <div class="success"><?php echo htmlspecialchars($success); ?> <a href="patientLogin.php" class="login-link">Login here</a></div>
    <?php } ?>
    <?php if (!empty($errors)) { ?>
        <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
    <?php } ?>
    <form action="../../controllers/patientRegisterController.php" method="POST" onsubmit="return validate(this)" novalidate>
        <label>Full Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter your full name">
        <span id="nameErr"></span>
        <label>Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email">
        <span id="emailErr"></span>
        <label>Phone:</label>
        <input type="text" name="phone" id="phone" placeholder="Phone number">
        <span id="phoneErr"></span>
        <label>Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter password">
        <span id="passwordErr"></span>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password">
        <span id="confirmPasswordErr"></span>
        <label>Date of Birth:</label>
        <input type="date" name="dob" id="dob">
        <span id="dobErr"></span>
        <label>Blood Group:</label>
        <select name="blood_group">
            <option value="">Select</option>
            <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg) { ?>
                <option value="<?php echo $bg; ?>"><?php echo $bg; ?></option>
            <?php } ?>
        </select>
        <label>Gender:</label>
        <select name="gender">
            <option value="">Select</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <label>Address:</label>
        <textarea name="address" rows="2" placeholder="Your address"></textarea>
        <label>Emergency Contact Name:</label>
        <input type="text" name="emergency_contact_name" placeholder="Emergency contact name">
        <label>Emergency Contact Phone:</label>
        <input type="text" name="emergency_contact_phone" placeholder="Emergency contact phone">
        <input type="submit" value="Register">
        <p class="login-text">Already have an account? <a href="patientLogin.php" class="login-link">Login here</a></p>
    </form>
</div>
<script src="../js/patientRegister.js"></script>
</body>
</html>