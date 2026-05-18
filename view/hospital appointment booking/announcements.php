<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['announcements'])) {
    header("Location: ../../controllers/patientAnnouncementsShowController.php");
    exit();
}

$announcements = $_SESSION['announcements'];

unset($_SESSION['announcements']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="../css/announcements.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Hospital Announcements</h2>

        <?php if (empty($announcements)) { ?>
            <p>No announcements at this time.</p>
        <?php } else { ?>
            <?php foreach ($announcements as $announcement) { ?>
                <div class="announcement-card">
                    <div class="announcement-header">
                        <h3><?php echo $announcement['title']; ?></h3>
                        <span class="announcement-date"><?php echo date("M d, Y", strtotime($announcement['created_at'])); ?></span>
                    </div>
                    <div class="announcement-content">
                        <?php echo nl2br($announcement['content']); ?>
                    </div>
                    <?php if ($announcement['is_important']) { ?>
                        <span class="important-badge">Important</span>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>

    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

</body>
</html>