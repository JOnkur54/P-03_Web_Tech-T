<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorReview.php';

$doctor_id = $_SESSION['doctor_id'];
$reviewModel = new ModelDoctorReview($conn);
$reviews = $reviewModel->getReviews($doctor_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Reviews - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">Medi<span>Book</span></div>
        <ul class="sidebar-nav">
            <li><a href="ViewDocDashboard.php">Dashboard</a></li>
            <li><a href="ViewDocAppointments.php">Appointments</a></li>
            <li><a href="ViewDocAvailability.php">Availability</a></li>
            <li><a href="ViewDocProfile.php">My Profile</a></li>
            <li><a href="ViewDocReviews.php" class="active">Reviews</a></li>
            <li><a href="ViewDocBilling.php">Earnings</a></li>
        </ul>
        <a href="../controllers/ContDocLogin.php?logout=true" class="logout-btn">Log Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Patient Reviews</h1>
        </div>

        <?php if (empty($reviews)): ?>
            <div class="card" style="background-color: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow); text-align: center; color: var(--gray);">
                No reviews found.
            </div>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="header">
                        <div class="patient-name"><?php echo htmlspecialchars($review['patient_name']); ?></div>
                        <div class="rating">
                            <?php 
                                for ($i = 0; $i < 5; $i++) {
                                    echo $i < $review['rating'] ? '★' : '☆';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="text">
                        <?php echo htmlspecialchars($review['review_text']); ?>
                    </div>
                    <div style="font-size: 12px; color: var(--gray); margin-bottom: 10px;">
                        Posted on <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                    </div>
                    
                    <!-- Option to reply -->
                    <div class="reply-box">
                        <form action="../controllers/ContDocReview.php" method="POST">
                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                            <label style="font-size: 13px; font-weight: 500; display: block; margin-bottom: 5px;">Reply to this review</label>
                            <textarea name="reply_text" placeholder="Write your reply here..."></textarea>
                            <button type="submit" class="btn btn-primary btn-sm">Submit Reply</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
