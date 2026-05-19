<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['patient_profile'])) { header("Location: ../../controllers/patientManageProfileShowController.php"); exit(); }
$patient = $_SESSION['patient_profile'];
$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['patient_profile'], $_SESSION['errors'], $_SESSION['success']);
$profilePicPath = "";
if (!empty($patient['profile_pic'])) {
    $picFile = "../profilePicture/" . $patient['profile_pic'];
    if (file_exists($picFile)) { $profilePicPath = $picFile; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Manage Profile</h2>
        <?php if ($success) { ?><div class="success"><?php echo htmlspecialchars($success); ?></div><?php } ?>
        <?php if (!empty($errors)) { ?><div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div><?php } ?>

        <div class="form-section">
            <h3>Profile Picture</h3>
            <?php if ($profilePicPath != "") { ?>
                <div style="margin-bottom:12px;"><img src="<?php echo $profilePicPath; ?>" alt="Profile" class="profile-img"></div>
            <?php } ?>
            <form action="../../controllers/patientManageProfileController.php" method="POST" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="action" value="upload_picture">
                <label>Choose New Picture:</label>
                <input type="file" name="profile_pic" accept="image/*">
                <input type="submit" value="Upload Picture">
            </form>
        </div>

        <div class="form-section">
            <h3>Personal Information</h3>
            <form action="../../controllers/patientManageProfileController.php" method="POST" onsubmit="return validateInfo(this)" novalidate>
                <input type="hidden" name="action" value="update_info">
                <label>Full Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($patient['name']); ?>">
                <span id="nameErr"></span>
                <label>Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($patient['email']); ?>">
                <span id="emailErr"></span>
                <label>Phone:</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>">
                <label>Date of Birth:</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($patient['date_of_birth']); ?>">
                <label>Blood Group:</label>
                <input type="text" name="blood_group" value="<?php echo htmlspecialchars($patient['blood_group']); ?>">
                <label>Gender:</label>
                <select name="gender">
                    <option value="">Choose</option>
                    <option value="male" <?php if (strtolower($patient['gender']) == "male") { echo "selected"; } ?>>Male</option>
                    <option value="female" <?php if (strtolower($patient['gender']) == "female") { echo "selected"; } ?>>Female</option>
                    <option value="other" <?php if (strtolower($patient['gender']) == "other") { echo "selected"; } ?>>Other</option>
                </select>
                <label>Address:</label>
                <textarea name="address" rows="3"><?php echo htmlspecialchars($patient['address']); ?></textarea>
                <label>Emergency Contact Name:</label>
                <input type="text" name="emergency_name" value="<?php echo htmlspecialchars($patient['emergency_contact_name']); ?>">
                <label>Emergency Contact Phone:</label>
                <input type="text" name="emergency_phone" value="<?php echo htmlspecialchars($patient['emergency_contact_phone']); ?>">
                <input type="submit" value="Update Information">
            </form>
        </div>

        <div class="form-section">
            <h3>Change Password</h3>
            <form action="../../controllers/patientManageProfileController.php" method="POST" onsubmit="return validatePassword(this)" novalidate>
                <input type="hidden" name="action" value="change_password">
                <label>Current Password:</label>
                <input type="password" name="current_password" id="current_password">
                <span id="currentPasswordErr"></span>
                <label>New Password:</label>
                <input type="password" name="new_password" id="new_password">
                <span id="newPasswordErr"></span>
                <label>Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password">
                <span id="confirmPasswordErr"></span>
                <input type="submit" value="Change Password">
            </form>
        </div>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>
<script src="../js/patientManageProfile.js"></script>