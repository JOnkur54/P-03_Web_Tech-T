<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['reviews'])) { header("Location: ../../controllers/patientReviewsShowController.php"); exit(); }
$reviews         = $_SESSION['reviews'];
$pending_reviews = isset($_SESSION['pending_reviews']) ? $_SESSION['pending_reviews'] : [];
$errors          = isset($_SESSION['errors'])          ? $_SESSION['errors']          : [];
$success         = isset($_SESSION['success'])         ? $_SESSION['success']         : "";
unset($_SESSION['reviews'], $_SESSION['pending_reviews'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <?php if ($success) { ?><div class="success" style="margin-bottom:16px;"><?php echo htmlspecialchars($success); ?></div><?php } ?>
    <?php if (!empty($errors)) { ?><div class="error" style="margin-bottom:16px;"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div><?php } ?>
    <?php if (!empty($pending_reviews)) { ?>
    <div class="card">
        <h2>Appointments Awaiting Your Review</h2>
        <?php foreach ($pending_reviews as $apt) { ?>
        <div class="pending-review">
            <p><strong>Doctor:</strong> <?php echo htmlspecialchars($apt['doctor_name']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($apt['appointment_date']); ?></p>
            <form action="../../controllers/patientReviewController.php" method="POST" onsubmit="return validate(this)" novalidate>
                <input type="hidden" name="appointment_id" value="<?php echo $apt['id']; ?>">
                <input type="hidden" name="doctor_id" value="<?php echo $apt['doctor_id']; ?>">
                <label>Rating (1-5):</label>
                <select name="rating" id="rating_<?php echo $apt['id']; ?>">
                    <option value="">Select rating</option>
                    <option value="5">5 — Excellent</option>
                    <option value="4">4 — Good</option>
                    <option value="3">3 — Average</option>
                    <option value="2">2 — Poor</option>
                    <option value="1">1 — Very Poor</option>
                </select>
                <span id="ratingErr_<?php echo $apt['id']; ?>"></span>
                <label>Review:</label>
                <textarea name="review_text" rows="3" placeholder="Write your review..."></textarea>
                <input type="submit" value="Submit Review">
            </form>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
    <div class="card">
        <h2>Your Reviews</h2>
        <?php if (empty($reviews)) { ?>
            <p>You haven't submitted any reviews yet.</p>
        <?php } else { ?>
            <?php foreach ($reviews as $r) { ?>
            <div class="review-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <h3 style="margin:0;font-size:14px;"><?php echo htmlspecialchars($r['doctor_name']); ?></h3>
                    <span class="stars" style="font-size:16px;"><?php for ($i=1;$i<=5;$i++) { echo $i<=$r['rating'] ? "★" : "☆"; } ?></span>
                </div>
                <p style="font-size:13px;"><?php echo htmlspecialchars($r['review_text']); ?></p>
                <p class="review-date">Reviewed on <?php echo date("d M Y", strtotime($r['created_at'])); ?></p>
            </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>
<script src="../js/patientReviews.js"></script>