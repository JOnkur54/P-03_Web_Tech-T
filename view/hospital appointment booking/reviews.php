<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../../model/patientModel.php';
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$reviews = getDoctorReviews($conn, $patient['id'] ?? 0);
$pendingReviews = getAppointmentsPendingReview($conn, $patient['id'] ?? 0);
$errors = $_SESSION['errors'] ?? []; 
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>Doctor Reviews</h2>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>

        <?php if (!empty($pendingReviews)): ?>
            <div class="card" style="background:#e9f7ef;">
                <h3>Write a review</h3>
                <form action="../../controllers/patientReviewController.php" method="POST">
                    <select name="appointment_data" id="appointmentData" required>
                        <option value="">Choose completed appointment</option>
                        <?php foreach ($pendingReviews as $apt): ?>
                            <option value="<?php echo (int)$apt['id'] . '|' . (int)$apt['doctor_id']; ?>"><?php echo htmlspecialchars($apt['appointment_date'] . ' — Dr. ' . $apt['doctor_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="appointment_id" id="appointmentId" value="0">
                    <input type="hidden" name="doctor_id" id="doctorId" value="0">
                    <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
                    <textarea name="review_text" placeholder="Write your review" rows="4"></textarea>
                    <input type="submit" value="Submit Review">
                    <script>
                        const appointmentData = document.getElementById('appointmentData');
                        const appointmentId = document.getElementById('appointmentId');
                        const doctorId = document.getElementById('doctorId');

                        appointmentData.addEventListener('change', function () {
                            const [aptId, docId] = this.value.split('|');
                            appointmentId.value = aptId || '0';
                            doctorId.value = docId || '0';
                        });
                    </script>
                </form>
            </div>
        <?php else: ?>
            <p>You have no completed appointments pending review.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Your Reviews</h3>
        <?php if (empty($reviews)): ?>
            <p>No reviews submitted yet.</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="card" style="padding:15px; margin-bottom:12px; background:#f8f9fa;">
                    <h4><?php echo htmlspecialchars($review['doctor_name']); ?> <span style="font-size:12px; color:#6c757d;">(<?php echo htmlspecialchars($review['rating']); ?>/5)</span></h4>
                    <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>