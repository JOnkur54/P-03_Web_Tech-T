<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['doctor_details'])) {
    $doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
    header("Location: ../../controllers/patientDoctorDetailsShowController.php?doctor_id=" . $doctor_id);
    exit();
}

$doctor = $_SESSION['doctor_details'];
$availability = isset($_SESSION['doctor_availability']) ? $_SESSION['doctor_availability'] : [];
$reviews = isset($_SESSION['doctor_reviews']) ? $_SESSION['doctor_reviews'] : [];
$avg_rating = isset($_SESSION['avg_rating']) ? $_SESSION['avg_rating'] : 0;
$total_reviews = isset($_SESSION['total_reviews']) ? $_SESSION['total_reviews'] : 0;

unset($_SESSION['doctor_details'], $_SESSION['doctor_availability'], $_SESSION['doctor_reviews'], $_SESSION['avg_rating'], $_SESSION['total_reviews']);

if (!$doctor) {
    header("Location: doctors.php");
    exit();
}

// Get doctor photo path
$photo_path = "../profilePicture/user_" . $doctor['user_id'] . ".jpg";
if (!file_exists($photo_path)) {
    $photo_path = "../profilePicture/user_" . $doctor['user_id'] . ".png";
}
if (!file_exists($photo_path)) {
    $photo_path = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Details</title>
    <link rel="stylesheet" href="../css/doctorDetails.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <div class="doctor-header">
            <div class="doctor-photo">
                <?php if ($photo_path) { ?>
                    <img src="<?php echo $photo_path; ?>" alt="Doctor Photo">
                <?php } else { ?>
                    <div class="no-photo">No Photo</div>
                <?php } ?>
            </div>
            
            <div class="doctor-info">
                <h2><?php echo $doctor['doctor_name']; ?></h2>
                <p class="specialization"><?php echo $doctor['specialization']; ?></p>
                
                <div class="rating-badge">
                    <span class="stars">
                        <?php 
                        $rating = round($avg_rating);
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                echo "★";
                            } else {
                                echo "☆";
                            }
                        }
                        ?>
                    </span>
                    <span class="rating-text"><?php echo number_format($avg_rating, 1); ?> (<?php echo $total_reviews; ?> reviews)</span>
                </div>
            </div>
        </div>

        <div class="doctor-details">
            <p><b>Email:</b> <?php echo $doctor['email']; ?></p>
            <p><b>Phone:</b> <?php echo $doctor['phone']; ?></p>
            <p><b>Experience:</b> <?php echo $doctor['experience_years']; ?> years</p>
            <p><b>Consultation Fee:</b> &#2547; <?php echo $doctor['consultation_fee']; ?></p>
            <p><b>Bio:</b> <?php echo $doctor['bio']; ?></p>
        </div>

        <h3>Availability Schedule</h3>
        <?php if (empty($availability)) { ?>
            <p>No availability schedule set.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Slot Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($availability as $slot) { ?>
                        <tr>
                            <td><?php echo $slot['day_of_week']; ?></td>
                            <td><?php echo date("h:i A", strtotime($slot['start_time'])); ?></td>
                            <td><?php echo date("h:i A", strtotime($slot['end_time'])); ?></td>
                            <td><?php echo $slot['slot_duration_minutes']; ?> minutes</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

        <p class="book-action">
            <a href="../../controllers/patientBookAppointmentShowController.php?doctor_id=<?php echo $doctor['id']; ?>" class="book-btn">Book Appointment</a>
        </p>

    </div>

    <div class="card">
        <h3>Patient Reviews (<?php echo $total_reviews; ?>)</h3>
        
        <?php if (empty($reviews)) { ?>
            <p>No reviews yet.</p>
        <?php } else { ?>
            <?php foreach ($reviews as $review) { ?>
                <div class="review-card">
                    <div class="review-header">
                        <span class="reviewer-name"><?php echo $review['patient_name']; ?></span>
                        <span class="review-stars">
                            <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $review['rating']) {
                                    echo "★";
                                } else {
                                    echo "☆";
                                }
                            }
                            ?>
                        </span>
                    </div>
                    <p class="review-text"><?php echo $review['review_text']; ?></p>
                    <p class="review-date"><?php echo date("M d, Y", strtotime($review['created_at'])); ?></p>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

</body>
</html>