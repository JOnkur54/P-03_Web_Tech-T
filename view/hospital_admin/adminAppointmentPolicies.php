<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: adminLogin.php"); exit(); }
if (!isset($_SESSION['policies'])) { header("Location: ../../controllers/adminAppointmentPoliciesController.php"); exit(); }

$policies = $_SESSION['policies'];
$success  = isset($_SESSION['success']) ? $_SESSION['success'] : "";
$errors   = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
unset($_SESSION['policies'], $_SESSION['success'], $_SESSION['errors']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Policies</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>
<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Global Appointment Policies</h2>

        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>

        <?php if (!empty($errors)) { ?>
            <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
        <?php } ?>

        <div class="form-section">
            <form action="../../controllers/adminSavePoliciesController.php" method="POST" onsubmit="return validatePolicies(this)" novalidate>

                <label for="min_cancel_hours">Minimum Cancellation Notice (hours):</label>
                <input type="number" name="min_cancel_hours" id="min_cancel_hours" value="<?php echo htmlspecialchars($policies['min_cancel_hours']); ?>" min="0">
                <span id="cancelErr"></span>

                <label for="max_advance_days">Maximum Advance Booking Window (days):</label>
                <input type="number" name="max_advance_days" id="max_advance_days" value="<?php echo htmlspecialchars($policies['max_advance_days']); ?>" min="1">
                <span id="advanceErr"></span>

                <label for="default_fee">Default Consultation Fee (&#2547;):</label>
                <input type="number" name="default_fee" id="default_fee" value="<?php echo htmlspecialchars($policies['default_fee']); ?>" min="0" step="0.01">
                <span id="feeErr"></span>

                <input type="submit" value="Save Policies">
            </form>
        </div>
    </div>
</div>

<?php include "../partials/adminRight.php"; ?>

</div>

<script src="../js/adminPolicies.js"></script>

<?php include "../partials/adminFooter.php"; ?>