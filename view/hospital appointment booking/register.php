<?php
session_start();

if (isset($_SESSION['patient_id'])) {
    header("Location: patientDashboard.php");
    exit();
}

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>

<div class="register-container">
    <div class="register-card">

        <h2>Patient Registration</h2>

        <?php if (!empty($success)) { ?>
            <div class="success">
                <?php echo $success; ?>
                <p><a href="login.php" class="login-link">Click here to login</a></p>
            </div>
        <?php } ?>

        <?php if (!empty($errors)) { ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <form action="../../controllers/patientRegisterController.php" method="POST" onsubmit="return validate(this)" novalidate>
            
            <label for="name"><b>Full Name:</b></label>
            <input type="text" name="name" id="name" placeholder="Enter your full name">
            <span id="nameErr"></span>

            <label for="email"><b>Email:</b></label>
            <input type="email" name="email" id="email" placeholder="Enter your email">
            <span id="emailErr"></span>

            <label for="phone"><b>Phone:</b></label>
            <input type="text" name="phone" id="phone" placeholder="Enter your phone number">
            <span id="phoneErr"></span>

            <label for="password"><b>Password:</b></label>
            <input type="password" name="password" id="password" placeholder="Enter password">
            <span id="passwordErr"></span>

            <label for="confirm_password"><b>Confirm Password:</b></label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password">
            <span id="confirmPasswordErr"></span>

            <label for="dob"><b>Date of Birth:</b></label>
            <input type="date" name="dob" id="dob">
            <span id="dobErr"></span>

            <label for="blood_group"><b>Blood Group:</b></label>
            <select name="blood_group" id="blood_group">
                <option value="">Select Blood Group </option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>

            <label for="gender"><b>Gender:</b></label>
            <select name="gender" id="gender">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="address"><b>Address:</b></label>
            <textarea name="address" id="address" rows="3" placeholder="Enter your address "></textarea>

            <label for="emergency_contact"><b>Emergency Contact:</b></label>
            <input type="text" name="emergency_contact" id="emergency_contact" placeholder="Emergency contact number">

            <input type="submit" value="Register">

            <p class="login-text">Already have an account? <a href="login.php">Login here</a></p>

        </form>

    </div>
</div>

<script src="../js/register.js"></script>

</body>
</html>