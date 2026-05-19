<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['doctor_details'])) {
    $did = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
    header("Location: ../../controllers/patientDoctorDetailsShowController.php?doctor_id=" . $did);
    exit();
}
$doctor        = $_SESSION['doctor_details'];
$availability  = isset($_SESSION['doctor_availability']) ? $_SESSION['doctor_availability'] : [];
$reviews       = isset($_SESSION['doctor_reviews'])      ? $_SESSION['doctor_reviews']      : [];
$avg_rating    = isset($_SESSION['avg_rating'])          ? $_SESSION['avg_rating']          : 0;
$total_reviews = isset($_SESSION['total_reviews'])       ? $_SESSION['total_reviews']       : 0;
unset($_SESSION['doctor_details'], $_SESSION['doctor_availability'], $_SESSION['doctor_reviews'], $_SESSION['avg_rating'], $_SESSION['total_reviews']);
if (!$doctor) { header("Location: patientDoctors.php"); exit(); }

$photo_path = null;
foreach (['.jpg', '.jpeg', '.png', '.webp'] as $ext) {
    $p = "../profilePicture/user_" . $doctor['user_id'] . $ext;
    if (file_exists($p)) { $photo_path = $p; break; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Details</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <div class="doctor-header">
            <div class="doctor-photo">
                <?php if ($photo_path) { ?>
                    <img src="<?php echo $photo_path; ?>" alt="Doctor Photo">
                <?php } else { ?>
                    <div class="no-photo"><?php echo strtoupper(substr($doctor['doctor_name'],0,1)); ?></div>
                <?php } ?>
            </div>
            <div class="doctor-info">
                <h2><?php echo htmlspecialchars($doctor['doctor_name']); ?></h2>
                <p class="specialization"><?php echo htmlspecialchars($doctor['specialization']); ?></p>
                <div style="margin-top:8px;">
                    <span class="stars"><?php for ($i=1;$i<=5;$i++) { echo $i<=round($avg_rating) ? "★" : "☆"; } ?></span>
                    <span class="rating-text"><?php echo number_format($avg_rating,1); ?> (<?php echo $total_reviews; ?> reviews)</span>
                </div>
            </div>
        </div>
        <table style="margin-top:16px;">
            <tr><th style="width:30%;">Experience</th><td><?php echo $doctor['experience_years']; ?> years</td></tr>
            <tr><th>Consultation Fee</th><td>&#2547; <?php echo $doctor['consultation_fee']; ?></td></tr>
            <tr><th>License No.</th><td><?php echo htmlspecialchars($doctor['license_number']); ?></td></tr>
            <tr><th>Bio</th><td><?php echo htmlspecialchars($doctor['bio']); ?></td></tr>
        </table>
        <h3 style="margin-top:20px;">Availability Schedule</h3>
        <?php if (empty($availability)) { ?>
            <p>No schedule available.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>Day</th><th>Start</th><th>End</th><th>Slot Duration</th></tr></thead>
                <tbody>
                <?php foreach ($availability as $slot) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($slot['day_of_week']); ?></td>
                    <td><?php echo date("h:i A", strtotime($slot['start_time'])); ?></td>
                    <td><?php echo date("h:i A", strtotime($slot['end_time'])); ?></td>
                    <td><?php echo $slot['slot_duration_minutes']; ?> min</td>
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
            <?php foreach ($reviews as $r) { ?>
            <div class="review-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <strong style="font-size:13px;"><?php echo htmlspecialchars($r['patient_name']); ?></strong>
                    <span class="stars" style="font-size:14px;"><?php for ($i=1;$i<=5;$i++) { echo $i<=$r['rating'] ? "★" : "☆"; } ?></span>
                </div>
                <p style="font-size:13px;"><?php echo htmlspecialchars($r['review_text']); ?></p>
                <p class="review-date"><?php echo date("M d, Y", strtotime($r['created_at'])); ?></p>
            </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>