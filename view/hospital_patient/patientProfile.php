<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['patient_profile'])) { header("Location: ../../controllers/patientProfileController.php"); exit(); }
$patient = $_SESSION['patient_profile'];
unset($_SESSION['patient_profile']);
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
    <title>My Profile</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>My Profile</h2>
        <div class="profile-picture-container">
            <?php if ($profilePicPath != "") { ?>
                <img src="<?php echo $profilePicPath; ?>" alt="Profile Picture" class="profile-picture">
            <?php } else { ?>
                <div class="no-picture"><?php echo strtoupper(substr($patient['name'], 0, 1)); ?></div>
            <?php } ?>
        </div>
        <table>
            <tr><th style="width:35%;">Name</th><td><?php echo htmlspecialchars($patient['name']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($patient['email']); ?></td></tr>
            <tr><th>Phone</th><td><?php echo htmlspecialchars($patient['phone']); ?></td></tr>
            <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($patient['date_of_birth']); ?></td></tr>
            <tr><th>Blood Group</th><td><?php echo htmlspecialchars($patient['blood_group']); ?></td></tr>
            <tr><th>Gender</th><td><?php echo ucfirst(htmlspecialchars($patient['gender'])); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlspecialchars($patient['address']); ?></td></tr>
            <tr><th>Emergency Contact</th><td><?php echo htmlspecialchars($patient['emergency_contact_name']); ?> — <?php echo htmlspecialchars($patient['emergency_contact_phone']); ?></td></tr>
        </table>
        <div style="margin-top:16px;">
            <a href="../../controllers/patientManageProfileShowController.php" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>