<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
     header("Location: patientLogin.php");
      exit(); 
      }
if (!isset($_SESSION['announcements'])) { 
    header("Location: ../../controllers/patientAnnouncementsShowController.php");
     exit(); }
$announcements = $_SESSION['announcements'];
unset($_SESSION['announcements']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>

<div class="layout">

<?php include "../partials/patientLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Hospital Announcements</h2>

        <?php if (empty($announcements)) { ?>
            <p>No announcements at this time.</p>
        <?php } else { ?>
            <?php foreach ($announcements as $ann) { ?>
            <div class="announcement-card">
                <div class="announcement-header">
                    <h3 style="margin:0;font-size:15px;color:#0033a0;"><?php echo htmlspecialchars($ann['title']); ?></h3>
                    <span class="announcement-date">
                        <?php echo date("d M Y", strtotime($ann['published_at'])); ?>
                    </span>
                </div>
                <div style="font-size:13px;margin-top:10px;line-height:1.7;color:#444;">
                    <?php echo nl2br(htmlspecialchars($ann['body'])); ?>
                </div>
                <div style="margin-top:8px;">
                    <span style="font-size:11px;background:#e8f0fe;color:#0033a0;padding:2px 10px;border-radius:10px;">
                        <?php echo ucfirst($ann['target_role']); ?>
                    </span>
                </div>
            </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php include "../partials/patientRight.php"; ?>

</div>

<?php include "../partials/patientFooter.php"; ?>