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
    <title>My Reviews</title>
    <link rel="stylesheet" href="../css/patient.css">
    <style>
    .star-row { display:flex; gap:6px; margin:10px 0 4px; }
    .star-row span {
        font-size:34px;
        color:#ccc;
        cursor:pointer;
        line-height:1;
        transition:color 0.1s;
        user-select:none;
    }
    .star-row span.lit { color:#f59e0b; }
    .star-display { color:#f59e0b; font-size:18px; letter-spacing:2px; }
    .pending-card { border-left:4px solid #f59e0b; background:#fffbeb; padding:18px; border-radius:6px; margin-bottom:18px; }
    .review-card  { border-left:3px solid #0033a0; background:#f8f9fa; padding:16px; border-radius:6px; margin-bottom:14px; }
    .review-meta  { font-size:12px; color:#999; margin-top:6px; }
    .review-actions { display:flex; gap:10px; margin-top:10px; }
    .modal-bg { display:none; position:fixed; top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center; }
    .modal-bg.open { display:flex; }
    .modal-box { background:#fff; border-radius:8px; padding:28px; width:460px; }
    .modal-box h3 { color:#0033a0; margin-bottom:14px; }
    .modal-close { float:right; background:none; border:none; font-size:22px; cursor:pointer; color:#666; margin-top:-4px; }
    .err-msg { color:red; font-size:12px; display:block; margin-bottom:6px; }
    </style>
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">

    <?php if ($success) { ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php } ?>
    <?php if (!empty($errors)) { ?>
        <div class="error">
            <ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul>
        </div>
    <?php } ?>

    <!-- ── Pending review forms ─────────────────────────────────── -->
    <?php if (!empty($pending_reviews)) { ?>
    <div class="card">
        <h2>Appointments Awaiting Review</h2>
        <p style="color:#666;font-size:13px;margin-bottom:14px;">You completed these appointments — rate your experience!</p>

        <?php foreach ($pending_reviews as $apt) {
            $uid = "appt_" . $apt['id'];
        ?>
        <div class="pending-card">
            <p style="font-weight:600;font-size:14px;margin-bottom:2px;"><?php echo htmlspecialchars($apt['doctor_name']); ?></p>
            <p style="font-size:12px;color:#666;margin-bottom:12px;">Date: <?php echo date('d M Y', strtotime($apt['appointment_date'])); ?></p>

            <form action="../../controllers/patientReviewController.php" method="POST" novalidate
                  onsubmit="return checkStars('<?php echo $uid; ?>')">
                <input type="hidden" name="action"         value="submit">
                <input type="hidden" name="appointment_id" value="<?php echo $apt['id']; ?>">
                <input type="hidden" name="doctor_id"      value="<?php echo $apt['doctor_id']; ?>">
                <input type="hidden" name="rating" id="hidden_<?php echo $uid; ?>" value="0">

                <label>Click to rate:</label>
                <div class="star-row" id="row_<?php echo $uid; ?>">
                    <span data-v="1" onclick="pickStar('<?php echo $uid; ?>',1)">&#9733;</span>
                    <span data-v="2" onclick="pickStar('<?php echo $uid; ?>',2)">&#9733;</span>
                    <span data-v="3" onclick="pickStar('<?php echo $uid; ?>',3)">&#9733;</span>
                    <span data-v="4" onclick="pickStar('<?php echo $uid; ?>',4)">&#9733;</span>
                    <span data-v="5" onclick="pickStar('<?php echo $uid; ?>',5)">&#9733;</span>
                </div>
                <span class="err-msg" id="err_<?php echo $uid; ?>"></span>

                <label>Written Review (optional):</label>
                <textarea name="review_text" rows="3" placeholder="Describe your experience..."></textarea>

                <input type="submit" value="Submit Review">
            </form>
        </div>
        <?php } ?>
    </div>
    <?php } else { ?>
    <div class="card">
        <h2>Appointments Awaiting Review</h2>
        <p style="color:#666;font-size:13px;">No completed appointments waiting for a review.</p>
    </div>
    <?php } ?>

    <!-- ── Submitted reviews ────────────────────────────────────── -->
    <div class="card">
        <h2>Your Submitted Reviews</h2>

        <?php if (empty($reviews)) { ?>
            <p style="color:#666;font-size:13px;">No reviews submitted yet.</p>
        <?php } else { ?>
            <?php foreach ($reviews as $r) { ?>
            <div class="review-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <strong style="font-size:14px;"><?php echo htmlspecialchars($r['doctor_name']); ?></strong>
                    <span class="star-display"><?php
                        for ($i=1;$i<=5;$i++) { echo $i<=$r['rating'] ? "★" : "☆"; }
                    ?></span>
                </div>
                <p style="font-size:13px;color:#333;line-height:1.6;">
                    <?php echo $r['review_text'] ? htmlspecialchars($r['review_text']) : '<em style="color:#999;">No written review.</em>'; ?>
                </p>
                <p class="review-meta">Submitted: <?php echo date('d M Y', strtotime($r['created_at'])); ?></p>
                <div class="review-actions">
                    <button class="btn btn-warning"
                        onclick="openEdit(<?php echo $r['id']; ?>,<?php echo $r['rating']; ?>,'<?php echo htmlspecialchars(addslashes($r['review_text']), ENT_QUOTES); ?>')">
                        Edit
                    </button>
                    <form method="POST" action="../../controllers/patientReviewController.php" style="display:inline;"
                          onsubmit="return confirm('Delete this review?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="review_id" value="<?php echo $r['id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            <?php } ?>
        <?php } ?>
    </div>

</div>
<?php include "../partials/patientRight.php"; ?>
</div>

<!-- EDIT MODAL -->
<div class="modal-bg" id="editModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeEdit()">&times;</button>
        <h3>Edit Review</h3>
        <form method="POST" action="../../controllers/patientReviewController.php" novalidate
              onsubmit="return checkStars('edit_modal')">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="review_id" id="edit_review_id">
            <input type="hidden" name="rating" id="hidden_edit_modal" value="0">

            <label>Rating:</label>
            <div class="star-row" id="row_edit_modal">
                <span data-v="1" onclick="pickStar('edit_modal',1)">&#9733;</span>
                <span data-v="2" onclick="pickStar('edit_modal',2)">&#9733;</span>
                <span data-v="3" onclick="pickStar('edit_modal',3)">&#9733;</span>
                <span data-v="4" onclick="pickStar('edit_modal',4)">&#9733;</span>
                <span data-v="5" onclick="pickStar('edit_modal',5)">&#9733;</span>
            </div>
            <span class="err-msg" id="err_edit_modal"></span>

            <label>Review:</label>
            <textarea name="review_text" id="edit_review_text" rows="4"></textarea>

            <input type="submit" value="Save Changes" style="margin-top:10px;">
        </form>
    </div>
</div>

<?php include "../partials/patientFooter.php"; ?>
<script src="../js/patientReviews.js"></script>