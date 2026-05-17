<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorAvailability.php';

$doctor_id = $_SESSION['doctor_id'];
$availabilityModel = new ModelDoctorAvailability($conn);
$currentAvailability = $availabilityModel->getAvailability($doctor_id);
$leaveDates = $availabilityModel->getLeaveDates($doctor_id);

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Availability & Leave - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">Medi<span>Book</span></div>
        <ul class="sidebar-nav">
            <li><a href="ViewDocDashboard.php">Dashboard</a></li>
            <li><a href="ViewDocAppointments.php">Appointments</a></li>
            <li><a href="ViewDocAvailability.php" class="active">Availability</a></li>
            <li><a href="ViewDocProfile.php">My Profile</a></li>
            <li><a href="ViewDocReviews.php">Reviews</a></li>
            <li><a href="ViewDocBilling.php">Earnings</a></li>
        </ul>
        <a href="../controllers/ContDocLogin.php?logout=true" class="logout-btn">Log Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Manage Availability & Leave</h1>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" style="background-color: #d1fae5; color: #059669; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                Operation successful!
            </div>
        <?php endif; ?>

        <div class="section-title">Weekly Availability</div>
        <div class="card">
            <form action="../controllers/ContDocAvailability.php" method="POST">
                <input type="hidden" name="action" value="update_availability">
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Available</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Slot Duration (Min)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($days as $day): ?>
                            <?php 
                                $avail = $currentAvailability[$day] ?? null;
                                $is_avail = $avail ? $avail['is_available'] : 0;
                                $start = $avail ? $avail['start_time'] : '09:00:00';
                                $end = $avail ? $avail['end_time'] : '17:00:00';
                                $duration = $avail ? $avail['slot_duration_minutes'] : 30;
                            ?>
                            <tr>
                                <td><?php echo $day; ?></td>
                                <td>
                                    <input type="checkbox" name="avail[<?php echo $day; ?>][is_available]" value="1" <?php echo $is_avail ? 'checked' : ''; ?>>
                                </td>
                                <td>
                                    <input type="time" name="avail[<?php echo $day; ?>][start_time]" value="<?php echo date('H:i', strtotime($start)); ?>">
                                </td>
                                <td>
                                    <input type="time" name="avail[<?php echo $day; ?>][end_time]" value="<?php echo date('H:i', strtotime($end)); ?>">
                                </td>
                                <td>
                                    <select name="avail[<?php echo $day; ?>][slot_duration]">
                                        <option value="15" <?php echo ($duration == 15) ? 'selected' : ''; ?>>15 mins</option>
                                        <option value="30" <?php echo ($duration == 30) ? 'selected' : ''; ?>>30 mins</option>
                                        <option value="45" <?php echo ($duration == 45) ? 'selected' : ''; ?>>45 mins</option>
                                        <option value="60" <?php echo ($duration == 60) ? 'selected' : ''; ?>>60 mins</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Save Availability</button>
            </form>
        </div>

        <div class="section-title">Mark Leave / Unavailable Dates</div>
        <div class="card">
            <form action="../controllers/ContDocAvailability.php" method="POST" style="display: flex; gap: 20px; align-items: flex-end;">
                <input type="hidden" name="action" value="add_leave">
                <div class="form-group" style="flex: 1;">
                    <label for="leave_date">Date</label>
                    <input type="date" id="leave_date" name="leave_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group" style="flex: 2;">
                    <label for="reason">Reason (Optional)</label>
                    <input type="text" id="reason" name="reason" placeholder="Vacation, Conference, etc.">
                </div>
                <button type="submit" class="btn btn-primary" style="height: 42px; margin-bottom: 15px;">Add Leave</button>
            </form>

            <div class="section-title" style="font-size: 16px; margin-top: 20px;">Planned Leave Dates</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($leaveDates)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--gray);">No leave dates planned.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($leaveDates as $leave): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($leave['leave_date'])); ?></td>
                                <td><?php echo htmlspecialchars($leave['reason']); ?></td>
                                <td>
                                    <a href="../controllers/ContDocAvailability.php?action=delete_leave&id=<?php echo $leave['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this leave date?')">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
