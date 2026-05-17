<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorProfile.php';

$doctor_id = $_SESSION['doctor_id'];
$profileModel = new ModelDoctorProfile($conn);
$profile = $profileModel->getProfile($doctor_id);
$specializations = $profileModel->getSpecializations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">Medi<span>Book</span></div>
        <ul class="sidebar-nav">
            <li><a href="ViewDocDashboard.php">Dashboard</a></li>
            <li><a href="ViewDocAppointments.php">Appointments</a></li>
            <li><a href="ViewDocAvailability.php">Availability</a></li>
            <li><a href="ViewDocProfile.php" class="active">My Profile</a></li>
            <li><a href="ViewDocReviews.php">Reviews</a></li>
            <li><a href="ViewDocBilling.php">Earnings</a></li>
        </ul>
        <a href="../controllers/ContDocLogin.php?logout=true" class="logout-btn">Log Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Manage Professional Profile</h1>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" style="background-color: #d1fae5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                Profile updated successfully!
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="../controllers/ContDocProfile.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Full Name (Read-only)</label>
                    <input type="text" id="name" value="<?php echo htmlspecialchars($profile['name']); ?>" readonly style="background-color: #f3f4f6;">
                </div>
                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <select id="specialization" name="specialization_id">
                        <?php foreach ($specializations as $spec): ?>
                            <option value="<?php echo $spec['id']; ?>" <?php echo ($spec['id'] == $profile['specialization_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($spec['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bio">Biography</label>
                    <textarea id="bio" name="bio"><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="fee">Consultation Fee ($)</label>
                    <input type="number" id="fee" name="consultation_fee" step="0.01" value="<?php echo htmlspecialchars($profile['consultation_fee']); ?>">
                </div>
                <div class="form-group">
                    <label for="experience">Years of Experience</label>
                    <input type="number" id="experience" name="experience_years" value="<?php echo htmlspecialchars($profile['experience_years']); ?>">
                </div>
                <div class="form-group">
                    <label for="license">License Number</label>
                    <input type="text" id="license" name="license_number" value="<?php echo htmlspecialchars($profile['license_number']); ?>">
                </div>
                <!-- Photo upload placeholder -->
                <div class="form-group">
                    <label for="photo">Profile Photo</label>
                    <input type="file" id="photo" name="photo">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
