<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Patient</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Register New Patient</h2>
        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>
        <?php if (!empty($errors)) { ?>
            <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
        <?php } ?>
        <form action="../../controllers/receptionistRegisterPatientController.php" method="POST" novalidate>
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name">
            <span id="nameErr"></span>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email">
            <span id="emailErr"></span>
            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone">
            <span id="phoneErr"></span>
            <label for="password">Temporary Password:</label>
            <input type="password" name="password" id="password">
            <span id="passErr"></span>
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob">
            <label for="blood_group">Blood Group:</label>
            <select name="blood_group" id="blood_group">
                <option value="">Select</option>
                <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg) { ?>
                    <option value="<?php echo $bg; ?>"><?php echo $bg; ?></option>
                <?php } ?>
            </select>
            <label for="gender">Gender:</label>
            <select name="gender" id="gender">
                <option value="">Select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            <label for="address">Address:</label>
            <textarea name="address" id="address" rows="2"></textarea>
            <label for="emergency_contact_name">Emergency Contact Name:</label>
            <input type="text" name="emergency_contact_name" id="emergency_contact_name">
            <label for="emergency_contact_phone">Emergency Contact Phone:</label>
            <input type="text" name="emergency_contact_phone" id="emergency_contact_phone">
            <input type="submit" value="Register Patient">
        </form>
    </div>
</div>
<?php include "../partials/receptionistRight.php"; ?>
</div>
<?php include "../partials/receptionistFooter.php"; ?>
<script src="../js/receptionistRegister.js"></script>