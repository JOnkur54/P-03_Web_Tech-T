<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../../model/patientModel.php';
$announcements = getAnnouncementsForPatient($conn);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>Announcements</h2>
        <?php if (empty($announcements)): ?>
            <p>No announcements at this time.</p>
        <?php else: ?>
            <?php foreach ($announcements as $item): ?>
                <div class="card" style="padding:18px; margin-bottom:16px; background:#f4f7fb;">
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($item['body'])); ?></p>
                    <small>Published: <?php echo htmlspecialchars(date('Y-m-d', strtotime($item['published_at']))); ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>