<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['reviews'])) {
    header("Location: ../../controllers/patientReviewsShowController.php");
    exit();
}

$reviews = $_SESSION['reviews'];
$pending_reviews = isset($_SESSION['pending_reviews']) ? $_SESSION['pending_reviews'] : [];
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['reviews'], $_SESSION['pending_reviews'], $_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="../css/reviews.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">

    <?php if (!empty($pending_reviews)) { ?>
        <div class="card">
            <h2>Appointments Pending Review</h2>
            <?php foreach ($pending_reviews as $apt) { ?>
                <div class="pending-review">
                    <p><b>Doctor:</b> <?php echo $apt['doctor_name']; ?></p>
                    <p><b>Date:</b> <?php echo $apt['appointment_date']; ?></p>
                    <form action="../../controllers/patientReviewsController.php" method="POST" onsubmit="return validate(this)" novalidate>
                        <input type="hidden" name="appointment_id" value="<?php echo $apt['id']; ?>">
                        <input type="hidden" name="doctor_id" value="<?php echo $apt['doctor_id']; ?>">
                        
                        <label for="rating_<?php echo $apt['id']; ?>">Rating (1-5):</label>
                        <select name="rating" id="rating_<?php echo $apt['id']; ?>">
                            <option value="">Select</option>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Poor</option>
                        </select>
                        <span id="ratingErr_<?php echo $apt['id']; ?>"></span>
                        
                        <label for="review_text_<?php echo $apt['id']; ?>">Review:</label>
                        <textarea name="review_text" id="review_text_<?php echo $apt['id']; ?>" rows="3" placeholder="Write your review..."></textarea>
                        
                        <input type="submit" value="Submit Review">
                    </form>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="card">
        <h2>Your Reviews</h2>

        <?php if (!empty($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if (!empty($errors)) { ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if (empty($reviews)) { ?>
            <p>You haven't submitted any reviews yet.</p>
        <?php } else { ?>
            <?php foreach ($reviews as $review) { ?>
                <div class="review-card">
                    <h3><?php echo $review['doctor_name']; ?></h3>
                    <p><b>Rating:</b> <?php echo $review['rating']; ?>/5</p>
                    <p><?php echo $review['review_text']; ?></p>
                    <p class="review-date">Reviewed on <?php echo date("M d, Y", strtotime($review['created_at'])); ?></p>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

<script src="../js/reviews.js"></script>

</body>
</html>