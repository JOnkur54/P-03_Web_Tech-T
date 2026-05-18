<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['patient_profile'])) {
    header("Location: ../../controllers/patientManageProfileShowController.php");
    exit();
}

$patient = $_SESSION['patient_profile'];
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['patient_profile'], $_SESSION['errors'], $_SESSION['success']);

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
    <title>Manage Profile</title>
    <link rel="stylesheet" href="../css/manageProfile.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Manage Profile</h2>

        <?php if (!empty($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
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

        <!-- SECTION 1: Profile Picture Upload -->
        <div class="form-section">
            <h3>Profile Picture</h3>
            <form action="../../controllers/patientManageProfileController.php" method="POST" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="action" value="upload_picture">

                <?php if ($profilePicPath != "") { ?>
                    <div class="current-picture">
                        <img src="<?php echo $profilePicPath; ?>" alt="Profile Picture" class="profile-img">
                        <p>Current Picture</p>
                    </div>
                <?php } ?>

                <label for="profile_pic">Choose New Picture:</label>
                <input type="file" name="profile_pic" id="profile_pic" accept="image/*" required>

                <input type="submit" value="Upload Picture">
            </form>
        </div>

        <!-- SECTION 2: Personal Information -->
        <div class="form-section">
            <h3>Personal Information</h3>
            <form action="../../controllers/patientManageProfileController.php" method="POST" onsubmit="return validateInfo(this)" novalidate>
                <input type="hidden" name="action" value="update_info">

                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $patient['name']; ?>">
                <span id="nameErr"></span>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $patient['email']; ?>">
                <span id="emailErr"></span>

                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" value="<?php echo $patient['phone']; ?>">

                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" value="<?php echo $patient['date_of_birth']; ?>">

                <label for="blood_group">Blood Group:</label>
                <input type="text" name="blood_group" id="blood_group" value="<?php echo $patient['blood_group']; ?>">

                <label for="gender">Gender:</label>
                <select name="gender" id="gender">
                    <option value="">Choose</option>
                    <option value="male" <?php if ($patient['gender'] == "male") { echo "selected"; } ?>>Male</option>
                    <option value="female" <?php if ($patient['gender'] == "female") { echo "selected"; } ?>>Female</option>
                    <option value="other" <?php if ($patient['gender'] == "other") { echo "selected"; } ?>>Other</option>
                </select>

                <label for="address">Address:</label>
                <textarea name="address" id="address" rows="3"><?php echo $patient['address']; ?></textarea>

                <label for="emergency_name">Emergency Contact Name:</label>
                <input type="text" name="emergency_name" id="emergency_name" value="<?php echo $patient['emergency_contact_name']; ?>">

                <label for="emergency_phone">Emergency Contact Phone:</label>
                <input type="text" name="emergency_phone" id="emergency_phone" value="<?php echo $patient['emergency_contact_phone']; ?>">

                <input type="submit" value="Update Information">
            </form>
        </div>

        <!-- SECTION 3: Change Password -->
        <div class="form-section">
            <h3>Change Password</h3>
            <form action="../../controllers/patientManageProfileController.php" method="POST" onsubmit="return validatePassword(this)" novalidate>
                <input type="hidden" name="action" value="change_password">

                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" id="current_password">
                <span id="currentPasswordErr"></span>

                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password">
                <span id="newPasswordErr"></span>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password">
                <span id="confirmPasswordErr"></span>

                <input type="submit" value="Change Password">
            </form>
        </div>

    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

<script src="../js/manageProfile.js"></script>

</body>
</html>