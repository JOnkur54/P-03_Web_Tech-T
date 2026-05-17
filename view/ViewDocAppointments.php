<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorAppointment.php';

$doctor_id = $_SESSION['doctor_id'];
$appointmentModel = new ModelDoctorAppointment($conn);
$weeklyAppointments = $appointmentModel->getWeeklySchedule($doctor_id);
$upcomingFollowUps = $appointmentModel->getUpcomingFollowUps($doctor_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">Medi<span>Book</span></div>
        <ul class="sidebar-nav">
            <li><a href="ViewDocDashboard.php">Dashboard</a></li>
            <li><a href="ViewDocAppointments.php" class="active">Appointments</a></li>
            <li><a href="ViewDocAvailability.php">Availability</a></li>
            <li><a href="ViewDocProfile.php">My Profile</a></li>
            <li><a href="ViewDocReviews.php">Reviews</a></li>
            <li><a href="ViewDocBilling.php">Earnings</a></li>
        </ul>
        <a href="../controllers/ContDocLogin.php?logout=true" class="logout-btn">Log Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>My Appointments</h1>
        </div>

        <div class="section-title">Upcoming Appointments (Next 7 Days)</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient Name</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($weeklyAppointments)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--gray);">No appointments scheduled for the next 7 days.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($weeklyAppointments as $appt): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($appt['appointment_date'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($appt['appointment_time'])); ?></td>
                                <td><?php echo htmlspecialchars($appt['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($appt['reason']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $appt['status']; ?>">
                                        <?php echo str_replace('_', ' ', $appt['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($appt['status'] === 'pending'): ?>
                                        <a href="../controllers/ContDocAppointment.php?action=confirm&id=<?php echo $appt['id']; ?>" class="btn btn-primary btn-sm">Confirm</a>
                                        <a href="../controllers/ContDocAppointment.php?action=reject&id=<?php echo $appt['id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                                    <?php elseif ($appt['status'] === 'confirmed'): ?>
                                        <button onclick="checkIn(<?php echo $appt['id']; ?>)" class="btn btn-primary btn-sm">Check In</button>
                                        <a href="../controllers/ContDocAppointment.php?action=noshow&id=<?php echo $appt['id']; ?>" class="btn btn-gray btn-sm">No-Show</a>
                                    <?php elseif ($appt['status'] === 'checked_in'): ?>
                                        <a href="ViewDocConsultation.php?appointment_id=<?php echo $appt['id']; ?>" class="btn btn-primary btn-sm">Start Consultation</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="section-title">Upcoming Follow-Up Appointments</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Follow-up Date</th>
                        <th>Patient Name</th>
                        <th>Symptoms (Last Visit)</th>
                        <th>Diagnosis (Last Visit)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($upcomingFollowUps)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--gray);">No upcoming follow-up appointments found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($upcomingFollowUps as $fu): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($fu['follow_up_date'])); ?></td>
                                <td><?php echo htmlspecialchars($fu['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($fu['symptoms']); ?></td>
                                <td><?php echo htmlspecialchars($fu['diagnosis']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function checkIn(appointmentId) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../controllers/ContDocAppointment.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('Patient checked in successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            };
            xhr.send('action=check_in_ajax&id=' + appointmentId);
        }
    </script>
</body>
</html>
