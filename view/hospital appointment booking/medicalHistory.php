<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../../model/patientModel.php';
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$doctorNotes = getPatientMedicalNotes($conn, $patient['id'] ?? 0);
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>Medical History</h2>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>

        <form action="../../controllers/patientMedicalHistoryController.php" method="POST">
            <textarea name="note_text" placeholder="Add a personal medical note or symptom..." rows="5"></textarea>
            <input type="submit" value="Save Note">
        </form>
    </div>

    <div class="card">
        <h3>Personal Medical Notes</h3>
        <?php if (empty($doctorNotes)): ?>
            <p>No medical notes saved yet.</p>
        <?php else: ?>
            <?php foreach ($doctorNotes as $note): ?>
                <div class="card" style="padding:15px; margin-bottom:12px; background:#f8f9fa;">
                    <p><?php echo nl2br(htmlspecialchars($note)); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>