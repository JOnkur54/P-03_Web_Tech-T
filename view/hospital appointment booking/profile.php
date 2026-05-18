<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['patient_profile'])) {
    header("Location: ../../controllers/patientProfileController.php");
    exit();
}

$patient = $_SESSION['patient_profile'];

unset($_SESSION['patient_profile']);

$profilePicPath = "";
if (!empty($patient['profile_pic'])) {
    $picFile = "../profilePicture/" . $patient['profile_pic'];
    if (file_exists($picFile) && strlen($patient['profile_pic']) < 100) {
        $profilePicPath = $picFile;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Patient Profile</h2>

        <?php if ($profilePicPath != "") { ?>
            <div class="profile-picture-container">
                <img src="<?php echo $profilePicPath; ?>" alt="Profile Picture" class="profile-picture">
            </div>
        <?php } else { ?>
            <div class="profile-picture-container">
                <div class="no-picture">No Profile Picture</div>
            </div>
        <?php } ?>

        <p><b>Name:</b> <?php echo $patient['name']; ?></p>
        <p><b>Email:</b> <?php echo $patient['email']; ?></p>
        <p><b>Phone:</b> <?php echo $patient['phone']; ?></p>
        <p><b>Date of Birth:</b> <?php echo $patient['date_of_birth']; ?></p>
        <p><b>Blood Group:</b> <?php echo $patient['blood_group']; ?></p>
        <p><b>Gender:</b> <?php echo ucfirst($patient['gender']); ?></p>
        <p><b>Address:</b> <?php echo $patient['address']; ?></p>
        <p><b>Emergency Contact:</b> <?php echo $patient['emergency_contact_name']; ?> - <?php echo $patient['emergency_contact_phone']; ?></p>

        <p><a href="../../controllers/patientManageProfileShowController.php" class="edit-link">Edit Profile</a></p>

    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

</body>
</html>