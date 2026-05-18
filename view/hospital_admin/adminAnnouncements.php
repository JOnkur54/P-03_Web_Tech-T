<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: adminLogin.php"); exit(); }
if (!isset($_SESSION['announcements'])) { header("Location: ../../controllers/adminAnnouncementsController.php"); exit(); }

$announcements = $_SESSION['announcements'];
$success       = isset($_SESSION['success']) ? $_SESSION['success'] : "";
$errors        = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
unset($_SESSION['announcements'], $_SESSION['success'], $_SESSION['errors']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>
<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Hospital Announcements</h2>

        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>

        <?php if (!empty($errors)) { ?>
            <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
        <?php } ?>

        <div class="form-section">
            <h3>Post New Announcement</h3>
            <form action="../../controllers/adminAnnouncementActionController.php" method="POST" onsubmit="return validateAnnouncement(this)" novalidate>
                <input type="hidden" name="action" value="post">

                <label for="title">Title:</label>
                <input type="text" name="title" id="title" placeholder="Announcement title">
                <span id="titleErr"></span>

                <label for="body">Message:</label>
                <textarea name="body" id="body" rows="4" placeholder="Write the announcement here..."></textarea>
                <span id="bodyErr"></span>

                <label for="target">Target Audience:</label>
                <select name="target" id="target">
                    <option value="all">All Users</option>
                    <option value="patient">Patients Only</option>
                    <option value="doctor">Doctors Only</option>
                </select>

                <input type="submit" value="Post Announcement">
            </form>
        </div>

        <h3>Posted Announcements</h3>

        <?php if (empty($announcements)) { ?>
            <p>No announcements posted yet.</p>
        <?php } else { ?>
            <?php foreach ($announcements as $ann) { ?>
            <div class="form-section" style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <strong style="font-size:15px;color:#0033a0;"><?php echo htmlspecialchars($ann['title']); ?></strong>
                        <span style="margin-left:10px;font-size:11px;background:#e8f0fe;color:#0033a0;padding:2px 8px;border-radius:10px;">
                            <?php echo ucfirst($ann['target_role']); ?>
                        </span>
                    </div>
                    <form action="../../controllers/adminAnnouncementActionController.php" method="POST" style="display:inline;" onsubmit="return confirm('Delete this announcement?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="announcement_id" value="<?php echo $ann['id']; ?>">
                        <button type="submit" class="btn-reject">Delete</button>
                    </form>
                </div>
                <p style="margin:8px 0;font-size:13px;"><?php echo htmlspecialchars($ann['body']); ?></p>
                <small style="color:#666;">Posted by <?php echo htmlspecialchars($ann['author_name']); ?> on <?php echo date('d M Y, h:i A', strtotime($ann['published_at'])); ?></small>
            </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php include "../partials/adminRight.php"; ?>

</div>

<script src="../js/adminAnnouncements.js"></script>

<?php include "../partials/adminFooter.php"; ?>