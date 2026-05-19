<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: adminLogin.php"); exit(); }
if (!isset($_SESSION['complaints'])) { header("Location: ../../controllers/adminComplaintsController.php"); exit(); }

$complaints = $_SESSION['complaints'];
$success    = isset($_SESSION['success']) ? $_SESSION['success'] : "";
$errors     = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
unset($_SESSION['complaints'], $_SESSION['success'], $_SESSION['errors']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Complaints</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>
<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Patient Complaints</h2>

        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>

        <?php if (!empty($errors)) { ?>
            <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
        <?php } ?>

        <?php if (empty($complaints)) { ?>
            <p>No complaints found.</p>
        <?php } else { ?>
            <?php foreach ($complaints as $c) { ?>
            <div class="form-section" style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <strong><?php echo htmlspecialchars($c['patient_name']); ?></strong>
                    <span style="font-size:12px;color:#666;"><?php echo date('d M Y', strtotime($c['created_at'])); ?></span>
                </div>
                <p style="margin-bottom:8px;"><?php echo htmlspecialchars($c['message']); ?></p>

                <?php if ($c['status'] == "resolved") { ?>
                    <p style="color:#198754;font-size:13px;"><strong>Resolved:</strong> <?php echo htmlspecialchars($c['admin_response']); ?></p>
                <?php } else { ?>
                    <form action="../../controllers/adminResolveComplaintController.php" method="POST" onsubmit="return validateResolve(this)" novalidate>
                        <input type="hidden" name="complaint_id" value="<?php echo $c['id']; ?>">
                        <label>Admin Response:</label>
                        <textarea name="response" rows="2" placeholder="Enter resolution note..." style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;margin-bottom:6px;"></textarea>
                        <span class="resolve-err" id="resolveErr_<?php echo $c['id']; ?>"></span>
                        <button type="submit" class="btn-approve">Mark as Resolved</button>
                    </form>
                <?php } ?>
            </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php include "../partials/adminRight.php"; ?>

</div>

<script src="../js/adminComplaints.js"></script>

<?php include "../partials/adminFooter.php"; ?>