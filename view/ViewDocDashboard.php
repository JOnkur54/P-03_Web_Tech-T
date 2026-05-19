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
$todayAppointments = $appointmentModel->getTodaySchedule($doctor_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
        <!-- fixed CSS -->
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f3f4f6;
        }
        .sidebar {
            width: 250px;
            background-color: white;
            border-right: 1px solid var(--light-gray);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .sidebar .logo {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--dark);
        }
        .sidebar .logo span {
            color: var(--primary);
        }
        .sidebar-nav {
            list-style: none;
            flex-grow: 1;
        }
        .sidebar-nav li {
            margin-bottom: 10px;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--gray);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background-color: var(--secondary);
            color: var(--primary);
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 700;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-profile .name {
            font-weight: 500;
            font-size: 14px;
        }
        .user-profile .role {
            font-size: 12px;
            color: var(--gray);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        .stat-card h3 {
            font-size: 14px;
            color: var(--gray);
            margin-bottom: 10px;
        }
        .stat-card .value {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }
        .table-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid var(--light-gray);
            font-size: 14px;
        }
        th {
            background-color: #f9fafb;
            font-weight: 600;
            color: var(--gray);
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-confirmed { background-color: #d1fae5; color: #059669; }
        .status-checked_in { background-color: #e0f2fe; color: #0284c7; }
        .status-completed { background-color: #e5e7eb; color: #4b5563; }
        .status-cancelled { background-color: #fee2e2; color: #dc2626; }
        .status-no_show { background-color: #f3f4f6; color: #6b7280; }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
        }
        .logout-btn {
            margin-top: auto;
            color: var(--danger);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 12px;
            display: flex;
            align-items: center;
            border-radius: 8px;
        }
        .logout-btn:hover {
            background-color: #fee2e2;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">Medi<span>Book</span></div>
        <ul class="sidebar-nav">
            <li><a href="ViewDocDashboard.php" class="active">Dashboard</a></li>
            <li><a href="ViewDocAppointments.php">Appointments</a></li>
            <li><a href="ViewDocAvailability.php">Availability</a></li>
            <li><a href="ViewDocProfile.php">My Profile</a></li>
            <li><a href="ViewDocReviews.php">Reviews</a></li>
            <li><a href="ViewDocBilling.php">Earnings</a></li>
        </ul>
        <a href="../controllers/ContDocLogin.php?logout=true" class="logout-btn">Log Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1>Dashboard</h1>
                <p style="color: var(--gray); font-size: 14px;">Welcome back, Dr. <?php echo htmlspecialchars($_SESSION['name']); ?></p>
            </div>
            <div class="user-profile">
                <div>
                    <div class="name"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
                    <div class="role">Doctor</div>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Today's Appointments</h3>
                <div class="value"><?php echo count($todayAppointments); ?></div>
            </div>
            <!-- Placeholder stats, will be dynamic later -->
            <div class="stat-card">
                <h3>Pending Requests</h3>
                <div class="value">5</div>
            </div>
            <div class="stat-card">
                <h3>Earnings (This Month)</h3>
                <div class="value">$1,200</div>
            </div>
        </div>

        <div class="section-title">Today's Schedule</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Patient Name</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($todayAppointments)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--gray);">No appointments scheduled for today.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($todayAppointments as $appt): ?>
                            <tr>
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
                                        <!-- AJAX button for check-in -->
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
