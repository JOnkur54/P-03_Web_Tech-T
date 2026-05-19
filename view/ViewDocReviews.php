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
        <!-- fixed CSS -->
    <style>
        body { display: flex; min-height: 100vh; background-color: #f3f4f6; }
        .sidebar { width: 250px; background-color: white; border-right: 1px solid var(--light-gray); padding: 20px; display: flex; flex-direction: column; }
        .sidebar .logo { font-size: 20px; font-weight: 700; margin-bottom: 30px; color: var(--dark); }
        .sidebar .logo span { color: var(--primary); }
        .sidebar-nav { list-style: none; flex-grow: 1; }
        .sidebar-nav li { margin-bottom: 10px; }
        .sidebar-nav a { display: flex; align-items: center; padding: 12px; border-radius: 8px; text-decoration: none; color: var(--gray); font-size: 14px; font-weight: 500; transition: all 0.2s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background-color: var(--secondary); color: var(--primary); }
        .main-content { flex-grow: 1; padding: 30px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; font-weight: 700; }
        .review-card { background-color: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow); margin-bottom: 20px; }
        .review-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .review-card .patient-name { font-weight: 600; font-size: 16px; }
        .review-card .rating { color: #f59e0b; font-weight: 700; }
        .review-card .text { font-size: 14px; color: var(--dark); margin-bottom: 15px; }
        .review-card .reply-box { background-color: #f9fafb; padding: 15px; border-radius: 8px; margin-top: 10px; }
        .review-card .reply-box textarea { width: 100%; padding: 10px; border: 1px solid var(--light-gray); border-radius: 8px; font-size: 14px; margin-bottom: 10px; }
        .logout-btn { margin-top: auto; color: var(--danger); text-decoration: none; font-size: 14px; font-weight: 500; padding: 12px; display: flex; align-items: center; border-radius: 8px; }
        .logout-btn:hover { background-color: #fee2e2; }
    </style>
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
